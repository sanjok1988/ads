<?php

namespace Sanjok\Blog\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function all();

    public function create(array $attributes);

    public function update(array $data, $id);

    public function delete(int $id);

    public function show(int $id);

    public function find(int $id);

    public function findOrFail(int $id);

    public function paginate(int $linit);
}
