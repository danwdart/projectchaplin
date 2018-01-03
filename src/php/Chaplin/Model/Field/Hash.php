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
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Chaplin_Model_Field_Hash
    extends Chaplin_Model_Field_Abstract
    implements JsonSerializable
{
    
    protected $_arrFields = array();
    protected $_collFields = array();
    protected $_bIsNew = true;
    // Only used for API=based items
    protected $_strURLPrefix;

    public function bIsNew()
    {
        return $this->_bIsNew;
    }

    public function getId()
    {
        throw new OutOfBoundsException('getId needs to be overridden!');
    }

    public static function createFromData(Chaplin_Dao_Interface $dao, Array $arrArray)
    {
        $hash = new static();
        foreach($arrArray as $strField => $mixedValue) {
            $hash->_getFieldObject($strField)->setFromData($mixedValue);
        }
        $hash->_bIsNew = false;
        
        return $hash;
    }

    public static function createFromAPIResponse(Array $arrAPI, $strURLPrefix)
    {
        $hash = new static();
        $hash->_strURLPrefix = $strURLPrefix;
        foreach($arrAPI as $strField => $mixedValue) {
            $hash->_getFieldObject($strField)->setFromData($mixedValue);
        }
        $hash->_bIsNew = false;
        
        return $hash;
    }

    public static function createFromIterator(Iterator $itt, Array $arrArray)
    {
        $hash = new static();
        foreach($arrArray as $strField => $mixedValue) {
            $hash->_getFieldObject($strField)->setFromData($mixedValue);
        }
        $hash->_bIsNew = false;
        
        return $hash;   
    }

    protected function __construct()
    {
        foreach($this->_arrFields as $strField => $arrClassArray) {
            $strClass = $arrClassArray['Class'];
            $strParam = isset($arrClassArray['Param'])?$arrClassArray['Param']:null;
            $this->_collFields[$strField] = new $strClass($strParam);
        }
    }

    public function __get($strProperty)
    {
        return $this->_getField($strProperty, null);
    }

    public function __set($strProperty, $strValue)
    {
        $this->_setField($strProperty, $strValue);
    }
    
    public function getValue($mixedDefault)
    {
        return $this;
    }
    
    public function getFields(Chaplin_Dao_Interface $dao)
    {
        return $this->_collFields;
    }
    
    private function _getFieldObject($strName)
    {
        if (!isset($this->_collFields[$strName])) {
            throw new OutOfBoundsException('Invalid field: '.$strName);
        }
        return $this->_collFields[$strName];
    }
    
    protected function _getField($strName, $mixedDefault)
    {
        try {
            return $this->_getFieldObject($strName)->getValue($mixedDefault);
        } catch(OutOfBoundsException $e) {
            return $mixedDefault;
        }
    }

    public function toArray()
    {
        return $this->_getModelArray($this->_collFields);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    private function _getModelArray(Array $arrFields)
    {
        $arrOut = array();
        foreach($this->_collFields as $strFieldName => $objField) {
            $strClass = get_class($objField);
            switch($strClass) {
            case 'Chaplin_Model_Field_Field':
            case 'Chaplin_Model_Field_Readonly':
            case 'Chaplin_Model_Field_FieldId':
                $arrOut[$strFieldName] = $objField->getValue(null);
                break;
            case 'Chaplin_Model_Field_Collection':
                foreach($objField as $hash) {
                    foreach(
                        $this->_getModelArray(
                            $hash->_collFields
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

    protected function _setField($strName, $mixedValue)
    {
        $this->_getFieldObject($strName)->setValue($mixedValue);
        $this->_bIsDirty = true;
        return $this;
    }
}
