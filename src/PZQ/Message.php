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
