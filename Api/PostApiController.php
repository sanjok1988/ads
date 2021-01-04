<?php

namespace Sanjok\Blog\Api;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Post;
use Illuminate\Http\Response;
use Sanjok\Blog\Contracts\IPost;

use Sanjok\Blog\Api\BaseApiController;

use Sanjok\Blog\Http\Resources\Posts\PostResource;
use Sanjok\Blog\Http\Resources\Posts\PostDetailResource;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PostApiController
 */

class PostApiController extends BaseApiController
{
    /**
     * Method __construct
     *
     * @param IPost $post [explicite description]
     *
     * @return void
     */
    public function __construct(IPost $post)
    {
        $this->post = $post;
    }

    /**
     * Method getAll
     *
     * @return void
     */
    public function index()
    {
        return PostResource::collection($this->post->filterPost())
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(int $post_id)
    {

        return new PostDetailResource($this->post->find($post_id));
    }

    public function getPopularPosts()
    {
        return PostResource::collection($this->post->getPopularPosts());
    }

    public function filterPost()
    {
        return PostDetailResource::collection($this->post->filterPost())
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'excerpt', 'content', 'author', 'category', 'status', 'image');
        $update = $this->post->createPost($data);
        if ($update) {
            return new PostDetailResource($update);
        } else {
            return response('failed', 200);
        }
    }

    public function update(Request $request, $post_id)
    {
        $data = $request->only('title', 'excerpt', 'content', 'author', 'category', 'status', 'image');
    
        if ($update = $this->post->updatePost($data, $post_id)) {
            return new PostDetailResource($update);
        } else {
            return response('failed', 200);
        }
    }

    public function destroy($id)
    {
        if ($post = $this->post->find($id)) {
            if ($post->delete()) {
                return response('success', 200);
            }
        }
    }

    public function toggleStatus($id)
    {
        if ($post = $this->post->find($id)) {
            if ($post) {
                if ($post->status == 1) {
                    $post->status = 0;
                } else {
                    $post->status = 1;
                }

                if ($post->update()) {
                    return response(['data' => $post->status, 'status' => 'success'], 200);
                } else {
                    return response(['status' => 'failed']);
                }
            }
        }
    }
}
