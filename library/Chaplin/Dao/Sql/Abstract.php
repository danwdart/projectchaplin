<?php
abstract class Chaplin_Dao_Sql_Abstract implements Chaplin_Dao_Interface
{
    private static $_zendDb;

    public static function setAdapter(Zend_Db_Adapter_Abstract $zendDb)
    {
        self::$_zendDb = $zendDb;
    }

    protected function _getAdapter()
    {
        if(!self::$_zendDb instanceof Zend_Db_Adapter_Abstract) {
            throw new Chaplin_Dao_Sql_Exception_NoAdapter();
        }
        return self::$_zendDb;
    }

    abstract protected function _getTable();

    protected function _save(Chaplin_Model_Abstract_Base $modelBase)
    {
        $strTable = $this->_getTable();

        $arrUpdate = array();
        $this->_saveCollection($modelBase->preUpdateFromDao($this), $arrUpdate);
        $this->_getAdapter()->insert($strTable, $arrUpdate);
    }

    private function _saveCollection(Chaplin_Model_Field_Collection_Abstract $collection, &$arrUpdate)
    {
        if(!$collection->isEmpty()) {
            foreach($collection as $strFieldName => $objField) {
                if($objField->isDirty()) {
                    $location = $strFieldName;
                    $strClass = get_class($objField);
                    switch($strClass) {
                        case 'Chaplin_Model_Field_Field':
                        case 'Chaplin_Model_Field_FieldId':
                            $arrUpdate[$location] = $objField->getValue();
                            break;
                        default:
                            throw new Exception('Not Implemented class '.$strClass);
                    }
                }
            }
        }
    }
}


