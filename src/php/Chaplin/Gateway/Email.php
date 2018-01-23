<?php


namespace Chaplin\Gateway;

use Chaplin\Gateway\GatewayAbstract;
use Chaplin\Dao\Smtp\Exchange;
use Chaplin\Model\User;
use Chaplin\Model\Video;
use Chaplin\Gateway;


class Email extends GatewayAbstract
{
    private $_daoExchange;

    public function __construct(Exchange $daoExchange)
    {
        $this->_daoExchange = $daoExchange;
    }

    public function email(
        User $modelUser,
        $strSubject,
        $strTemplate,
        $arrParams
    ) {
    

        $this->_daoExchange->email($modelUser, $strSubject, $strTemplate, $arrParams);
    }

    public function videoFinished(
        Video $modelVideo
    ) {
    

        $strUsername = $modelVideo->getUsername();
        $modelUser = Gateway::getUser()->getByUsername($strUsername);

        $this->_daoExchange->email(
            $modelUser,
            'Video Finished Processing',
            'videofinished',
            [
            'Nick' => $modelUser->getNick(),
            'Name' => $modelVideo->getTitle()
            ]
        );
    }

    public function resetPassword(
        User $modelUser
    ) {
    
        $strVhost = getenv("VHOST");

        $strValidationToken = $modelUser->resetPassword();
        // Let's make sure that we save before we send the email.
        // It's a bit odd but it ensures atomic saves.
        Gateway::getUser()->save($modelUser);

        $this->_daoExchange->email(
            $modelUser,
            'Reset Password',
            'resetpassword',
            [
                'Url' => 'http://'.$strVhost.'/login/validate/token/'.$strValidationToken
            ]
        );
    }
}
