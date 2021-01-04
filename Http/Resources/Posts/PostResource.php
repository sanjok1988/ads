<?php

namespace Sanjok\Blog\Http\Resources\Posts;

use Sanjok\Blog\Http\Resources\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Sanjok\Blog\Http\Resources\Category\CategoryResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //     return [
        //         'data'=>$this->collection,
        //         'meta' => ['post_count' => $this->collection->count()]
        // ];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image,
            'excerpt' => $this->excerpt,
            // 'category' => CategoryResource::collection($this->whenLoaded('categories'))->first(),
            'category' => CategoryResource::collection($this->category)->first(),
            'author' => $this->author,
            'published_at' => $this->published_at,
        ];
        // return parent::toArray($request);
    }
}
