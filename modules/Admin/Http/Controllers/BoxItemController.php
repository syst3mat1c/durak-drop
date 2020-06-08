<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponseable;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Controllers\Traits\BoxContentable;
use Modules\Admin\Http\Requests\BoxItem\BoxItemRarityRequest;
use Modules\Admin\Http\Requests\BoxItem\BoxItemStoreRequest;
use Modules\Admin\Http\Requests\BoxItem\BoxItemTypeRequest;
use Modules\Admin\Http\Requests\BoxItem\BoxItemUpdateRequest;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Repositories\BoxItemRepository;
use Modules\Main\Repositories\BoxRepository;
use Modules\Main\Repositories\BuyerItemRepository;

class BoxItemController extends Controller
{
    use AuthorizesRequests, Responseable, ApiResponseable, BoxContentable {
        ApiResponseable::success insteadof Responseable;
        ApiResponseable::success as apiSuccess;
    }

    protected $boxItemRepo;

    /**
     * BoxItemController constructor.
     * @param BoxItemRepository $boxItemRepository
     */
    public function __construct(BoxItemRepository $boxItemRepository)
    {
        $this->boxItemRepo = $boxItemRepository;
    }

    /**
     * @param BoxItem $boxItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BoxItem $boxItem)
    {
        return view('admin::modules.box_items.create', compact('boxItem'));
    }

    /**
     * @param BoxItemStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BoxItemStoreRequest $request)
    {
        $boxItem = $this->boxItemRepo->store(
            $request->only($request->validated)
        );

        /** @var BoxItem $boxItem */
        $boxItem = $boxItem->fresh();

        return $this->routeSuccess('admin.boxes.edit', ['box' => $boxItem->box]);
    }

    /**
     * @param BoxItem $boxItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BoxItem $boxItem)
    {
        return view('admin::modules.box_items.edit', compact('boxItem'));
    }

    /**
     * @param BoxItem $boxItem
     * @param BoxItemUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BoxItem $boxItem, BoxItemUpdateRequest $request)
    {
        $this->boxItemRepo->update(
            $boxItem, $request->only($request->validated)
        );

        return $this->routeSuccess('admin.boxes.edit', ['box' => $boxItem->box]);
    }

    /**
     * @param BoxItem $boxItem
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(BoxItem $boxItem)
    {
        $box = $boxItem->box;
        $this->boxItemRepo->delete($boxItem);
        return $this->routeSuccess('admin.boxes.edit', compact('box'));
    }
}
