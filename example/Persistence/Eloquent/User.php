<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/02/16
 * Time: 17:56.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Example\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Infrastructure\Model\Repository\Eloquent\IdentityTrait;

/**
 * Class User.
 */
class User extends Model implements Identity
{
    use IdentityTrait;

    /**
     * @var string
     */
    protected $table = 'users';
}
