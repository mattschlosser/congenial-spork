<?php

namespace Ruggmatt\Camp\Users;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

class UserRepository
{

    public function __construct(private ConnectionInterface $db)
    {
    }

    /** @return PromiseInterface<?array> **/
    public function findUser(int $id): PromiseInterface
    {
        error_Log("GETTING USER WITH id {$id}");
        return $this->db->query(
            'SELECT * FROM users WHERE id = ?',
            [$id]
        )->then(
            function (QueryResult $result) {
                if (count($result->resultRows) === 0) {
                    return null;
                }
                return $result->resultRows[0];
            },
            function (\Exception $e) {
                error_log($e);
                return null;
            }
        );
    }

    /** @return PromiseInterface<?int> */
    public function createUser(string $name): PromiseInterface
    {
        return $this->db->query(
            'INSERT INTO users (`name`) VALUES (?)',
            [$name]
        )->then(function (QueryResult $result) {
            if ($result->insertId === 0) {
                return null;
            }
            return $result->insertId;
        });
    }

    public function getAllUsers(): PromiseInterface
    {
        return $this->db->query(
            'SELECT id, `name` FROM users'
        )->then(function (QueryResult $result) {
            return $result->resultRows;
        });
    }
}
