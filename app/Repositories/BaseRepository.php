<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Repositories\Product\Criteria\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected array $criteria = [];

    public function __construct(
        protected Model $model

    ) {}

    public function create(array $data): Model
    {
        return  $this->model->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return  $this->model->update($data);
    }

    public function delete(Model $model): bool
    {
        return $this->model->delete();
    }

    public function find(string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(string $id): Model
    {
        return $this->model->findOrFail($id);
    }


    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->applyCriteria()->paginate($perPage);
    }

    public function pushCriteria(
        CriteriaInterface $criteria
    ): static {
        $this->criteria[] = $criteria;

        return $this;
    }

    public function applyCriteria(): Builder
    {
        $query = $this->model->newQuery();

        foreach ($this->criteria as $criteria) {
            $query = $criteria->apply($query);
        }

        return $query;
    }

    protected function resetCriteria(): void
    {
        $this->criteria = [];
    }
}
