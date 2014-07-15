<?php

namespace HIM\PZQ;

/**
 * 
 * Copyright (c) 2014 Vincent Chabot <vchabot@hi-media.com>
 * Licensed under the GNU Lesser General Public License v3 (LGPL version 3).
 *
 * @copyright 2014 Vincent Chabot <vchabot@hi-media.com>
 * @license http://www.gnu.org/licenses/lgpl.html
 */
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
