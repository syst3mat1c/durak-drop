<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Requests\Promocode\PromocodeRequest;
use Modules\Main\Entities\Promocode;
use Modules\Main\Repositories\PromocodeRepository;

class PromocodeController extends Controller
{
    use AuthorizesRequests, Responseable;

    protected $promocodeRepo;

    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepo = $promocodeRepository;
    }

    public function index()
    {
        $promocodes = $this->promocodeRepo->all();
        return view('admin::modules.promocodes.index', compact('promocodes'));
    }

    public function show()
    {
    }

    public function create()
    {
    }

    /**
     * @param PromocodeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PromocodeRequest $request)
    {
        $this->promocodeRepo->store($request->only($request->validated));
        return $this->routeSuccess('admin.promocodes.index');
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    /**
     * @param Promocode $promocode
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Promocode $promocode)
    {
        $this->promocodeRepo->delete($promocode);
        return $this->routeSuccess('admin.promocodes.index');
    }
}
