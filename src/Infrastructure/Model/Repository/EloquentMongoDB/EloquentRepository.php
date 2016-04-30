<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/02/16
 * Time: 15:58.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB;

use Jenssegers\Mongodb\Eloquent\Model;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Page;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Pageable;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\PageRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\ReadRepository;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\WriteRepository;

/**
 * Class EloquentRepository.
 */
abstract class EloquentRepository implements ReadRepository, WriteRepository, PageRepository
{
    /** @var Model */
    protected static $instance;
    /** @var EloquentReadRepository */
    protected $readRepository;
    /** @var EloquentWriteRepository */
    protected $writeRepository;
    /** @var EloquentPageRepository */
    protected $pageRepository;

    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $eloquentModel = $this->getModelInstance();

        $this->readRepository = EloquentReadRepository::create($eloquentModel);
        $this->writeRepository = EloquentWriteRepository::create($eloquentModel);
        $this->pageRepository = EloquentPageRepository::create($eloquentModel);
    }

    /**
     * Retrieves an entity by its id.
     *
     * @param Identity|Model $id
     * @param Fields|null    $fields
     *
     * @return array
     */
    public function find(Identity $id, Fields $fields = null)
    {
        return $this->readRepository->find($id, $fields);
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
        return $this->readRepository->findBy($filter, $sort, $fields);
    }

    /**
     * Returns an EloquentMongoDB Model instance.
     *
     * @return Model
     */
    protected function getModelInstance()
    {
        if (null === self::$instance) {
            $modelInstance = $this->modelClassName();
            self::$instance = new $modelInstance();
        }

        return self::$instance;
    }

    /**
     * Must return the EloquentMongoDB Model Fully Qualified Class Name as a string.
     *
     * eg: return App\Model\User::class
     *
     * @return string
     */
    abstract protected function modelClassName();

    /**
     * Returns whether an entity with the given id exists.
     *
     * @param Identity|Model $id
     *
     * @return bool
     */
    public function exists(Identity $id)
    {
        return $this->writeRepository->exists($id);
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
        return $this->writeRepository->count($filter);
    }

    /**
     * Adds a new entity to the storage.
     *
     * @param Identity|Model $value
     *
     * @return mixed
     */
    public function add(Identity $value)
    {
        return $this->writeRepository->add($value);
    }

    /**
     * Adds a collections of entities to the storage.
     *
     * @param Model[] $values
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function addAll(array $values)
    {
        return $this->writeRepository->addAll($values);
    }

    /**
     * Removes the entity with the given id.
     *
     * @param Identity|Model $id
     *
     * @return bool
     */
    public function remove(Identity $id)
    {
        return $this->writeRepository->remove($id);
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
        return $this->writeRepository->removeAll($filter);
    }

    /**
     * Returns a Page of entities meeting the paging restriction provided in the Pageable object.
     *
     * @param Pageable $pageable
     *
     * @return Page
     */
    public function findAll(Pageable $pageable = null)
    {
        return $this->pageRepository->findAll($pageable);
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
        return $this->readRepository->findByDistinct($distinctFields, $filter, $sort);
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
        $this->writeRepository->transactional($transaction);
    }
}
