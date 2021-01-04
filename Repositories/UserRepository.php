<?php

namespace Sanjok\Blog\Repositories;

use Sanjok\Blog\Models\User;
use Sanjok\Blog\Contracts\IUser;
use Sanjok\Blog\Traits\UploaderTrait\UploadTrait;
use Sanjok\Blog\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements IUser
{
    use UploadTrait;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        return $this->model->all();
    }
}
