<?php

use App\Kernel;
use Nyholm\Psr7;
use Spiral\RoadRunner;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\Debug\Debug;

include "vendor/autoload.php";

$env = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
$debug = false;

$kernel = new Kernel($env, $debug);
$worker = RoadRunner\Worker::create();
$psr17Factory = new Psr7\Factory\Psr17Factory();
$httpFoundationFactory = new HttpFoundationFactory();
$psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);;

$psr7worker = new RoadRunner\Http\PSR7Worker($worker, $psr17Factory, $psr17Factory, $psr17Factory);

while ($req = $psr7worker->waitRequest()) {
    try {
        $request = $httpFoundationFactory->createRequest($req);
        $response = $kernel->handle($request);
        $psr7worker->respond($psrHttpFactory->createResponse($response));
        $kernel->terminate($request, $response);
        $kernel->reboot(null);
    } catch (\Throwable $e) {
        $psr7worker->getWorker()->error((string)$e);
    }
}
