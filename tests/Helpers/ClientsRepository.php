<?php

namespace NilPortugues\Tests\Foundation\Helpers;

use NilPortugues\Foundation\Infrastructure\Model\Repository\EloquentMongoDB\EloquentRepository;

class ClientsRepository extends EloquentRepository
{
    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return Clients::class;
    }
}
