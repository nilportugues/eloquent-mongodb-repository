<?php

namespace NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB;

use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;

class EloquentReadRepository implements ReadRepository
{
    /**
     * Retrieves an entity by its id.
     *
     * @param Identity $id
     * @param Fields|null $fields
     *
     * @return array
     */
    public function find(Identity $id, Fields $fields = null)
    {
        // TODO: Implement find() method.
    }

    /**
     * Returns all instances of the type.
     *
     * @param Filter|null $filter
     * @param Sort|null $sort
     * @param Fields|null $fields
     *
     * @return array
     */
    public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * Returns all instances of the type meeting $distinctFields values.
     *
     * @param Fields $distinctFields
     * @param Filter|null $filter
     * @param Sort|null $sort
     *
     * @return array
     */
    public function findByDistinct(Fields $distinctFields, Filter $filter = null, Sort $sort = null)
    {
        // TODO: Implement findByDistinct() method.
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
        // TODO: Implement count() method.
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
        // TODO: Implement exists() method.
    }
}