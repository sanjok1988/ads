<?php

namespace Sanjok\Blog\Http\Controllers;


use Sanjok\Blog\Http\Controllers\BaseController;


class DashboardController extends BaseController
{
    public function index()
    {


        return view($this->getView('backend.dashboard.index'));
    }
}
