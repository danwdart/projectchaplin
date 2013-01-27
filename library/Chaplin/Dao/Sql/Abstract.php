<?php
abstract class Chaplin_Dao_Sql_Abstract implements Chaplin_Dao_Interface
{
    private static $_zendDb;

    public function __construct()
    {
        $configServers = Chaplin_Config_Servers::getInstance();
        if ($configServers->getSqlSettings()) {
            $configSettings = $configServers->getSqlSettings();
            $db = Zend_Db::factory($configSettings);
            self::$_zendDb = $db;
        }
    }

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

    abstract protected function _getPrimaryKey();

    abstract public function convertToModel($arrData);

    protected function _modelToSql(Array $arrModel)
    {
        $arrSql = $arrModel;
        if (isset($arrSql['_id'])) {
            $arrSql[$this->_getPrimaryKey()] = $arrSql['_id'];
            unset($arrSql['_id']);
        }
        return $arrSql;
    }

    protected function _sqlToModel(Array $arrSql)
    {
        $arrModel = $arrSql;
        $arrModel['_id'] = $arrModel[$this->_getPrimaryKey()];
        unset($arrModel[$this->_getPrimaryKey()]);
        return $arrModel;
    }

    private function _textToSafe($strText)
    {
        if('UTF-8' != mb_detect_encoding($strText)) {
            $strText = mb_convert_encoding($strText, 'UTF-8');
        }
        return $strText;
    }

    protected function _save(Chaplin_Model_Field_Hash $hash)
    {
        $strTable = $this->_getTable();
        $collFields = $hash->getFields($this);
        $arrUpdate = $this->_getUpdateArray($collFields);
        if ($hash->bIsNew()) {
            $this->_getAdapter()->insert($strTable, $arrUpdate);
        } else {
            $strWhere = $this->_getPrimaryKey().' = '.$hash->getId();
            $this->_getAdapter()->update($strTable, $arrUpdate, $strWhere);
        }
        $hash->postSave($this);
    }

    protected function _delete(Chaplin_Model_Field_Hash $hash)
    {
        $this->_getAdapter()->delete($this->_getTable(), 'bug_id = 3');
    }

    private function _getUpdateArray(Array $collFields)
    {
        $arrUpdate = array();
        foreach($collFields as $strFieldName => $objField) {
            if($objField->bIsDirty()) {
                $strClass = get_class($objField);
                switch($strClass) {
                    case 'Chaplin_Model_Field_Field':
                    case 'Chaplin_Model_Field_FieldId':
                        $arrUpdate[$strFieldName] = $this->_textToSafe($objField->getValue(null));
                        break;
                    default:
                        throw new Exception('Unmanaged class: '.$strClass);
                }
            }
        }
        return $this->_modelToSql($arrUpdate);
    }
}


