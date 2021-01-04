<?php

namespace Sanjok\Blog\Repositories;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Post;
use Sanjok\Blog\Models\User;
use Sanjok\Blog\Contracts\IPost;
use Sanjok\Blog\Models\Category;
use Illuminate\Pipeline\Pipeline;
use Sanjok\Blog\Filters\Posts\Sort;
use Sanjok\Blog\Filters\Posts\Limit;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;
use Sanjok\Blog\Repositories\BaseRepository;
use Sanjok\Blog\Filters\Posts\Category as CategoryFilter;
use Sanjok\Blog\Services\NepaliDate\NepaliDate;

class PostRepository extends BaseRepository implements IPost
{
    use UploadTrait;

    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function all()
    {
        return $this->model->with(['categories', 'author'])->get();
    }


    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findByCategoryId(int $id)
    {
        return $this->model->whereHas('category', function ($query) use ($id) {
            $query->where('id', $id);
        })->paginate();
    }

    public function findByCategorySlug(string $slug)
    {
        return $this->model->whereHas('category', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->paginate();
    }

    public function getTopStories($slug, $limit = 10)
    {
        return $this->model->whereHas('category', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->limit($limit)->latest()->get(['id', 'title', 'slug']);
    }




    /**
     * Save Post
     *
     * @param array $data
     * @return void
     */
    public function createPost(array $data)
    {
        $post = new Post;
        return $this->storePost($post, $data);
    }

    private function storePost(Post $post, array $data)
    {

        $post->slug = str_slug($data['title'], '-');
        //upload file
        if (isset($data['image']) && is_file($data['image'])) {
            $post->image = $this->upload($data['image'], 'posts');
        }

        //loggedin user id
        $post->user_id = 1;
        $post->title = $data['title'];
        $post->author = 'author';
        $post->status = 'publish';
        $post->published_at = date('Y-m-d');
        $post->content = $data['content'];
        $post->excerpt = $data['excerpt'];
        $obj = new NepaliDate();
        $post->published_at_bs = $obj->getBsDate($post->published_at);

        $post->save();

        if (!isset($data['category'])) {
            $data['category'] = [1];
        }

        $post->categories()->sync(Category::find($data['category']));
        return $post;
    }

    public function updatePost(array $data, int $post_id)
    {
        $post = $this->model->find($post_id);


        return $this->storePost($post, $data);
    }

    /**
     * Method filterPost
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function filterPost()
    {
        $posts = app(Pipeline::class)
            ->send($this->model::query())
            ->through([
                CategoryFilter::class,
                Limit::class,
                Sort::class
            ])->thenReturn();

        $pg = request()->input('page');

        if ((int)$pg > 0) {
            return $posts->orderBy('id', 'desc')->paginate($pg);
        } else {
            return $posts->orderBy('id', 'desc')->get();
        }
    }

    public function getPopularPosts()
    {
        return $this->model->orderBy('views', 'desc')->limit(4)->get();
    }
}
