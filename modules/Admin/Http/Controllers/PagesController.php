<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Services\MainServices\AdminStatisticService;
use Modules\Admin\Services\ProfitService\ProfitService;
use Modules\Main\Repositories\BoxRepository;
use Modules\Users\Repositories\UserRepository;

class PagesController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var BoxRepository */
    protected $boxRepo;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * PagesController constructor.
     * @param BoxRepository $boxRepository
     * @param UserRepository $userRepository
     */
    public function __construct(BoxRepository $boxRepository, UserRepository $userRepository)
    {
        $this->boxRepo = $boxRepository;
        $this->userRepo = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::pages.index');
    }

    /**
     * @param ProfitService $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profit(ProfitService $service)
    {
        return view('admin::pages.profit', compact('service'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function referrals()
    {
        $referrals = $this->userRepo->getBestReferrals();
        return view('admin::pages.referrals', compact('referrals'));
    }
}
