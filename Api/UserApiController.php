<?php

namespace Sanjok\Blog\Api;

use Illuminate\Http\Response;
use Sanjok\Blog\Contracts\IUser;

use Illuminate\Support\Facades\Auth;
use Sanjok\Blog\Http\Resources\Users\UserResource;
use Sanjok\Blog\Http\Controllers\BaseController;
use Sanjok\Blog\Models\User;

class UserApiController extends BaseController {


    public function __construct(IUser $user)
    {
        $this->user = $user;

    }

    public function index() {

        return UserResource::collection($this->user->getAll());
        // return response()->json($this->post->getAllPost(), Response::HTTP_OK);
    }

    public function show(User $user) {

        return UserResource::collection($user);
        // return response()->json($this->post->getAllPost(), Response::HTTP_OK);
    }

    public function getAuthUser()
    {

        return $this->user->find(Auth::user()->id);
    }
}
