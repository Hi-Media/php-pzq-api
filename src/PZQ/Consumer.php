<?php

namespace HIM\PZQ;

class Consumer
{
    private $socket;
    private $timeout;
    private $filterExpired = true;

    public function __construct(\ZMQContext $context, $dsn = null)
    {
        $this->socket = new \ZMQSocket($context, \ZMQ::SOCKET_ROUTER);

        if ($dsn) {
            $this->connect($dsn);
        }
    }

    public function setFilterExpired($filterExpired)
    {
        $this->filterExpired = $filterExpired;
    }

    public function connect($dsn)
    {
        $this->socket->connect($dsn);
    }

    /**
     * Consume the received message (in block mode if $block is true)
     *
     * @param  boolean $block Block mode activated?
     * @return Messsage       Message received
     */
    public function consume($block = true)
    {
        $parts = $this->socket->recvMulti(($block ? 0 : \ZMQ::MODE_NOBLOCK));

        if ($parts === false) {
            return false;
        }

        $message = new Message();
        $message->setPeer($parts[0]);
        $message->setId($parts[1]);
        $message->setSentTime($parts[2]);
        $message->setAckTimeout($parts[3]);
        $message->setMessage(array_slice($parts, 5));

        if ($this->filterExpired && $this->isExpired($message)) {
            return $this->consume($block);
        }

        return $message;
    }

    /**
     * Determines if the message has expired.
     *
     * @param  Message $message Message to check
     * @return boolean
     */
    public function isExpired(Message $message)
    {
        $t    = microtime(true);
        $sent = (float) ($message->getSentTime() / 1000000);
        $diff = $t - $sent;

        return ($diff > ($message->getAckTimeout() / 1000000));
    }

    /**
     * Send ack for the message
     * If success is true, the message has been well processed.
     * It does not need to be processed again.
     *
     * @param  Message $message Message to ack
     * @param  boolean $success Message well processed or not
     */
    public function ack(Message $message, $success = true)
    {
        $this->socket->sendMulti(
            array(
                $message->getPeer(),
                $message->getId(),
                ($success ? "1" : "0")
            )
        );
    }

    /**
     * Get socket
     *
     * @return \ZMQSocket socket
     */
    public function getSocket()
    {
        return $this->socket;
    }
}
