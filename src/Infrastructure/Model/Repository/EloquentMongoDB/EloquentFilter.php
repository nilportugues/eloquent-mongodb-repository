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
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\BaseFilter;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Filter as FilterInterface;

/**
 * Class EloquentFilter.
 */
class EloquentFilter
{
    const MUST_NOT = 'must_not';
    const MUST = 'must';
    const SHOULD = 'should';

    const NOT_CONTAINS_PATTERN = '/^((?!%s.))/i';

    public static function filter(Builder $query, FilterInterface $filter)
    {
        foreach ($filter->filters() as $condition => $filters) {
            $filters = self::removeEmptyFilters($filters);
            if (count($filters) > 0) {
                self::processConditions($query, $condition, $filters);
            }
        }

        return $query;
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    private static function removeEmptyFilters(array $filters)
    {
        $filters = array_filter($filters, function ($v) {
            return count($v) > 0;
        });

        return $filters;
    }

    /**
     * @param Builder $where
     * @param string  $condition
     * @param array   $filters
     */
    private static function processConditions(Builder $where, $condition, array &$filters)
    {
        switch ($condition) {
            case self::MUST:
                self::apply($where, $filters);
                break;

            case self::MUST_NOT:
                self::applyNot($where, $filters);
                break;

            case self::SHOULD:
                self::apply($where, $filters);
                break;
        }
    }

    /**
     * @param Builder $where
     * @param array   $filters
     */
    protected static function apply(Builder $where, array $filters)
    {
        foreach ($filters as $filterName => $valuePair) {
            foreach ($valuePair as $key => $value) {
                if (is_array($value) && count($value) > 0) {
                    if (count($value) > 1) {
                        switch ($filterName) {
                            case BaseFilter::RANGES:
                                $where->getQuery()->whereBetween($key, [$value[0], $value[1]]);
                                break;
                            case BaseFilter::NOT_RANGES:
                                $where->getQuery()->whereNotBetween($key, [$value[0], $value[1]]);
                                break;
                            case BaseFilter::GROUP:
                                $where->getQuery()->whereIn($key, $value);
                                break;
                            case BaseFilter::NOT_GROUP:
                                $where->getQuery()->whereNotIn($key, $value);
                                break;
                        }
                        break;
                    }
                    $value = array_shift($value);
                }

                switch ($filterName) {
                    case BaseFilter::GREATER_THAN_OR_EQUAL:
                        $where->where($key, '>=', $value);
                        break;
                    case BaseFilter::GREATER_THAN:
                        $where->where($key, '>', $value);
                        break;
                    case BaseFilter::LESS_THAN_OR_EQUAL:
                        $where->where($key, '<=', $value);
                        break;
                    case BaseFilter::LESS_THAN:
                        $where->where($key, '<', $value);
                        break;
                    case BaseFilter::CONTAINS:
                        $where->where($key, 'regex', '/'.$value.'/i');
                        break;
                    case BaseFilter::NOT_CONTAINS:
                        $where->where($key, 'regex', sprintf(self::NOT_CONTAINS_PATTERN, $value));
                        break;
                    case BaseFilter::STARTS_WITH:
                        $where->where($key, 'regex', '/^'.$value.'/i');
                        break;
                    case BaseFilter::ENDS_WITH:
                        $where->where($key, 'regex', '/'.$value.'$/i');
                        break;
                    case BaseFilter::EQUALS:
                        $where->where($key, '=', $value);
                        break;
                    case BaseFilter::NOT_EQUAL:
                        $where->where($key, '!=', $value);
                        break;
                }
            }
        }
    }

    /**
     * @param Builder $where
     * @param array   $filters
     */
    protected static function applyNot(Builder $where, array $filters)
    {
        foreach ($filters as $filterName => $valuePair) {
            foreach ($valuePair as $key => $value) {
                if (is_array($value) && count($value) > 0) {
                    if (count($value) > 1) {
                        switch ($filterName) {
                            case BaseFilter::RANGES:
                                $where->getQuery()->whereNotBetween($key, [$value[0], $value[1]]);

                                break;
                            case BaseFilter::NOT_RANGES:
                                $where->getQuery()->whereBetween($key, [$value[0], $value[1]]);
                                break;
                            case BaseFilter::NOT_GROUP:
                                $where->getQuery()->whereIn($key, $value);
                                break;
                            case BaseFilter::GROUP:
                                $where->getQuery()->whereNotIn($key, $value);
                                break;
                        }
                        break;
                    }
                    $value = array_shift($value);
                }

                switch ($filterName) {
                    case BaseFilter::GREATER_THAN_OR_EQUAL:
                        $where->where($key, '<', $value);
                        break;
                    case BaseFilter::GREATER_THAN:
                        $where->where($key, '<=', $value);
                        break;
                    case BaseFilter::LESS_THAN_OR_EQUAL:
                        $where->where($key, '>', $value);
                        break;
                    case BaseFilter::LESS_THAN:
                        $where->where($key, '>=', $value);
                        break;
                    case BaseFilter::CONTAINS:
                        $where->where($key, 'regex', sprintf(self::NOT_CONTAINS_PATTERN, $value));
                        break;
                    case BaseFilter::NOT_CONTAINS:
                        $where->where($key, 'regex', '/'.$value.'/i');
                        break;
                    case BaseFilter::STARTS_WITH:
                        $where->where($key, 'not regex', '/^'.$value.'/i');
                        break;
                    case BaseFilter::ENDS_WITH:
                        $where->where($key, 'not regex', '/'.$value.'$/i');
                        break;
                    case BaseFilter::EQUALS:
                        $where->where($key, '!=', $value);
                        break;
                    case BaseFilter::NOT_EQUAL:
                        $where->where($key, '=', $value);
                        break;
                }
            }
        }
    }
}
