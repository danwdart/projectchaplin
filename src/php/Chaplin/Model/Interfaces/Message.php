<?php
namespace Chaplin\Model\Interfaces;

interface Message
{
    public function getExchangeName();

    public function getRoutingkey();

    public function process();
}
