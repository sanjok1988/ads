<?php

namespace Sanjok\Blog\Api;

use Illuminate\Http\Request;
use Sanjok\Blog\Contracts\ICategory;
use Sanjok\Blog\Http\Controllers\BaseController;
use Sanjok\Blog\Http\Resources\Category\CategoryResource;
use Sanjok\Blog\Http\Requests\Category\CategoryStoreRequest;
use Sanjok\Blog\Models\Category;

class CategoryApiController extends BaseController
{
    public function __construct(ICategory $category)
    {
        $this->category = $category;
    }

    public function show($category_id)
    {
        $category = $this->category->findOrFail($category_id);
        return new CategoryResource($category);
    }

    public function edit(Category $category)
    {

        return new CategoryResource($category);
    }

    public function index()
    {
        return CategoryResource::collection($this->category->all());
    }

    public function store(CategoryStoreRequest $request)
    {

        return $this->category->createCategory($request->all());
    }

    public function update(CategoryStoreRequest $request, $id)
    {

        return $this->category->updateCategory($request->all(), $id);
    }

    public function delete($id)
    {
    }

    public function toggleStatus($id)
    {
        return new CategoryResource($this->category->toggleStatus($id));
    }
}
