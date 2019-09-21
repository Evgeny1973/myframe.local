<?php

use App\Http\Action\AboutAction;
use App\Http\Middleware\BasicAuthActionMiddleware;
use App\Http\Middleware\NotFoundHandler;
use App\Http\Action\Blog\IndexAction;
use App\Http\Action\Blog\ShowAction;
use App\Http\Action\CabinetAction;
use App\Http\Action\HelloAction;
use App\Http\Middleware\ProfilerMiddleware;
use Aura\Router\RouterContainer;
use Framework\Http\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Framework\Http\Pipeline\Pipeline;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

# Init routes
$aura = new RouterContainer;
$routes = $aura->getMap();

$params = [
    'users' => ['admin' => 'password'],
];

$routes->get('home', '/', HelloAction::class);
$routes->get('about', '/about', AboutAction::class);
$routes->get('blog', '/blog', IndexAction::class);
$routes->get('blog_show', '/blog/{id}', ShowAction::class)->tokens(['id' => '\d+']);
$routes->get('cabinet', '/cabinet', [
    ProfilerMiddleware::class,
    new BasicAuthActionMiddleware($params['users']),
    CabinetAction::class,
]);

$router = new AuraRouterAdapter($aura);
$resolver = new MiddlewareResolver;

# Run
$request = ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $handlers = $result->getHandler();
    $pipeline = new Pipeline;
    foreach (is_array($handlers) ? $handlers : [$handlers] as $handler) {
        $pipeline->pipe($resolver->resolve($handler));
    }
    $response = $pipeline($request, new NotFoundHandler);
} catch (RequestNotMatchedException $e) {
    $handler = new NotFoundHandler;
    $response = $handler($request);
}

# Postprocessing
$response = $response->withHeader('X-Developer', 'Evgeny');

# Send
$emitter = new SapiEmitter;
$emitter->emit($response);
