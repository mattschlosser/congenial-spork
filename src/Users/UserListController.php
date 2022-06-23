<?php

namespace Ruggmatt\Camp\Users;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

use function React\Async\await;

class UserListController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return ResponseInterface **/
    public function __invoke(): ResponseInterface
    {
        $users = await($this->repository->getAllUsers());
        return Response::json($users)->withStatus(Response::STATUS_OK);
    }
}
