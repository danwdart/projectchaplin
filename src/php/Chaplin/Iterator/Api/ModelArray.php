<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/

namespace Chaplin\Iterator\Api;

use Chaplin\Iterator\IteratorInterface;
use BadMethodCallException;

class ModelArray implements IteratorInterface
{
    private $daoInterface;
    private $bEmpty        = false;

    private $intOffset     = 0;
    private $intStartRow   = 0;
    private $intReturnRows;
    private $strClass;
    private $arrRows;
    private $strURLPrefix;

    public function __construct($strClass, $strURLPrefix, array $arrRows)
    {
        $this->arrRows = $arrRows;
        $this->strClass = $strClass;
        $this->strURLPrefix = $strURLPrefix;
    }
    public function isEmpty()
    {
        if (0 == count($this->arrRows)) {
            $this->bEmpty = true;
        }
        return $this->bEmpty;
    }
    public function count()
    {
        return count($this->arrRows);
    }
    public function current()
    {
        $arrCurrentItem = $this->arrRows[$this->intOffset];
        $strClass = $this->strClass;
        return $strClass::createFromAPIResponse($arrCurrentItem, $this->strURLPrefix);
    }
    public function key()
    {
        return $this->intOffset;
    }
    public function next()
    {
        $this->intOffset++;
    }
    public function rewind()
    {
        $this->intOffset = 0;
    }
    public function valid()
    {
        return isset($this->arrRows[$this->intOffset]);
    }
    //Implements ArrayAccess
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException(__METHOD__);
    }
    public function offsetExists($offset)
    {
        throw new BadMethodCallException(__METHOD__);
    }
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException(__METHOD__);
    }
    public function offsetGet($offset)
    {
        throw new BadMethodCallException(__METHOD__);
    }

    /**  Limits the number of rows to be returned in the cursor
     *  @param     $intNoRows  = number of rows to return
     *  @return    $this (this is a fluent interface)
     **/
    public function limit($intNoRows)
    {
        throw new BadMethodCallException(__METHOD__);
    }
    /**
     *  Skips the first  $intNoRows
     *
     *  @param  $intNoRows  = number of rows to skip
     *  @return $this (this is a fluent interface)
     **/
    public function skip($intNoRows)
    {
        throw new BadMethodCallException(__METHOD__);
    }
    /**
     *  Sorts the cursor
     *
     *  @param  $arrColumns     Associative array of Key => value (1 = ASC, -1 = DESC)
     *  @return $this (this is a fluent interface)
     **/
    public function sort(array $arrColumns = array())
    {
        throw new BadMethodCallException(__METHOD__);
    }

    //Implements SeekableIterator
    public function seek($strPosition)
    {
        throw new BadMethodCallException(__METHOD__);
    }

    public function toArray()
    {
        $arrOut = [];
        foreach ($this as $item) {
            $arrOut[] = $item->toArray();
        }
        return $arrOut;
    }
}
