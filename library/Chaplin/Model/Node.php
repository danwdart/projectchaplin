<?php
class Chaplin_Model_Node extends Chaplin_Model_Field_Hash
{
    const FIELD_NODEID = self::FIELD_ID;
    const FIELD_IP = 'IP';
    const FIELD_NAME = 'Name';
    const FIELD_ACTIVE = 'Active';

    protected $_arrFields = array(
        self::FIELD_NODEID => array('Class' => 'Chaplin_Model_Field_FieldId'),
        self::FIELD_IP => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_NAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_ACTIVE => array('Class' => 'Chaplin_Model_Field_Field')
    );

    public static function create($strIP, $strName)
    {
        $node = new self();
        $node->_bIsNew = true;
        $node->_setField(self::FIELD_NODEID, md5(uniqid()));
        $node->_setField(self::FIELD_IP, $strIP);
        $node->_setField(self::FIELD_NAME, $strName);
        $node->_setField(self::FIELD_ACTIVE, false);
        return $node;
    }

    public function getNodeId()
    {
        return $this->_getField(self::FIELD_NODEID, null);
    }

    public function getIP()
    {
        return $this->_getField(self::FIELD_IP, null);
    }

    public function getName()
    {
        return $this->_getField(self::FIELD_NAME, null);
    }

    public function bIsActive()
    {
        return $this->_getField(self::FIELD_ACTIVE, false);
    }

    public function getStatusURL()
    {
        return 'http://'.$this->getIP().'/admin/nodestatus?format=json';
    }

    public function ping()
    {
        $response = Chaplin_Service::getInstance()
            ->getHttpClient()
            ->getHttpResponse($this->getStatusURL(), null, false);
        if(200 == $response->getStatus()) {
            $this->_setField(self::FIELD_ACTIVE, true);
            $this->save();
            return true;
        }
        return false;
    }

    public function delete()
    {
        return Chaplin_Gateway::getInstance()->getNode()->delete($this);
    }

    public function save()
    {
        return Chaplin_Gateway::getInstance()->getNode()->save($this);
    }
}
