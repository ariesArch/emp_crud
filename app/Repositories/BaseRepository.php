<?php

namespace App\Repositories;

use App\Traits\Relationable;
use App\Traits\Sortable;
use App\Traits\Filterable;
use Illuminate\Support\Collection;
use DB;

abstract class BaseRepository
{
    protected $model;
    use Relationable, Sortable, Filterable;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }
    /**
     * Define repository modal
     *
     * @like[User::class]
     */
    abstract public function model();
    /**
     * @return Model|mixed
     *
     * @throws GeneralException
     */
    public function makeModel()
    {
        $model = app()->make($this->model());

        // if (!$model instanceof Model) {
        //     throw new GeneralException("Class {$this->model()} must be an instance of " . Model::class);
        // }

        return $this->model = $model;
    }
    /**
     * Get all list from model
     *
     * @return Collection
     */
    public function all(array $columns = ['*'])
    {
        // return $this->model::get();
        // \Log::info($this->sortBy);
        $data =  $this->model::filter(request()->all())
            ->with($this->relations)
            ->orderBy($this->sortBy, $this->sortOrder);
        if (request()->has('paginate')) {
            $data = $data->paginate(request()->get('paginate'));
        } else {
            $data = $data->paginate(10);
        }
        return $data;
    }

    /**
     * Create modal collection
     *
     * @param $data
     * @return bool|Object
     */
    public function create(array $data)
    {
        if (empty($data)) return false;
        $result = $this->model::create($data);
        // if ($result) return $result->refresh();
        // return false;
        if ($result) {
            $result->created_by = auth()->user()->id;
            $result->save();
        }
        return $result ? $result->refresh()->load($this->relations) : false;
    }

    /**
     * Find Row id form model list
     *
     * @param $primaryKey
     * @return Object
     */
    public function find(int $primaryKey)
    {
        return $this->model::find($primaryKey);
    }

    /**
     * Find Row id form model list
     *
     * @param $primaryKey
     * @return Object
     */
    public function findOrFail(int $primaryKey)
    {
        return $this->model::findOrFail($primaryKey);
    }

    /**
     * Update model
     *
     * @param integer $primaryKey
     * @param $data
     * @return bool|Object
     */
    public function update(int $id, array $data)
    {
        if (!$id || empty($data)) return false;
        if ($this->findOrFail($id)->update($data)) return $this->find($id)->refresh();
        return false;
    }

    // public function updateByModel($model, array $data)
    // {
    //     if ($model->isDirty()) {
    //         $model->save();
    //     }
    //     return $model->refresh();
    // }

    public function delete(Object $model)
    {
        // $deleted = $this->findOrFail($model->id)->delete();
        // if ($deleted) {
        //     $model->deleted_by = auth()->user()->id;
        //     $model->save();
        // }
        DB::beginTransaction();
        try {
            $deleted = $this->findOrFail($model->id)->delete();
            if ($deleted) {
                $model->deleted_by = auth()->user()->id;
                $model->save();
            }
            DB::commit();
            return true;
        } catch (Exception $exc) {
            DB::rollBack();
            Log::error($exc->getMessage());
            throw new InvalidArgumentException('Unable to delete');
        }
    }
}
