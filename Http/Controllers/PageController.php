<?php

namespace Sanjok\Blog\Http\Controllers;

use Sanjok\Blog\Models\Post;
use Illuminate\Http\Request;

class PageController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = new Post;
        $search = $page->query();

        if ($title = $request->title) {
            $search->where('title', 'like', '%' . $title . '%');
        }
        $pages = $search->wherePostType('page')->orderBy('id', 'desc')->paginate(10);
        return view($this->getView('backend.page.index'), compact('pages', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = 'create';

        return view($this->getView('backend.page.form'), compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $upload_path = '';
        $validatedData = $request->validate([
            'title' => 'required'
        ]);

        $page = new Post();

        $slug = str_slug($request->title, '-');

        if ($request->hasFile('feature_image')) {
            $fileNameWithExt = $request->file('feature_image')->getClientOriginalName();
            // get file name
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('feature_image')->getClientOriginalExtension();

            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // upload
            $upload_path = $request->file('feature_image')->storeAs('pages', $fileNameToStore, 'public_upload');
            $upload_path = 'uploads' . '/' . $upload_path;
        }
        $page->title = $request->input('title');
        $page->post_type = 'page';
        $page->slug = $slug;
        $page->content = $request->input('content');
        $page->feature_image = $upload_path;
        $page->user_id = $request->user()->id;


        $page->save();
        return redirect()->route('page.index')->with('success', 'Page Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Post $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $page)
    {
        $form = 'edit';
        return view($this->getView('backend.page.form'), compact('page', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $page)
    {
        $validatedData = $request->validate([
            'title' => 'required'
        ]);
        if ($request->hasFile('feature_image')) {
            $fileNameWithExt = $request->file('feature_image')->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('feature_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $upload_path = $request->file('feature_image')->storeAs('pages', $fileNameToStore, 'public_upload');
            $upload_path = 'uploads' . '/' . $upload_path;
            $page->feature_image = $upload_path;
        } else {
            if ($request->input('feature_yes') == 'no') {
                $page->feature_image = '';
            }
        }
        $page->title = $request->input('title');
        $page->slug = $request->input('slug');
        $page->content = $request->input('content');

        $page->user_id = $request->user()->id;

        $page->update();
        return redirect()->route('page.index')->with('success', 'Page Updated Successsfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $page)
    {
        $page->delete();
        return redirect()->route('page.index')->with('success', 'Page Deleted Successfully');
    }
}
