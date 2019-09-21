<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class BasicAuthActionDecorator
{
    /**
     * @var callable
     */
    protected $next;
    /**
     * @var array
     */
    protected $users;

    public function __construct(callable $next, array $users)
    {
        $this->next = $next;
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;
        if (!empty($username) && !empty($password)) {
            foreach ($this->users as $name => $pass) {
                if ($name === $username && $pass === $password) {
                    return ($this->next)($request->withAttribute('username', $username));
                }
            }
        }
        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
    }
}
