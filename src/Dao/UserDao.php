<?php

namespace Dao;

use Coduo\PHPHumanizer\String;
use Entity\User;

class UserDao
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $table = "user";

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Save function, will insert if is a new object or update an object
     * with the parameters passed
     *
     * @param User $user
     * @return User
     */
    public function save(User $user)
    {
        $this->pdo->beginTransaction();

        try {
            if (null == $user->getId()) {
                $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name, email, login) VALUES (:name, :email, :login)");
                $user->setId($this->pdo->lastInsertId());
            } else {
                $stmt = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, email = :email, login = :login WHERE id = :id");

                // pass the id to a variable
                $id = $user->getId();

                $stmt->bindParam(":id", $id);
            }

            // using this way, because passing directly
            // the bindParam(:name, $user->getName()) will throw the error message
            // 'Strict standards: Only variables should be passed by reference'
            // --
            // the better way is passing all the data to variables and than, pass
            // to the bindParam.
            $name = $user->getName();
            $email = $user->getEmail();
            $login = $user->getLogin();

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":login", $login);
            $stmt->execute();

            $this->pdo->commit();

            // return the current id or the last inserted id in db
            return $user;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();

            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * Delete an User
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user)
    {
        $this->pdo->beginTransaction();

        try {
            $id = $user->getId();

            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();

            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * Find all the records from user
     *
     * @return \Entity\User
     */
    public function find()
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, login FROM {$this->table}");
        $stmt->execute();

        // set the fetchMode, the return will be a multi array of User
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "Entity\User");

        return $stmt->fetchAll();
    }

    /**
     * Find one record in database
     *
     * @param int $id id from user
     * @return \Entity\User
     */
    public function findOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, login FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        // set the fetchMode, the return will be an User object
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "Entity\User");

        return $stmt->fetch();
    }
}