<?php

use Framework\Http\Response;
use Framework\Http\RequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$request = RequestFactory::fromGlobals();
$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new Response('Hello ' . $name . '!'))
    ->withHeader('X-Developer', 'Evgeny');

header('HTTP/1.0 ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
foreach ($response->getHeaders() as $name => $value) {
    header($name. ':' . $value);
}
echo $response->getBody();

