<?php

namespace Ruggmatt\Camp\Server;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class CORS
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $response = $next($request);
        $response = $response->withAddedHeader(
            "Access-Control-Allow-Origin",
            "*"
        );
        return $response;
    }
}
