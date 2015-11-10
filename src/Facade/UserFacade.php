<?php

namespace Facade;

use Dao\UserDao;
use Entity\User;

class UserFacade
{
    /**
     * @var UserDao
     */
    private $userDao;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->userDao = new UserDao($pdo);
    }

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user)
    {
        return $this->userDao->save($user);
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->userDao->delete($user);
    }

    /**
     * @return User
     */
    public function find()
    {
        return $this->userDao->find();
    }

    /**
     * @param $id
     * @return \Entity\User
     */
    public function findOne($id)
    {
        return $this->userDao->findOne($id);
    }
}