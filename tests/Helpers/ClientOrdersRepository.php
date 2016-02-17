<?php

namespace NilPortugues\Tests\Foundation\Helpers;

use NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB\EloquentRepository;

class ClientOrdersRepository extends EloquentRepository
{
    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return ClientOrders::class;
    }
}
