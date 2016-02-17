<?php

namespace NilPortugues\Tests\Foundation\Helpers;

use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;

class ClientId implements Identity
{
    protected $id;

    /**
     * ClientId constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
