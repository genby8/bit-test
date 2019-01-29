<?php

namespace Application\Model\Entity;

use Application\Model\Entity;

/**
 * @property integer $id
 * @property integer $uid
 * @property string $balance
 */
class Account extends Entity
{
    protected $fields = [
        'id' => null,
        'uid' => null,
        'balance' => null
    ];
}
