<?php

namespace Sanjok\Blog\Http\Resources\Posts;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Sanjok\Blog\Http\Resources\Posts\PostResource;

class PostPaginationResource extends ResourceCollection
{
    private $pagination;

    public function __construct($resource)
    {
        $this->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage(),
            'nextPageUrl' => $resource->nextPageUrl(),
            'previousPageUrl' => $resource->previousPageUrl()
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [

            'data' => PostResource::collection($this->collection),
            'pagination' => $this->pagination
        ];
    }
}
