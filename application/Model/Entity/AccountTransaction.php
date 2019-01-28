<?php

namespace Application\Model\Entity;

use Application\Model\Entity;

/**
 * @property integer $id
 * @property integer $uid
 * @property string $amount
 * @property string $datetime
 */
class AccountTransaction extends Entity
{
    protected $fields = [
        'id' => null,
        'uid' => null,
        'amount' => null,
        'datetime' => null
    ];
}

