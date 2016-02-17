<?php

namespace NilPortugues\Example\Service;

use DateTimeImmutable;
use NilPortugues\Example\Domain\User;
use NilPortugues\Example\Domain\UserId;

class UserAdapter
{
    /**
     * @param $model
     *
     * @return User
     */
    public function fromEloquent($model)
    {
        return new User(
            new UserId($model->id),
            $model->name,
            new DateTimeImmutable($model->created_at)
        );
    }
}
