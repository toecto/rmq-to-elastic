<?php

require __dir__."/vendor/autoload.php";
$config = require __dir__."/config.php";

$rmq = new \toecto\AMQPSimpleWrapper\AMQPSimpleWrapper(
    $config['rabbitmq']['user'],
    $config['rabbitmq']['password'],
    $config['rabbitmq']['vhost'],
    $config['rabbitmq']['host'],
    $config['rabbitmq']['port']
);

$consumer = new $config['consumer']['class']($rmq, $config);

$rmq->consume(
    $config['consumer']['queue'],
    array($consumer, 'consume'),
    $config['consumer']['limit'],
    $config['consumer']['prefetch']
);
