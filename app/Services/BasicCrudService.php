<?php
namespace App\Services;

abstract class BasicCrudService
{
    abstract public function validateStore(array $attributes);

    public function getAll()
    {
        return $this->model->all();
    }

    public function createNew(array $attributes)
    {
        $this->validateStore($attributes);
        return $this->model->create($attributes)->fresh();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function updateById(int $id, array $attributes)
    {
        $this->validateStore($attributes);
        $object = $this->model->find($id);
        $object->fill($attributes);
        $object->save();
        return $object;
    }

    public function deleteById(int $id)
    {
        $object = $this->model->find($id);
        if ($object) {
            return $object->delete();
        }
        return false;
    }

}
