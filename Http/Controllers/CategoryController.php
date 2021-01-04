<?php

namespace Sanjok\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Category;
use Sanjok\Blog\Contracts\ICategory;

class CategoryController extends BaseController
{
    public function __construct(ICategory $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $form = 'create';
        $type = '';

        $categories = $this->category->paginate(10);


        return view($this->getView('backend.category.index'), compact('form', 'categories', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->category->createCategory($request->all());
        return redirect()->route('category.index', ['type' => $request->type])->with('success', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Request $request)
    {
        $form = 'edit';

        $categories = $this->category->paginate(10);
        return view($this->getView('backend.category.index'), compact('form', 'category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->category->updateCategory($request->all(), $category);
        return redirect()->route('category.index')->with('success', 'Category Updated Successsfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category Deleted Successfully');
    }
}
