<?php

namespace Modules\Main\Repositories;

use Illuminate\Support\Collection;
use Modules\Main\Entities\Category;

class CategoryRepository
{
    /**
     * @return mixed
     */
    public function resourceSelect()
    {
        return Category::pluck('title', 'id');
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return Category::get();
    }

    /**
     * @param array $data
     * @return Category
     */
    public function store(array $data)
    {
        return Category::create($data);
    }

    /**
     * @param Category $category
     * @param array $data
     * @return bool
     */
    public function update(Category $category, array $data)
    {
        return $category->update($data);
    }

    /**
     * @param Category $category
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Category $category)
    {
        return $category->delete();
    }
}
