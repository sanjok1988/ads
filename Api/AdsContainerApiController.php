<?php

namespace Sanjok\Blog\Api;


use Sanjok\Blog\Models\AdContainer;
use Sanjok\Blog\Api\BaseApiController;

class AdsContainerApiController extends BaseApiController
{

    public function index()
    {

        return response(AdContainer::get());
    }
}
