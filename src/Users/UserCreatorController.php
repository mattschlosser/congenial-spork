<?php

namespace Ruggmatt\Camp\Users;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

use function React\Async\await;

class UserCreatorController
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return ResponseInterface **/
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $req = $request->getParsedBody();
        if (!$req || !$req['name']) {
            return Response::json(['msg' => "Name required", "req" => $req])->withStatus(Response::STATUS_BAD_REQUEST);
        }
        $id = await($this->repository->createUser($req['name']));
        if ($id === null) {
            return Response::json(['msg' => "Could not be created"])
                ->withStatus(Response::STATUS_INTERNAL_SERVER_ERROR);
        }
        return Response::json(['id' => $id])->withStatus(Response::STATUS_CREATED);
    }
}
