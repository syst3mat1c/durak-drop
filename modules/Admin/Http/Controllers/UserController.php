<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Requests\User\AddMoneyRequest;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class UserController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var UserRepository */
    protected $userRepo;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    /**
     * @param int $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchByProviderId(int $user)
    {
        $user = $this->userRepo->findByProviderId($user);
        abort_if(!$user, 404);

        return redirect()->route('admin.users.show', $user);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $items = $user->items()->with(['boxItem.box'])->latest('updated_at')->get();
        $orders = $this->userRepo->orders($user);
        return view('admin::modules.users.show', compact('user', 'items', 'orders'));
    }

    /**
     * @param User $user
     * @param AddMoneyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMoney(User $user, AddMoneyRequest $request)
    {
        $this->userRepo->addMoney($user, $request->get('money'));
        return $this->routeSuccess('admin.users.show', compact('user'));
    }
}
