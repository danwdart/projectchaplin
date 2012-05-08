<?php

/*
 Copyright (c) 2009 hamcrest.org
 */

require_once 'Hamcrest/Core/SubstringMatcher.php';

/**
 * Tests if the argument is a string that contains a substring.
 */
class Hamcrest_Core_StringStartsWith extends Hamcrest_Core_SubstringMatcher
{
  
  public function __construct($substring)
  {
    parent::__construct($substring);
  }
  
  public static function startsWith($substring)
  {
    return new self($substring);
  }
  
  // -- Protected Methods
  
  protected function evalSubstringOf($string)
  {
    return (substr($string, 0, strlen($this->_substring)) === $this->_substring);
  }
  
  protected function relationship()
  {
    return 'starting with';
  }
  
}
