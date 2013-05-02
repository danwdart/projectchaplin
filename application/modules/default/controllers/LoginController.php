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
class LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new default_Form_Login();

        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }

        $post = $this->_request->getPost();

        $username = $post['username'];
        $password = $post['password'];

        if(isset($post['Register'])) {
            return $this->_redirect('/login/register');
        }
        
        if(!$form->isValid($post)) {
            return $this->view->assign('form', $form);
        }
        
        if(!isset($post['Login'])) {
            $form->password->addError('Invalid Action');
            return $this->view->assign('form', $form);
        }
            
        $adapter = new Chaplin_Auth_Adapter_Database($username, $password);
        $auth = Chaplin_Auth::getInstance();
        $auth->authenticate($adapter);
        if(!$auth->hasIdentity()) {
            $form->password->addError('Wrong username or password. Want to try again?');
            $form->markAsError();
            return $this->view->assign('form', $form);
        }

        $login = new Zend_Session_Namespace('login');
        if (!is_null($login->url)) {
            $this->_redirect($login->url);
            $login->url = null;
            return;
        }

        return $this->_redirect('/');
    }

    public function logoutAction()
    {
        $this->view->layout()->disableLayout();
        $renderer = $this->getHelper('ViewRenderer');
        $renderer->setNoRender(true);

        Chaplin_Auth::getInstance()->clearIdentity();
        $this->_redirect($this->_redirect_url);
    }

    public function registerAction()
    {
        $form = new default_Form_UserData_Create();

        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }

        $post = $this->_request->getPost();
        
        if(!$form->isValid($post)) {
            return $this->view->assign('form', $form);
        }

        $username = $post['username'];
        $password = $post['password'];
        $password2 = $post['password2'];
        $email = $post['email'];
        $fullname = $post['fullname'];
        // TODO validate email
        // TODO validate username
        // TODO validate password
        // TODO check if user exists
        try
        {
            $user = Chaplin_Model_User::create($username, $password);
            $user->setEmail($email);
            $user->setNick($fullname);
            $user->setUserType(
                new Chaplin_Model_User_Helper_UserType(
                    Chaplin_Model_User_Helper_UserType::ID_USER
                )
            );
            $user->save();
                   
            // AJAX: Success
            return $this->_redirect($this->_redirect_url);
        }
        catch (Zend_Db_Statement_Exception $e) {
            $form->username->addError('Could not create account - a user aleady exists with that name');
            $form->markAsError();
            return $this->view->assign('form', $form);
        }
        catch(Exception $e)
        {
            $form->username->addError('Could not create account. Reason: '.$e->getMessage());
            $form->markAsError();
            return $this->view->assign('form', $form);
        }
    }

    public function oauthAction()
    {
        $oauth = [
            'google' => [
                'oauth_version' => 2,
                'client_id' => '489050087246.apps.googleusercontent.com',
                'client_secret' => 'csIFAlwR65VATyk-hs9P2aId',
                'scope' => 'openid%20email%20profile',  
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'redirect_uri' => 'http://manc.dandart.co.uk/login/oauth/provider/google',
                'token_uri' => 'https://accounts.google.com/o/oauth2/token',
                'info_uri' => 'https://www.googleapis.com/oauth2/v3/userinfo',
                'callback_decode' => ['Zend_Json','decode'],
                'key_email' => 'email',
                'key_fullname' => 'name',
                'key_firstname' => 'given_name',
                'key_lastname' => 'family_name'
            ],
            'facebook' => [
                'oauth_version' => 2,
                'client_id' => '189586011191131',
                'client_secret' => 'a9756399b811b37fdbcaacc3ec185dfa',
                'scope' => 'email',
                'auth_uri' => 'https://www.facebook.com/dialog/oauth',
                'redirect_uri' => 'http://manc.dandart.co.uk/login/oauth/provider/facebook',
                'token_uri' => 'https://graph.facebook.com/oauth/access_token',
                'info_uri' => 'https://graph.facebook.com/me',
                'callback_decode' => function($str){parse_str($str,$arr);return $arr;},
                'key_email' => 'email',
                'key_fullname' => 'name',
                'key_firstname' => 'first_name',
                'key_lastname' => 'last_name'
            ],
            'twitter' => [
                'oauth_version' => 1,
                'callbackUrl' => 'http://manc.dandart.co.uk/login/oauth/provider/twitter',
                'siteUrl' => 'https://api.twitter.com/oauth',
                'consumerKey' => 'EFlUeaMDcqnXAJYLqX7jQ',
                'consumerSecret' => 'wjxSWjDhRPslwnjGKil7ocsyovomhw5Bx6cznzW4Ag',
                'info_uri' => 'https://api.twitter.com/1/account/verify_credentials.json',
                'key_fullname' => 'name',
                'key_email' => 'screen_name'
            ],
            'identica' => [
                'oauth_version' => 1,
                'callbackUrl' => 'http://manc.dandart.co.uk/login/oauth/provider/identica',
                'siteUrl' => 'https://identi.ca/api/oauth',
                'consumerKey' => 'b9fbdb19e1bd55b4d1688e1def2bd1c8',
                'consumerSecret' => 'ef78ff72983c3681dd5158b1da8efe7c',
                'info_uri' => 'https://identi.ca/api/account/verify_credentials.json',
                'key_fullname' => 'name',
                'key_email' => 'screen_name'
            ]
        ];

        $strProvider = $this->_request->getParam('provider');
        if (!isset($oauth[$strProvider])) {
            die('invalid provider');
        }
        $arrOauth = $oauth[$strProvider];

        $this->_helper->viewRenderer->setNoRender();
        $this->_session = new Zend_Session_Namespace('oauth');

        switch ($arrOauth['oauth_version']) {
            case 2:

                if (is_null($this->_request->getQuery('code'))) {
                    $state = md5(uniqid());
                
                    $this->_session->state = $state;

                    $url = $arrOauth['auth_uri'].
                        '?client_id='.$arrOauth['client_id'].
                        '&response_type=code'.
                        '&scope='.$arrOauth['scope'].
                        '&redirect_uri='.$arrOauth['redirect_uri'].
                        '&state='.$state;

                    return $this->_redirect($url);
                }
                $state = $this->_session->state;
                $getstate = $this->_request->getQuery('state');
                if ($getstate != $state) {
                    die('Unauthorised, buddy. Hey:  ' . $getstate . ' ' . $state);
                }
                $this->_session->state = null;

                $client = new Zend_Http_Client($arrOauth['token_uri']);
                $client->setMethod(Zend_Http_Client::POST);
                $client->setParameterPost([
                    'code' => $_GET['code'],
                    'client_id' => $arrOauth['client_id'],
                    'client_secret' => $arrOauth['client_secret'],
                    'redirect_uri' => $arrOauth['redirect_uri'],
                    'grant_type' => 'authorization_code'
                ]);
                $response = $client->request('POST');
                $body = $response->getBody();

                $callback_decode = $arrOauth['callback_decode'];

                $arrInfo = $callback_decode($body);

                $strAccessToken = $arrInfo['access_token'];
                $infoUri = $arrOauth['info_uri'].'?oauth_token='.   $strAccessToken;

                $client = new Zend_Http_Client($infoUri);
                $response = $client->request();
                break;

            case 1:
                $consumer = new Zend_Oauth_Consumer($arrOauth);
                $arrQuery = $this->_request->getQuery();
                if (empty($arrQuery) &&
                    is_null($this->_session->request_token)
                ) { 
                    $token = $consumer->getRequestToken();
                    $this->_session->request_token = serialize($token);
                    return $consumer->redirect();
                }

                $request_token = unserialize($this->_session->request_token);
                if (!$request_token) {
                    $this->_session->request_token = null;
                    return $this->_redirect($arrOauth['callbackUrl']);
                }

                try {
                    $token = $consumer->getAccessToken(
                        $arrQuery,
                        unserialize($this->_session->request_token)
                    );
                } catch (Zend_Oauth_Exception $e) {
                    $this->_session->request_token = null;
                    return $this->_redirect($arrOauth['callbackUrl']);
                }

                $this->_session->access_token = $token;
                $this->_session->request_token = null;

                $zendClient = $token->getHttpClient($arrOauth);
                $zendClient->setMethod(Zend_Http_Client::GET);
                $zendClient->setUri($arrOauth['info_uri']);
                $response = $zendClient->request();
                break;
            default:
                throw new Exception('unknown api version');
        }

        $arrResponse = Zend_Json::decode($response->getBody());
        $email = $arrResponse[$arrOauth['key_email']];
        echo 'Name: '.$arrResponse[$arrOauth['key_fullname']].'<br/>';
        if (isset($arrOauth['key_firstname']))
            echo 'First Name: '.$arrResponse[$arrOauth['key_firstname']].'<br/>';
        if (isset($arrOauth['key_lastname']))
        echo 'Last Name: '.$arrResponse[$arrOauth['key_lastname']].'<br/>';
        echo 'Email: '.$arrResponse[$arrOauth['key_email']];
    }

    public function openidAction()
    {

    }
}
