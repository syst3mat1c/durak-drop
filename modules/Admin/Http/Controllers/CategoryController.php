<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Requests\Category\CategoryRequest;
use Modules\Main\Entities\Category;
use Modules\Main\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    use AuthorizesRequests, Responseable;

    protected $categoryRepo;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = $this->categoryRepo->all();
        return view('admin::modules.categories.index', compact('categories'));
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryRepo->store($request->only($request->validated));
        return $this->routeSuccess('admin.categories.index');
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $this->categoryRepo->delete($category);
        return $this->routeSuccess('admin.categories.index');
    }
}
