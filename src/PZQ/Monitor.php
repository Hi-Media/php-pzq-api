<?php

namespace HIM\PZQ;

class Monitor
{
    private $socket;

    public function __construct($dsn)
    {
        $this->socket = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_REQ);
        $this->socket->connect($dsn);
    }

    public function getStats()
    {
        $this->socket->send("MONITOR");

        $message = $this->socket->recv();
        $parts = explode("\n", $message);
        $parts = array_filter($parts);

        $data = array();

        foreach ($parts as $part) {
            $pieces = explode(': ', $part);
            $data[$pieces[0]] = $pieces[1];
        }

        return $data;
    }
}
