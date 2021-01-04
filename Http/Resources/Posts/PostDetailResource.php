<?php

namespace Sanjok\Blog\Http\Resources\Posts;

use Illuminate\Http\Resources\Json\JsonResource;
use Sanjok\Blog\Http\Resources\Category\CategoryResource;

class PostDetailResource extends JsonResource //ResourceCollection
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
            'content' => $this->content,
            'image' => $this->image,
            //'category' => CategoryResource::collection($this->whenLoaded('categories'))->first(),
            'category' => CategoryResource::collection($this->category)->first(),
            'author' => $this->author,
            'views' => $this->views,
            'status' => $this->status
        ];
    }
}
