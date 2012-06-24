<?php
class Chaplin_Cache_Backend_Exception_PhpRedisMissing extends hException
{
  const MESSAGE = 'PhpRedis was not included in the options array of the backend';
  
  public function __construct()
  {
    parent::__construct(sprintf(self::MESSAGE));
  }
}
