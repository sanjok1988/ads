<?php

namespace Sanjok\Blog\Api;


use Illuminate\Http\Response;
use Sanjok\Blog\Contracts\IPost;

use Sanjok\Blog\Contracts\ICategory;
use Sanjok\Blog\Http\Controllers\BaseController;
use Sanjok\Blog\Http\Resources\Posts\PostResource;
use Sanjok\Blog\Http\Resources\Posts\PostDetailResource;

use Sanjok\Blog\Http\Resources\Posts\PostPaginationResource;


class FrontendApiController extends BaseController
{

    public function __construct(IPost $post, ICategory $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    public function index()
    {
        return PostResource::collection($this->post->filterPost())
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show($post)
    {
        if (is_numeric($post)) {
            return new PostDetailResource($this->post->findById($post));
        }
        if (is_string($post)) {
            return new PostDetailResource($this->post->findBySlug($post));
        }
    }

    public function getPostByCategory($post)
    {
        if (is_numeric($post)) {
            return new PostPaginationResource($this->post->findByCategoryId($post));
        }
        if (is_string($post)) {
            //return $this->post->findByCategorySlug($post);
            return new PostPaginationResource($this->post->findByCategorySlug($post));
        }
    }

    public function getPopularPosts()
    {
        return PostResource::collection($this->post->getPopularPosts());
    }

    public function getTopStories()
    {
        return $this->post->getTopStories('main', 10);
    }
}
