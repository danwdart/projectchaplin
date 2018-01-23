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

use Chaplin\Model\Field\FieldAbstract;
use JsonSerializable;
use OutOfBoundsException;
use Chaplin\Dao\DaoInterface;
use Iterator;
use Exception;

class Hash extends FieldAbstract implements JsonSerializable
{
    
    protected $arrFields = array();
    protected $collFields = array();
    protected $bIsNew = true;
    // Only used for API=based items
    protected $strURLPrefix;

    public function bIsNew()
    {
        return $this->bIsNew;
    }

    public function getId()
    {
        throw new OutOfBoundsException('getId needs to be overridden!');
    }

    public static function createFromData(DaoInterface $dao, array $arrArray)
    {
        $hash = new static();
        foreach ($arrArray as $strField => $mixedValue) {
            $hash->getFieldObject($strField)->setFromData($mixedValue);
        }
        $hash->bIsNew = false;
        
        return $hash;
    }

    public static function createFromAPIResponse(array $arrAPI, $strURLPrefix)
    {
        $hash = new static();
        $hash->strURLPrefix = $strURLPrefix;
        foreach ($arrAPI as $strField => $mixedValue) {
            $hash->getFieldObject($strField)->setFromData($mixedValue);
        }
        $hash->bIsNew = false;
        
        return $hash;
    }

    public static function createFromIterator(Iterator $itt, array $arrArray)
    {
        $hash = new static();
        foreach ($arrArray as $strField => $mixedValue) {
            $hash->getFieldObject($strField)->setFromData($mixedValue);
        }
        $hash->bIsNew = false;
        
        return $hash;
    }

    protected function __construct()
    {
        foreach ($this->arrFields as $strField => $arrClassArray) {
            $strClass = $arrClassArray['Class'];
            $strParam = isset($arrClassArray['Param'])?$arrClassArray['Param']:null;
            $this->collFields[$strField] = new $strClass($strParam);
        }
    }

    public function __get($strProperty)
    {
        return $this->getField($strProperty, null);
    }

    public function __set($strProperty, $strValue)
    {
        $this->setField($strProperty, $strValue);
    }
    
    public function getValue($mixedDefault)
    {
        return $this;
    }
    
    public function getFields(DaoInterface $dao)
    {
        return $this->collFields;
    }
    
    private function getFieldObject($strName)
    {
        if (!isset($this->collFields[$strName])) {
            throw new OutOfBoundsException('Invalid field: '.$strName);
        }
        return $this->collFields[$strName];
    }
    
    protected function getField($strName, $mixedDefault)
    {
        try {
            return $this->getFieldObject($strName)->getValue($mixedDefault);
        } catch (OutOfBoundsException $e) {
            return $mixedDefault;
        }
    }

    public function toArray()
    {
        return $this->getModelArray($this->collFields);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    private function getModelArray(array $arrFields)
    {
        $arrOut = array();
        foreach ($this->collFields as $strFieldName => $objField) {
            $strClass = get_class($objField);
            switch ($strClass) {
                case 'Chaplin\\Model\\Field\\Field':
                case 'Chaplin\\Model\\Field\\Readonly':
                case 'Chaplin\\Model\\Field\\FieldId':
                    $arrOut[$strFieldName] = $objField->getValue(null);
                    break;
                case 'Chaplin\\Model\\Field\\Collection':
                    foreach ($objField as $hash) {
                        foreach ($this->getModelArray(
                            $hash->collFields
                        ) as $strField => $mixedValue) {
                            if (!isset($arrOut[$strFieldName])) {
                                    $arrOut[$strFieldName] = [];
                            }
                            $arrOut[$strFieldName][$strField] = $mixedValue;
                        }
                    }
                    break;
                default:
                    throw new Exception('Not Implemented class '.$strClass);
            }
        }
        return $arrOut;
    }

    protected function setField($strName, $mixedValue)
    {
        $this->getFieldObject($strName)->setValue($mixedValue);
        $this->bIsDirty = true;
        return $this;
    }
}
