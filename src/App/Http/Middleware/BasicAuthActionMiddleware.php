<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class BasicAuthActionMiddleware
{
    /**
     * @var array
     */
    protected $users;
    public const ATTRIBUTE = '_user';

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;
        if (!empty($username) && !empty($password)) {
            foreach ($this->users as $name => $pass) {
                if ($name === $username && $pass === $password) {
                    return $next($request->withAttribute(self::ATTRIBUTE, $username));
                }
            }
        }
        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
    }
}
