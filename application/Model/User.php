<?php

namespace Application\Model;

use Application\Db\Db;
use Application\Logger\Logger;
use Application\Model\Entity\User as EntityUser;
use Application\Session\SessionWrapper;

class User
{

    /**
     * @var string
     */
    private $userTableName = 'users';
    private $loggerAuthName = 'auth';

    /**
     * Получить теукщего пользователя
     * @return EntityUser|null
     */
    public function getCurrentUser(): ?EntityUser
    {
        $email = SessionWrapper::getData('email');
        if (!is_null($email)) {
            return $this->getUser($email);
        }
        return null;
    }

    /**
     * Авторизован ли пользователь
     * @return bool
     */
    public function isAuth(): bool
    {
        $currentUser = $this->getCurrentUser();
        if (!is_null($currentUser)) {
            return true;
        }
        return false;
    }

    /**
     * Залогиниться
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        $user = $this->getUser($email);
        if (!is_null($user) && $user->password === $this->getHashPassword($password, $user->salt)) {
            SessionWrapper::setData('email', $user->email);
            $this->getLoggerAuth()->info('Попытка залогиниться. Успех.', ['email' => $email]);
            return true;
        }
        $this->getLoggerAuth()->info('Попытка залогиниться. Неудача.', ['email' => $email]);
        return false;
    }

    /**
     * Разлогиниться
     */
    public function logout()
    {
        SessionWrapper::unsetData('email');
        $this->getLoggerAuth()->info('Попытка разлогиниться. Успех.');
    }

    /**
     * @param $email
     * @return EntityUser|null
     */
    public function getUser($email): ?EntityUser
    {
        $conn = Db::getConnect();
        $sth = $conn->prepare('SELECT * FROM ' . $this->getTableName() . ' WHERE email = ? LIMIT 1');
        $sth->execute([$email]);
        $users = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($users)) {
            return $this->getEntity($users[0]);
        }
        return null;
    }

    /**
     * @param array $data
     * @return EntityUser
     */
    protected function getEntity(array $data): EntityUser
    {
        return new EntityUser($data);
    }

    /**
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function getHashPassword(string $password, string $salt): string
    {
        return md5($password . $salt);
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->userTableName;
    }

    public function getLoggerAuth()
    {
        return Logger::getLogger($this->loggerAuthName);
    }

}