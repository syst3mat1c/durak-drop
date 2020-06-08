<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponseable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Requests\Chance\ChanceRequest;
use Modules\Admin\Http\Requests\Chance\ChanceStatusRequest;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;
use Modules\Users\Entities\Chance;
use Modules\Users\Repositories\ChanceRepository;
use Modules\Users\Repositories\UserRepository;

class ChanceController extends Controller
{
    use AuthorizesRequests, Responseable, ApiResponseable {
        ApiResponseable::success insteadof Responseable;
        ApiResponseable::success as apiSuccess;
    }

    protected $chanceRepo;
    protected $userRepo;

    /**
     * ChanceController constructor.
     * @param ChanceRepository $chanceRepository
     * @param UserRepository $userRepository
     */
    public function __construct(ChanceRepository $chanceRepository, UserRepository $userRepository)
    {
        $this->chanceRepo = $chanceRepository;
        $this->userRepo = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $chances = $this->chanceRepo->all();
        return view('admin::modules.chances.index', compact('chances'));
    }

    /**
     * @param ChanceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ChanceRequest $request)
    {
        $user = $this->userRepo->findByProviderId($request->get('provider_id'));
        $json = $this->mutateJson($request->get('json'));
        $this->chanceRepo->store($user, compact('json'));

        if ($request->has('with-back')) {
            return $this->backSuccess();
        } else {
            return $this->routeSuccess('admin.chances.index');
        }
    }

    /**
     * @param Chance $chance
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Chance $chance)
    {
        $this->chanceRepo->delete($chance);

        return $this->routeSuccess('admin.chances.index');
    }

    /**
     * @param Chance $chance
     * @param ChanceStatusRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setStatus(Chance $chance, ChanceStatusRequest $request)
    {
        $this->chanceRepo->update($chance, $request->only('status'));

        return $this->apiSuccess(200, []);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function mutateJson(string $text)
    {
        return collect(explode(',', $text))->map(function($item) {
            return in_array($item, BoxItem::WEALTHS)
                ? (int) $item
                : false;
        })->filter()->values()->toJson(256);
    }
}
