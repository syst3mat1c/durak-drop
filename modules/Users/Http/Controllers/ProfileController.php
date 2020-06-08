<?php

namespace Modules\Users\Http\Controllers;

use App\Services\UI\HeaderService;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Illuminate\Support\Collection;
use Modules\Main\Entities\Item;
use Modules\Main\Entities\Withdraw;
use Modules\Main\Repositories\ItemRepository;
use Modules\Main\Repositories\WithdrawRepository;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\Profile\WithdrawCoinsRequest;
use Modules\Users\Http\Requests\Profile\WithdrawRequest;
use Modules\Users\Repositories\UserRepository;

class ProfileController extends Controller
{
    use AuthorizesRequests, Responseable;

    protected $userRepo;
    protected $withdrawRepo;
    protected $headerService;

    public function __construct(UserRepository $userRepository, WithdrawRepository $withdrawRepository, HeaderService $headerService)
    {
        $this->userRepo         = $userRepository;
        $this->withdrawRepo     = $withdrawRepository;
        $this->headerService    = $headerService;
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $this->headerService->setTitle($user->name);
        $items = $this->userRepo->items($user);
        return view('users::profiles.show', compact('user', 'items'));
    }

    /**
     * @param WithdrawRequest $request
     * @return array
     */
    public function withdrawCoins(WithdrawCoinsRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $offer = (int) $request->get('amount');

        if ($offer <= $user->coins_smart) {
            /** @var Collection $items */
            $items = $user->items()->close()->coins()->get();

            $itemCoins = $items->sum('amount');

            $this->userRepo->addCoins($user, $itemCoins);
            $this->userRepo->addRating($user, $itemCoins);

            $items->each(function(Item $item) {
                app(ItemRepository::class)->update($item, [
                    'status' => Item::STATUS_WITHDRAW
                ]);
            });

            $this->withdrawRepo->store([
                'user_id'   => $user->id,
                'amount'    => $offer,
                'type'      => Withdraw::TYPE_COINS,
                'status'    => Withdraw::STATUS_PENDING,
            ]);
            $this->userRepo->addCoins($user, -$offer);

            $this->userRepo->flushSmartBalance($user);

            return [
                'status'    => true,
                'message'   => 'Вы успешно создали заявку на вывод монет',
                'balance'   => $this->userRepo->serializeBalance($user)
            ];
        } else {
            $message = 'У Вас нет в наличии столько монет!';
        }

        return [
            'status' => false,
            'message' => $message
        ];
    }

    /**
     * @param WithdrawRequest $request
     * @return array
     */
    public function withdrawCredits(WithdrawRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $offer = (int) $request->get('amount');

        if ($offer <= $user->credits_smart) {
            /** @var Collection $items */
            $items = $user->items()->close()->credits()->get();

            $itemCredits = $items->sum('amount');

            $this->userRepo->addCredits($user, $itemCredits);
            $this->userRepo->addRating($user, $itemCredits);

            $items->each(function(Item $item) {
                app(ItemRepository::class)->update($item, [
                    'status' => Item::STATUS_WITHDRAW
                ]);
            });

            $this->withdrawRepo->store([
                'user_id'   => $user->id,
                'amount'    => $offer,
                'type'      => Withdraw::TYPE_CREDITS,
                'status'    => Withdraw::STATUS_PENDING,
            ]);
            $this->userRepo->addCredits($user, -$offer);

            $this->userRepo->flushSmartBalance($user);

            return [
                'status'    => true,
                'message'   => 'Вы успешно создали заявку на вывод кредитов',
                'balance'   => $this->userRepo->serializeBalance($user)
            ];
        } else {
            $message = 'У Вас нет в наличии столько кредитов!';
        }

        return [
            'status' => false,
            'message' => $message
        ];
    }
}
