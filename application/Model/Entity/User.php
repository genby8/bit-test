<?php

namespace Application\Model\Entity;

use Application\Model\Entity;

/**
 * @property int $id
 * @property string $email
 * @property string $salt
 * @property string $password
 */
class User extends Entity
{
    protected $fields = [
        'id' => null,
        'email' => null,
        'salt' => null,
        'password' => null
    ];
}