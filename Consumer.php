<?php

namespace toecto\AMQPToElastic;

class Consumer {
    
    protected $rmq;
    protected $config;
    protected $elastic;

    public function __construct($rmq, $config) {
        $this->rmq = $rmq;
        $this->config = $config;

        $this->elastic = new \Elasticsearch\Client(array(
            'hosts' => array(
                $config['elastic']['host'].':'.$config['elastic']['port']
            )
        ));
    }

    public function consume($msg, $key, $envelop) {
        $msg = (array)$msg;
        if (empty($key)) $key = 'logs';
        $msg['@timestamp'] = date('c');
        $msg['@version'] = 1;

        $data = array(
            'body'  => $msg,
            'index' => 'logstash-'.date('Y.m.d'),
            'type'  => $key,
        );
        $this->elastic->index($data);
    }
}
