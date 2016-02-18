<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/02/16
 * Time: 16:06.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB;

use Jenssegers\Mongodb\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Order;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort as SortInterface;

/**
 * Class EloquentSorter.
 */
class EloquentSorter
{
    /**
     * @param Builder|EloquentBuilder $query
     * @param SortInterface           $sort
     */
    public static function sort(Builder $query, SortInterface $sort)
    {
        /** @var Order $order */
        foreach ($sort->orders() as $propertyName => $order) {
            $query->getQuery()->orderBy($propertyName, $order->isAscending() ? 'ASC' : 'DESC');
        }
    }
}
