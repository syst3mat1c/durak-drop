<?php

namespace App\Services\Billing\Controllers;

use App\Services\Billing\Events\PaymentSuccessEvent;
use App\Services\Billing\Requests\BillingCallbackRequest;
use App\Services\Billing\Requests\BillingFormRequest;
use App\Services\Billing\Services\BillingService;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Services\UI\HeaderService;
use Modules\Main\Repositories\DepositRepository;
use Modules\Main\Repositories\PromocodeRepository;

class BillingController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var BillingService */
    protected $billingService;

    /**
     * BillingController constructor.
     * @param BillingService $billingService
     * @return void
     */
    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * @param BillingFormRequest $request
     * @return array
     * @throws \Throwable
     */
    public function getForm(BillingFormRequest $request)
    {
        $billingService = $this->billingService;
        $amount         = $request->get('amount');
        $orderId        = $request->user()->id;
        $sign           = $this->billingService->hashForm($amount, $orderId);

        return [
            'status' => true,
            'view' => view('billing::form', compact(
                'billingService', 'amount', 'orderId', 'sign'
            ))->render()
        ];
    }

    /**
     * @param BillingFormRequest $request
     * @return array
     * @throws \Throwable
     */
    public function getLink(BillingFormRequest $request)
    {
        $billingService = $this->billingService;
        $amount         = $request->get('amount');

        $promocode = app(PromocodeRepository::class)->findByCode($request->get('promo'));
        $promocodeId = null;

        if ($promocode) {
            if ($promocode->min_amount <= (float) $amount) {
                if (is_null($promocode->attempts) || $promocode->attempts > $promocode->deposits_count) {
                    $promocodeId = $promocode->id;
                }
            }
        }

        $depositOrder   = app(DepositRepository::class)->create($request->user(), (float) $amount, $promocodeId);

        $orderId        = $depositOrder->id;
        $sign           = $this->billingService->hashForm($amount, $orderId);

        $query = http_build_query([
            'm'     => $billingService->merchantId(),
            'oa'    => $amount,
            'o'     => $orderId,
            's'     => $sign
        ]);

        return [
            'status' => true,
            'link' => "https://www.free-kassa.ru/merchant/cash.php?{$query}"
        ];
    }

    /**
     * @param BillingCallbackRequest $request
     * @return string
     */
    public function callback(BillingCallbackRequest $request)
    {
        $amount  = $request->get('AMOUNT');
        $order   = $request->get('MERCHANT_ORDER_ID');
        $sign    = $request->get('SIGN');

        $serverSign = $this->billingService->hashCallback($amount, $order);

        if ($serverSign != $sign) {
            return response(['message' => 'wrong sign'], 500);
        }

        event(
            new PaymentSuccessEvent(
                (int) $order, (float) $amount
            )
        );

        return 'YES';
    }

    /**
     * @return mixed
     */
    protected function getIp()
    {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }
}
