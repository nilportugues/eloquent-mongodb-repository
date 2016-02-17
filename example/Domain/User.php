<?php

namespace NilPortugues\Example\Domain;

use DateTimeImmutable;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;

class User implements Identity
{
    /**
     * @var UserId
     */
    protected $userId;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var \DateTimeImmutable
     */
    protected $registrationDate;

    /**
     * User constructor.
     *
     * @param UserId            $id
     * @param string            $name
     * @param DateTimeImmutable $registrationDate
     */
    public function __construct(UserId $id, $name, DateTimeImmutable $registrationDate)
    {
        $this->userId = $id;
        $this->name = $name;
        $this->registrationDate = $registrationDate;
    }

    /**
     * Returns value for userId property.
     *
     * @return UserId
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * Returns value for name property.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns value for registrationDate property.
     *
     * @return DateTimeImmutable
     */
    public function registrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->userId()->id();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id();
    }
}
