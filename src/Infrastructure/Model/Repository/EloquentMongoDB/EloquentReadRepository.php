<?php

namespace NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB;

use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Filter as DomainFilter;

class EloquentReadRepository extends BaseEloquentRepository implements ReadRepository
{
    /**
     * Retrieves an entity by its id.
     *
     * @param Identity    $id
     * @param Fields|null $fields
     *
     * @return array
     */
    public function find(Identity $id, Fields $fields = null)
    {
        $model = self::$instance;
        $columns = ($fields) ? $fields->get() : ['*'];

        return $model->query()->where($model->getKeyName(), '=', $id->id())->get($columns)->first();
    }

    /**
     * Returns all instances of the type.
     *
     * @param Filter|null $filter
     * @param Sort|null   $sort
     * @param Fields|null $fields
     *
     * @return array
     */
    public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)
    {
        $model = self::$instance;
        $query = $model->query();
        $columns = ($fields) ? $fields->get() : ['*'];

        if ($filter) {
            EloquentFilter::filter($query, $filter);
        }

        if ($sort) {
            EloquentSorter::sort($query, $sort);
        }

        return $query->get($columns)->toArray();
    }

    /**
     * Returns all instances of the type meeting $distinctFields values.
     *
     * @param Fields      $distinctFields
     * @param Filter|null $filter
     * @param Sort|null   $sort
     *
     * @return array
     */
    public function findByDistinct(Fields $distinctFields, Filter $filter = null, Sort $sort = null)
    {
        $model = self::$instance;
        $query = $model->query();

        $columns = (count($fields = $distinctFields->get()) > 0) ? $fields : ['*'];

        if ($filter) {
            EloquentFilter::filter($query, $filter);
        }

        if ($sort) {
            EloquentSorter::sort($query, $sort);
        }

        return $query->getQuery()->distinct()->get($columns);
    }

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
        $filter = new DomainFilter();
        $filter->must()->equal($model->getKeyName(), $id->id());
        return $this->count($filter) > 0;
    }
}
