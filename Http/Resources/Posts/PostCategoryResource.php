<?php

namespace Sanjok\Blog\Http\Resources\Posts;

use Illuminate\Http\Resources\Json\JsonResource;
use Sanjok\Blog\Http\Resources\Category\CategoryResource;

class PostCategoryResource extends JsonResource //ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'author' => $this->author,
            'status' => $this->status,
            'published_at' => $this->created_at,
            'category' => CategoryResource::collection($this->whenLoaded('categories'))->first()
        ];
    }
}
