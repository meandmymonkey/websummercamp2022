<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Lcobucci\Clock\SystemClock;
use Predis\ClientInterface;
use StellaMaris\Clock\ClockInterface;

return function(ContainerConfigurator $configurator) {
    $configurator->parameters()->set('app.transponder_url', env('TRANSPONDER_URL'));

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services
        ->load('App\\', '../src/*')
        ->exclude('../src/{DependencyInjection,Entity,Tests,Kernel.php}');

    $services->alias(ClientInterface::class, 'snc_redis.default');

    $services
        ->set(ClientBuilder::class)
        ->call('setHosts', [[env('ELASTICSEARCH_URL')]]);

    $services
        ->set(Client::class)
        ->factory([service(ClientBuilder::class), 'build']);

    $services
        ->set(SystemClock::class)
        ->factory([SystemClock::class, 'fromSystemTimezone']);

    $services->alias(ClockInterface::class, SystemClock::class);
};