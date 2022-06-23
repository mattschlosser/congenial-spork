<?php

namespace Ruggmatt\Camp\Users;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

use function React\Async\await;

class UserLookupController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return ResponseInterface **/
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        error_log("ID is {$id}");
        $user = await($this->repository->findUser($id));
        if ($user === null) {
            return Response::plaintext("User not found\n")->withStatus(Response::STATUS_NOT_FOUND);
        }

        return Response::json(
            $user
        );
    }
}
