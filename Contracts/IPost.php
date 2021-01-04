<?php

namespace Sanjok\Blog\Contracts;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Post;

interface IPost
{
    public function getPopularPosts();

    public function createPost(array $data);

    public function updatePost(array $data, int $post_id);

    public function findById(int $id);

    public function findBySlug(string $slug);

    public function findByCategoryId(int $id);

    public function findByCategorySlug(string $slug);

    public function getTopStories(string $slug, int $limit = 10);
}
