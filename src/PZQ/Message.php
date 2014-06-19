<?php

namespace HIM\PZQ;

class Message
{
    private $id = null;
    private $peer = null;
    private $message = null;
    private $sent = null;
    private $ackTimeout = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getSentTime()
    {
        return $this->sent;
    }

    public function setSentTime($sent)
    {
        $this->sent = $sent;
    }

    public function getAckTimeout()
    {
        return $this->ackTimeout;
    }

    public function setAckTimeout($timeout)
    {
        $this->ackTimeout = $timeout;
    }

    public function getPeer()
    {
        return $this->peer;
    }

    public function setPeer($peer)
    {
        return $this->peer = $peer;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
