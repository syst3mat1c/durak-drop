<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Requests\Withdraw\WithdrawRequest;
use Modules\Main\Entities\Withdraw;
use Modules\Main\Repositories\WithdrawRepository;

class WithdrawController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var WithdrawRepository */
    protected $withdrawRepo;

    /**
     * WithdrawController constructor.
     * @param WithdrawRepository $withdrawRepository
     */
    public function __construct(WithdrawRepository $withdrawRepository)
    {
        $this->withdrawRepo = $withdrawRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $withdraws = $this->withdrawRepo->all();
        return view('admin::modules.withdraws.index', compact('withdraws'));
    }

    /**
     * @param Withdraw $withdraw
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Withdraw $withdraw)
    {
        return view('admin::modules.withdraws.edit', compact('withdraw'));
    }

    /**
     * @param Withdraw $withdraw
     * @param WithdrawRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Withdraw $withdraw, WithdrawRequest $request)
    {
        $this->withdrawRepo->update($withdraw, $request->only($request->validated));
        return $this->routeSuccess('admin.withdraws.index');
    }

    /**
     * @param Withdraw $withdraw
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Withdraw $withdraw)
    {
        $this->withdrawRepo->delete($withdraw);
        return $this->routeSuccess('admin.withdraws.index');
    }
}
