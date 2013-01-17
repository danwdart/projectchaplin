<?php
class Chaplin_Config_Sessions
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Ini';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/config/sessions.ini');
    }
    
    public function getName()
    {
    	$this->_getValue(
            $this->_zendConfig->session->name,
            'session.name'
        );	
    }

    public function getRememberMeSeconds()
    {
		$this->_getValue(
            $this->_zendConfig->session->remember_me_seconds,
            'session.remember_me_seconds'
        );    		
    }

    public function getSessionOptions()
    {
    	return $this->_getValue(
            $this->_zendConfig->session,
            'session'
        )->toArray();	
    }

    public function getSaveHandler()
    {
    	$strClassName = $this->_getValue(
            $this->_zendConfig->saveHandler->class,
            'saveHandler.class'
        );
        $arrOptions = $this->_getValue(
        	$this->_zendConfig->saveHandler->options,
        	'savehandler.options'
        );
        return new $strClassName($arrOptions);
    }
}
