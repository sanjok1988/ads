<?php

namespace Sanjok\Blog\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use Sanjok\Blog\Http\Resources\Posts\PostResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


class CategoryPostResource extends JsonResource
{


    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category' => $this->name,
            'posts' => PostResource::collection($this->posts)

        ];
    }
}
