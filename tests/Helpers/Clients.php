<?php

namespace NilPortugues\Tests\Foundation\Helpers;

use Jenssegers\Mongodb\Eloquent\Model;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;
use NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB\IdentityTrait;

class Clients extends Model implements Identity
{
    use IdentityTrait;

    /**
     * @var string
     */
    protected $table = 'clients';

    /**
     * For instance, let's use our generated key instead of Mongo's.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderDates()
    {
        return $this->hasMany(ClientOrders::class);
    }
}
