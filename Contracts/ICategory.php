<?php

namespace Sanjok\Blog\Contracts;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Category;

interface ICategory
{
    public function findById(int $id);

    public function findBySlug(string $slug);

    public function createCategory(array $data);

    public function updateCategory(array $data, int $category_id);

    public function toggleStatus(int $id);
}
