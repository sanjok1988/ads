<?php

namespace Sanjok\Blog\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Sanjok\Blog\Models\Post;
use Sanjok\Blog\Contracts\IPost;
use Sanjok\Blog\Contracts\ICategory;
use Sanjok\Blog\Models\Category;
use Sanjok\Blog\Http\Requests\PostStoreRequest;
use Sanjok\Blog\Http\Controllers\BaseController;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;

class PostController extends BaseController
{
    use UploadTrait;

    public function __construct(IPost $post, ICategory $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $post = new Post;
        $search = $post->query();

        if ($title = $request->title) {
            $search->where('title', 'like', '%' . $title . '%');
        }

        if ($cat = $request->cat) {
            $search->whereHas('categories', function ($query) use ($cat) {
                $query->where('category_id', $cat);
            });
        }

        $posts = $search->orderBy('id', 'desc')->paginate(10);

        return view($this->getView('backend.post.index'), compact('posts', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = 'create';
        $categories = $this->category->all();
        return view($this->getView('backend.post.form'), compact('form', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $this->post->createPost($request->all());

        return redirect()->route('post.index')->with('success', 'Post Created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $form = 'edit';
        $categories = $this->category->all();
        $assign_cat = $post->categories->pluck('id')->toArray();
        return view($this->getView('backend.post.form'), compact('post', 'form', 'categories', 'assign_cat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostStoreRequest $request, Post $post)
    {
        $data = $request->only('title', 'excerpt', 'content', 'author', 'category', 'status', 'image');
        $this->post->updatePost($data, $post->id);
        return redirect()->route('post.index')->with('success', 'Post Updated Successsfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->detachLink($post);
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Post Deleted Successfully');
    }

    public function detachLink(Post $post)
    {
        return $post->categories()->detach();
    }

    public function detachPost(Post $id)
    {
        $this->detachLink($id);

        return redirect()->route('post.index')->with('success', 'Post Detached Successfully');
    }

    public function filterPost()
    {
        return $this->post->filterPost();
    }
}
