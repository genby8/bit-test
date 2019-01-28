<?php

namespace Application\Model;

use Application\Db\Db;

class BaseModel
{

    /**
     * @var string
     */
    protected $tableName = '';

    /**
     * @return \PDO
     */
    protected function getPDO(): \PDO
    {
        return Db::getConnect();
    }

    /**
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->tableName;
    }
}