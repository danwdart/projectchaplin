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

namespace Chaplin\Model\Field;

use Iterator;
use Countable;
use Chaplin\Model\Field\Hash;
use OutOfBoundsException;
use Exception;

class Collection implements Iterator, Countable
{
    private $intIndex = 0;
    private $strHashType = null;
    private $bIsDirty = false;
    private $collHashes = array();

    public function __construct($strHashType)
    {
        $this->strHashType = $strHashType;
    }

    public function bIsDirty()
    {
        return $this->bIsDirty;
    }

    public function addHash(Hash $hash)
    {
        $this->collHashes[] = $hash;
        $this->bIsDirty = true;
    }

    public function valid()
    {
        return isset($this->collHashes[$this->intIndex]);
    }

    public function current()
    {
        return $this->collHashes[$this->intIndex];
    }

    public function next()
    {
        $this->intIndex++;
    }

    public function rewind()
    {
        $this->intIndex = 0;
    }

    public function count()
    {
        return count($this->collHashes);
    }

    public function key()
    {
        return $this->intIndex;
    }

    public function seek($strId)
    {
        foreach ($this->collHashes as $hash) {
            if ($hash->getId() == $strId) {
                return $hash;
            }
        }
        throw new OutOfBoundsException($strId);
    }

    public function setFromData($mixedValue)
    {
        if (!is_array($mixedValue)) {
            throw new Exception('Not Array');
        }

        $strHashType = $this->strHashType;

        foreach ($mixedValue as $strId => $arrData) {
            $strHashType::createFromIterator($this, $arrData);
            $this->collHashes[] = $strHashType::createFromIterator($this, $arrData);
        }
        return $this;
    }

    public function setValue($mixedValue)
    {
        throw new Exception("Can't do this!");
    }

    public function getValue($mixedDefault)
    {
        return $this;
    }
}
