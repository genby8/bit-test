<?php

namespace Application\Model;

use Application\Logger\Logger;
use Application\Model\Entity\User;
use Application\Model\Entity\Account as EntityAccount;
use Psr\Log\LoggerInterface;

class Account extends BaseModel
{
    private $bcscale = 2;

    /**
     * @var string
     */
    protected $tableName = 'account';

    /**
     * @param User $user
     * @param bool $forUpdate
     * @return string
     */
    public function getBalance(User $user, $forUpdate = false): EntityAccount
    {
        $q = 'SELECT * FROM ' . $this->tableName . ' WHERE uid = ? LIMIT 1';
        $q .= ($forUpdate) ? ' FOR UPDATE ' : '';
        $sth = $this->getPDO()->prepare($q);
        $sth->execute([$user->id]);
        $account = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($account)) {
            throw new \Error();
        }
        $entityAccount = $this->getEntity($account[0]);
        if (bccomp(0, $entityAccount->balance, $this->bcscale) === 1) {
            $this->getLoggerMoney()->alert('Отрицательный баланс',
                ['balance' => $entityAccount->balance, 'uid' => $user->id]);
        }
        return $entityAccount;
    }


    /**
     * @param array $data
     * @return EntityAccount
     */
    protected function getEntity(array $data): EntityAccount
    {
        return new EntityAccount($data);
    }

    /**
     * @param EntityAccount $account
     */
    public function save(EntityAccount $account)
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET balance=:balance WHERE id=:id';
        $this->getPDO()->prepare($sql)->execute(['balance' => $account->balance, 'id' => $account->id]);
    }

    /**
     * @return LoggerInterface
     */
    public function getLoggerMoney(): LoggerInterface
    {
        return Logger::getLogger('money');
    }
}