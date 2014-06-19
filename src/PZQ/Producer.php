<?php

namespace HIM\PZQ;

class Producer
{
    private $socket;
    private $poll;
    private $ignoreAck = false;

    public function __construct(\ZMQContext $context, $dsn = null)
    {
        $this->socket = new \ZMQSocket($context, \ZMQ::SOCKET_DEALER);

        if ($dsn) {
            $this->connect($dsn);
        }

        $this->poll = new \ZMQPoll();
        $this->poll->add($this->socket, \ZMQ::POLL_IN);
    }

    /**
     * Connect the socket to $dsn if not already connected to this $dsn
     *
     * @param  string $dsn Dsn to reach
     */
    public function connect($dsn)
    {
        $endPoints = $this->socket->getEndPoints();

        // if not already connected
        if (!in_array($dsn, $endPoints['connect'])) {
            $this->socket->connect($dsn);
        }
    }

    /**
     * Send a message
     *
     * @param  Message $message Message to send
     * @param  integer $timeout Timeout
     * @return boolean
     */
    public function produce(Message $message, $timeout = 5000)
    {
        $out = array($message->getId(), "");
        $m = $message->getMessage();

        if (is_array($m)) {
            $out = array_merge($out, $m);
        } else {
            array_push($out, $m);
        }

        $this->socket->sendMulti($out);

        if (!$this->ignoreAck) {
            $r = $w = array();
            $this->poll->poll($r, $w, $timeout);

            if (empty($r)) {
                throw new ClientException('ACK timeout');
            }

            $response = $this->socket->recvMulti();

            if ($response[0] != $message->getId()) {
                throw new ClientException('Got ACK for wrong message');
            }

            if ($response[1] != '1') {
                throw new ClientException("Remote peer failed to handle message ({$response[3]})");
            }
        }

        return true;
    }

    public function setIgnoreAck($ignoreAck)
    {
        $this->ignoreAck = $ignoreAck;
    }
}
