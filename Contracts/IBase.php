<?php
namespace Sanjok\Blog\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


Interface IBase {
    public function all();
    public function find(int $id): ?Model;
    public function create(array $data): Model;
    public function update(array $data): Model;
    public function delete(int $id): int;
}
