<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/02/16
 * Time: 17:59.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Example\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use NilPortugues\Example\Service\UserAdapter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Fields;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Pageable;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort;
use NilPortugues\Foundation\Domain\Model\Repository\Page;
use NilPortugues\Foundation\Infrastructure\Model\Repository\Eloquent\EloquentRepository;

/**
 * Class UserRepository.
 */
class UserRepository extends EloquentRepository
{
    /**
     * @var UserAdapter
     */
    protected $userAdapter;

    /**
     * @param $userAdapter
     */
    public function __construct($userAdapter)
    {
        $this->userAdapter = $userAdapter;
    }

    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return User::class;
    }

    /**
     * {@inheritdoc}
     */
    public function find(Identity $id, Fields $fields = null)
    {
        /** @var Model $eloquentModel */
        $eloquentModel = parent::find($id, $fields);

        return $this->userAdapter->fromEloquent($eloquentModel);
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(Filter $filter = null, Sort $sort = null, Fields $fields = null)
    {
        $eloquentModelArray = parent::findBy($filter, $sort, $fields);

        return $this->fromEloquentArray($eloquentModelArray);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(Pageable $pageable = null)
    {
        $page = parent::findAll($pageable);

        return new Page(
            $this->fromEloquentArray($page->content()),
            $page->totalElements(),
            $page->pageNumber(),
            $page->totalPages(),
            $page->sortings(),
            $page->filters(),
            $page->fields()
        );
    }

    /**
     * @param array $eloquentModelArray
     *
     * @return array
     */
    protected function fromEloquentArray(array $eloquentModelArray)
    {
        $results = [];
        foreach ($eloquentModelArray as $eloquentModel) {
            //This is required to handle findAll returning array, not objects.
            $eloquentModel = (object) $eloquentModel;

            $results[] = $this->userAdapter->fromEloquent($eloquentModel);
        }

        return $results;
    }
}
