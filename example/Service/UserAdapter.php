<?php

namespace NilPortugues\Example\Service;

use DateTimeImmutable;
use NilPortugues\Example\Domain\User;
use NilPortugues\Example\Domain\UserId;
use NilPortugues\Example\Persistence\Eloquent\User as MongoDBUser;

class UserAdapter
{
    /**
     * @param User $user
     *
     * @return \NilPortugues\Example\Persistence\Eloquent\User
     */
    public function toEloquent(User $user)
    {
        $mongoDB = new MongoDBUser();
        $mongoDB->id = $user->id();
        $mongoDB->name = $user->name();
        $mongoDB->created_at = $user->registrationDate();

        return $mongoDB;
    }
    /**
     * @param array $model
     *
     * @return \NilPortugues\Example\Domain\User
     */
    public function fromEloquent(array $model)
    {
        return new User(new UserId($model['id']), $model['name'], new DateTimeImmutable($model['created_at']));
    }
}
