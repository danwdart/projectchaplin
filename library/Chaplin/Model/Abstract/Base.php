<?php
abstract class Chaplin_Model_Abstract_Base extends Chaplin_Model_Abstract
{
    const FIELD_Id ='_id';

    public static function createFromDao(Chaplin_Dao_Interface $dao, Array $arrDao)
    {
        $model = new static();

        foreach($arrDao as $strKey => $value) {
            if(is_array($value)) {
                continue;
            }
            $model->_setField($strKey, $value);
        }

        return $model;
    }

    public function getId()
    {
        return $this->_getField(self::FIELD_Id, null);
    }

    abstract public function delete();
}
