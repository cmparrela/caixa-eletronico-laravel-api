<?php
namespace App\Services;

interface CrudServiceInterface
{
    public function getAll();

    public function createNew(array $attributes);

    public function findById(int $id);

    public function updateById(int $id, array $attributes);

    public function deleteById(int $id);
}
