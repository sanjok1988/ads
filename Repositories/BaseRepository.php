<?php

namespace Sanjok\Blog\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Sanjok\Blog\Contracts\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;

class BaseRepository implements EloquentRepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function paginate($limit)
    {
        return $this->model->paginate($limit);
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->get();
    }


    // create a new record in the database
    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (QueryException $e) {
            dd($e->getMessage());
        }
    }

    // update record in the database
    public function update(array $data, $id)
    {
        $record = $this->model->find($id);
        return $record->update($data);
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model - findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }
}
