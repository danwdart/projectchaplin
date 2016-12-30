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
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;

class Admin_SetupController extends Zend_Controller_Action
{
    public function init()
    {
    	parent::init();
        if (file_exists(APPLICATION_PATH.'/../config/chaplin.ini')) {
        	return $this->_redirect('/');
        }
        $this->_helper->_layout->setLayout('plain');
    }

    public function indexAction()
    {

    }

    public function sqltestAction()
    {
        $arrPost = $this->_request->getPost();
        $strAdapter = isset($arrPost['adapter'])?$arrPost['adapter']:null;
        $arrParams = isset($arrPost['params'])?$arrPost['params']:array();

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        try {
            $adapter = Zend_Db::factory($strAdapter, $arrParams);
            // Make sure we get a PDO instance that tries to connect
            $adapter->prepare('SELECT');
            echo 'DB Connect Success!';
        } catch (Exception $e) {
            echo 'DB Connect failed! Reason: '.$e->getMessage();
        }
    }

    public function amqptestAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        try {
            $amqp = new Amqp\Connection($this->_request->getPost());
            $amqp->connect();
            echo 'AMQP connection success!';
        } catch (Exception $e) {
            echo 'Sorry - the connection failed...';
        }
    }

    public function smtptestAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $arrSmtp = $this->_request->getPost();

        // Zend won't like this without
        if (isset($arrSmtp['options']) &&
            isset($arrSmtp['options']['ssl']) &&
            empty($arrSmtp['options']['ssl'])) {
            unset($arrSmtp['options']['ssl']);
        }
        $transport = new Zend_Mail_Transport_Smtp(
            $arrSmtp['host'],
            $arrSmtp['options']
        );

        // No SMTP tests exist atm
    }

    private function _enableVimeo($arrPost)
    {
        $serviceVimeo = Chaplin_Service::getInstance()->getVimeo();
        $clientId = $arrPost['default']['vimeo']['client_id'];
        $clientSecret = $arrPost['default']['vimeo']['client_secret'];
        $accessToken = $serviceVimeo->requestAccessToken($clientId, $clientSecret);

        $arrPost['default']['vimeo']['access_token'] = $accessToken;
        return $arrPost;
    }

    public function writeAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $arrPost = array(
            'default' => $this->_request->getPost(),
            'production' => array(),
            'staging' => array(),
            'development' => array()
        );

        $arrDefault = $arrPost['default'];

        if (!isset($arrDefault['sql'])) {
            echo 'No SQL settings exist.';
            exit;
        }

        $arrSql = $arrDefault['sql'];

        $strAdapter = isset($arrSql['adapter'])?$arrSql['adapter']:null;
        if (is_null($strAdapter)) {
            echo 'Did you forget to choose an adapter?';
            exit;
        }
        $arrParams = isset($arrSql['params'])?$arrSql['params']:array();

        try {
            $adapter = Zend_Db::factory($strAdapter, $arrParams);
            $app = new PhinxApplication();
            $wrap = new TextWrapper($app, ['environment' => 'chaplin']);
            echo $wrap->getMigrate();
        } catch (Exception $e) {
            echo 'Error writing DB: '.$e->getMessage().' Please refresh and try again.';
            exit();
        }

        $arrPost = $this->_enableVimeo($arrPost);

        $config = new Zend_Config($arrPost, true);
        $config->setExtend('production', 'default');
        $config->setExtend('staging', 'production');
        $config->setExtend('development', 'production');

        $iniWriter = new Zend_Config_Writer_Ini();
        $iniWriter->setConfig($config);
        try {
            $iniWriter->write(APPLICATION_PATH.'/../config/chaplin.ini');
            if (!$arrPost['default']['vimeo']['access_token'])
                echo 'Vimeo access token not available. Go back and try again if you plan on using it.'.PHP_EOL;

            echo 'File successfully written.'.PHP_EOL.
                'Starting application.'.PHP_EOL;
            // Now start everything
            system(APPLICATION_PATH.'/../cli.sh start');
        } catch (Exception $e) {
            echo 'Could not write file. Please either allow permissions to config/chaplin.ini to your web user and retry or copy and insert the following into the file /config/chaplin.ini, and then start the servers using ./cli.sh start :'.PHP_EOL;
            echo $iniWriter->render();
        }
    }
}
