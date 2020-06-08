<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Controllers\Traits\BoxContentable;
use Modules\Admin\Http\Requests\Box\BoxAddItemRequest;
use Modules\Admin\Http\Requests\Box\BoxFakeItemRequest;
use Modules\Admin\Http\Requests\Box\BoxRequest;
use Modules\Main\Entities\Box;
use Modules\Main\Repositories\BoxItemRepository;
use Modules\Main\Repositories\BoxRepository;
use Modules\Main\Repositories\BuyerItemRepository;

class BoxController extends Controller
{
    use AuthorizesRequests, Responseable, BoxContentable;

    protected $boxRepo;
    protected $boxItemRepo;

    public function __construct(BoxRepository $boxRepository, BoxItemRepository $boxItemRepository)
    {
        $this->boxRepo          = $boxRepository;
        $this->boxItemRepo      = $boxItemRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $boxes = $this->boxRepo->all();
        return view('admin::modules.boxes.index', compact('boxes'));
    }

    /**
     * @param Box $box
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Box $box)
    {
        $proposedOrder = $this->boxRepo->proposeOrder();
        return view('admin::modules.boxes.create', compact('box', 'proposedOrder'));
    }

    /**
     * @param BoxRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BoxRequest $request)
    {
        $this->boxRepo->store($request->only($request->validated));
        return $this->routeSuccess('admin.boxes.index');
    }

    /**
     * @param Box $box
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Box $box)
    {
        $boxItems = $this->boxRepo->getItems($box);
        return view('admin::modules.boxes.edit', compact('box', 'boxItems'));
    }

    /**
     * @param Box $box
     * @param BoxRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Box $box, BoxRequest $request)
    {
        $this->boxRepo->update($box, $request->only($request->validated));
        return $this->routeSuccess('admin.boxes.index');
    }

    /**
     * @param Box $box
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Box $box)
    {
        $this->boxRepo->delete($box);
        return $this->routeSuccess('admin.boxes.index');
    }
}
