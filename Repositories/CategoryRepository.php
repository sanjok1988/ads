<?php

namespace Sanjok\Blog\Repositories;

use Sanjok\Blog\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Sanjok\Blog\Contracts\ICategory;
use Illuminate\Database\QueryException;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;
use Sanjok\Blog\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements ICategory
{
    use UploadTrait;

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function createCategory(array $data)
    {
        try {
            $data['slug'] = str_slug($data['name'], '-');

            return $this->model->create($data);
        } catch (QueryException $e) {
            Log::error($e->getMessage());
        }
    }

    public function updateCategory(array $data, int $category_id)
    {
        try {
            $category = $this->model->find($category_id);
            $category->update($data);
            return $category;
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function findById(int $id)
    {
        return $this->model->with('posts')->where('id', $id)->paginate(15);
    }

    public function findBySlug(string $slug)
    {

        return $this->model->with('posts')->where('slug', $slug)->paginate(5);
    }

    public function toggleStatus($id)
    {

        $category = $this->model->findOrFail($id);
        if ($category->status) {
            $category->status = false;
        } else {
            $category->status = true;
        }
        $category->update();
        return $category;
    }
}
