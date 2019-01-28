<?php

namespace Application\Model;

use Application\Logger\Logger;
use Application\Model\Entity\User;
use Application\Model\Entity\AccountTransaction as EntityAccountTransaction;
use Psr\Log\LoggerInterface;

class AccountTransaction extends BaseModel
{
    private $bcscale = 2;

    /**
     * @var string
     */
    protected $tableName = 'account_transaction';

    /**
     * @param User $user
     * @return string
     */
    public function getBalance(User $user): string
    {
        $sth = $this->getPDO()->prepare('SELECT SUM(amount) as sum  FROM ' . $this->getTableName() . ' WHERE uid = ? ORDER BY id ASC');
        $sth->execute([$user->id]);
        $result = $sth->fetchColumn();
        if (!is_null($result)) {
            return $result;
        }
        return '0';
    }

    /**
     * @param User $user
     * @param string $amount
     */
    public function addMoney(User $user, string $amount)
    {
        $transaction = new EntityAccountTransaction();
        $transaction->uid = $user->id;
        $transaction->amount = $amount;
        $this->save($transaction);
    }

    /**
     * @param User $user
     * @param string $amount
     * @return bool
     */
    public function takeMoney(User $user, string $amount): bool
    {
        $transaction = new EntityAccountTransaction();
        $transaction->uid = $user->id;
        $transaction->amount = -$amount;
        $conn = $this->getPDO();
        $this->getLoggerMoney()->info('Попытка вывести средства. Start.', ['amount' => $amount, 'uid' => $user->id]);
        try {
            $conn->beginTransaction();
            $balance = $this->getBalance($user);
            if (!$this->hasEnoughMoney($balance, $amount)) {
                return false;
            }
            $this->save($transaction);
            $conn->commit();
            $this->getLoggerMoney()->info('Попытка вывести средства. successful.',
                ['amount' => $amount, 'uid' => $user->id]);
            return true;
        } catch (\PDOException $e) {
            $conn->rollBack();
            $this->getLoggerMoney()->warning('Попытка вывести средства. unsuccessful.',
                ['amount' => $amount, 'uid' => $user->id]);
            return false;
        }
    }

    /**
     * @param $balance
     * @param $amount
     * @return bool
     */
    private function hasEnoughMoney(string $balance, string $amount)
    {
        if (bccomp($amount, $balance, $this->bcscale) === 1) {
            return false;
        }
        return true;
    }

    /**
     * @param EntityAccountTransaction $transaction
     */
    private function save(EntityAccountTransaction $transaction)
    {
        $data = $transaction->getData();
        unset($data['id']);
        unset($data['datetime']);
        $sql = 'INSERT INTO ' . $this->getTableName() . ' (uid, amount) VALUES (:uid, :amount)';
        $this->getPDO()->prepare($sql)->execute($data);
    }

    /**
     * @return LoggerInterface
     */
    public function getLoggerMoney(): LoggerInterface
    {
        return Logger::getLogger('money');
    }
}
