<?php

namespace NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB;

use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\WriteRepository;

class EloquentWriteRepository extends BaseEloquentRepository implements WriteRepository
{
    /**
     * Returns the total amount of elements in the repository given the restrictions provided by the Filter object.
     *
     * @param Filter|null $filter
     *
     * @return int
     */
    public function count(Filter $filter = null)
    {
        $model = self::$instance;
        $query = $model->query();

        if ($filter) {
            EloquentFilter::filter($query, $filter);
        }

        return (int) $query->getQuery()->count();
    }

    /**
     * Returns whether an entity with the given id exists.
     *
     * @param $id
     *
     * @return bool
     */
    public function exists(Identity $id)
    {
        $model = self::$instance;
        $result = $model->query()->where($model->getKeyName(), '=', $id->id())->first();

        return null !== $result;
    }

    /**
     * Adds a new entity to the storage.
     *
     * @param Identity $value
     *
     * @return mixed
     */
    public function add(Identity $value)
    {
        $this->guard($value);
        $value->save();

        return $value;
    }

    /**
     * Adds a collections of entities to the storage.
     *
     * @param array $values
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function addAll(array $values)
    {
        $model = self::$instance;
        $ids = [];
        try {
            foreach ($values as $value) {
                $this->guard($value);
                $value->save();
                $ids[] = $value->getIdAttribute('_id');
            }
        } catch (\Exception $e) {
            $model->destroy($ids);
            throw $e;
        }
    }

    /**
     * Removes the entity with the given id.
     *
     * @param $id
     *
     * @return bool
     */
    public function remove(Identity $id)
    {
        $model = self::$instance;

        return (bool) $model->query()->find($id->id())->delete();
    }

    /**
     * Removes all elements in the repository given the restrictions provided by the Filter object.
     * If $filter is null, all the repository data will be deleted.
     *
     * @param Filter $filter
     *
     * @return bool
     */
    public function removeAll(Filter $filter = null)
    {
        $model = self::$instance;
        $query = $model->query();

        if ($filter) {
            EloquentFilter::filter($query, $filter);
        }

        return $query->delete();
    }

    /**
     * Repository data is added or removed as a whole block.
     * Must work or fail and rollback any persisted/erased data.
     *
     * @param callable $transaction
     *
     * @throws \Exception
     */
    public function transactional(callable $transaction)
    {
        try {
            $transaction();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
