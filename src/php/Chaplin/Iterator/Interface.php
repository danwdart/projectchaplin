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
interface Chaplin_Iterator_Interface extends Countable, ArrayAccess, SeekableIterator
{
    const SORT_ASC = 1;
    const SORT_DESC = -1;
    const SORT_NUM_ASC = 2;
    const SORT_NUM_DESC = -2;
    /**
     *  Returns whether the Iterator is empty (ie no data passed in)
     *
     *  @return: true | false;
    **/
    public function isEmpty();
    /**
     *  Limits the number of rows to be returned in the cursor
     *
     *  @param:  $intNoRows  = number of rows to return
     *  @return: $this (this is a fluent interface)
    **/
    public function limit($intNoRows);
    /**
     *  Skips the first  $intNoRows
     *
     *  @param:  $intNoRows  = number of rows to skip
     *  @return: $this (this is a fluent interface)
    **/
    public function skip($intNoRows);
    /**
     *  Sorts the cursor
     *
     *  @param:  $arrColumns     Associative array of Key => value
     *  @return: $this (this is a fluent interface)
    **/
    public function sort(array $arrColumns = array());
}
