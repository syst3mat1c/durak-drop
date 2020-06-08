<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponseable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Main\Entities\Item;
use Modules\Main\Repositories\ItemRepository;
use Modules\Users\Repositories\UserRepository;

class ItemController extends Controller
{
    use AuthorizesRequests, Responseable, ApiResponseable {
        ApiResponseable::success insteadof Responseable;
        ApiResponseable::error insteadof Responseable;
        ApiResponseable::success as apiSuccess;
        ApiResponseable::error as apiError;
    }

    protected $itemRepo;
    protected $userRepo;

    public function __construct(ItemRepository $itemRepository, UserRepository $userRepository)
    {
        $this->itemRepo = $itemRepository;
        $this->userRepo = $userRepository;
    }

    /**
     * @param Item $item
     * @param Request $request
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function sell(Item $item, Request $request)
    {
        $this->authorize('manage', $item);
        $this->authorize('sell', $item);

        $this->itemRepo->update($item, [
            'status' => Item::STATUS_OPEN
        ]);
        $itemPrice  = $item->price;
        $user       = $request->user();

        $this->userRepo->addMoney($user, $itemPrice);

        $this->userRepo->flushSmartBalance($user);

        $user = $user->fresh();
        $item->fresh();

        return [
            'status'    => true,
            'message'   => "Вы успешно продали предмет за {$item->price_human}",
            'rating'    => $user->rating,
            'boxes'     => $user->items()->count(),
            'balance'   => $this->userRepo->serializeBalance($user),
            'content'   => view('users::profiles.partials.show_footer.manager_items', compact('user'))->render(),
        ];
    }

    /**
     * @param Item $item
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function open(Item $item, Request $request)
    {
        $this->authorize('manage', $item);
        $this->authorize('open', $item);

        $user = $request->user();

        $this->itemRepo->update($item, ['status' => Item::STATUS_OPEN]);

        return $this->apiSuccess(200, [
            'code' => $item->code,
            'url' => $item->activate_url,
            'rating'    => $user->rating_current,
            'cases'     => $user->cases_current,
            'money'     => $user->money_human,
            'content' => view('users::profiles.partials.box', compact('item', 'user'))->render(),
        ]);
    }
}
