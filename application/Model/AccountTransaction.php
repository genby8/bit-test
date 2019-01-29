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
        $accountModel = new Account();
        $conn->beginTransaction();
        try {
            $account = $accountModel->getBalance($user, true);
            if (!$this->hasEnoughMoney($account->balance, $amount)) {
                throw new \Error();
            }
            $account->balance = bcsub($account->balance, $amount, $this->bcscale);
            $accountModel->save($account);
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
    private function hasEnoughMoney(string $balance, string $amount): bool
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
        $sql = 'INSERT INTO ' . $this->getTableName() . ' (uid, amount) VALUES (:uid, :amount)';
        $this->getPDO()->prepare($sql)->execute(['uid' => $transaction->uid, 'amount' => $transaction->amount]);
    }

    /**
     * @return LoggerInterface
     */
    public function getLoggerMoney(): LoggerInterface
    {
        return Logger::getLogger('money');
    }
}
