<?php
$strVhost = Chaplin_Config_Chaplin::getInstance()->getFullVhost();
return [
    'google' => [
        'registration_url' => 'https://code.google.com/apis/console/',
        'oauth_version' => 2,
        'client_id' => '489050087246.apps.googleusercontent.com',
        'client_secret' => 'csIFAlwR65VATyk-hs9P2aId',
        'scope' => 'openid%20email%20profile',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'redirect_uri' => $strVhost.'/login/oauth/provider/google',
        'token_uri' => 'https://accounts.google.com/o/oauth2/token',
        'info_uri' => 'https://www.googleapis.com/oauth2/v3/userinfo',
        'callback_decode' => ['Zend_Json','decode'],
        'key_email' => 'email',
        'key_fullname' => 'name',
        'key_firstname' => 'given_name',
        'key_lastname' => 'family_name'
    ],
    'facebook' => [
        'registration_url' => 'https://developers.facebook.com/apps/',
        'oauth_version' => 2,
        'client_id' => '189586011191131',
        'client_secret' => 'a9756399b811b37fdbcaacc3ec185dfa',
        'scope' => 'email',
        'auth_uri' => 'https://www.facebook.com/dialog/oauth',
        'redirect_uri' => $strVhost.'/login/oauth/provider/facebook',
        'token_uri' => 'https://graph.facebook.com/oauth/access_token',
        'info_uri' => 'https://graph.facebook.com/me',
        'callback_decode' => function($str){parse_str($str,$arr);return $arr;},
        'key_email' => 'email',
        'key_fullname' => 'name',
        'key_firstname' => 'first_name',
        'key_lastname' => 'last_name'
    ],
    'twitter' => [
        'registration_url' => 'https://dev.twitter.com/apps',
        'oauth_version' => 1,
        'callbackUrl' => $strVhost.'/login/oauth/provider/twitter',
        'siteUrl' => 'https://api.twitter.com/oauth',
        'consumerKey' => 'EFlUeaMDcqnXAJYLqX7jQ',
        'consumerSecret' => 'wjxSWjDhRPslwnjGKil7ocsyovomhw5Bx6cznzW4Ag',
        'info_uri' => 'https://api.twitter.com/1.1/account/verify_credentials.json',
        'key_username' => 'screen_name',
        'key_fullname' => 'name',
    ],
    // TODO update to statusnet
    'identica' => [
        'oauth_version' => 1,
        'callbackUrl' => $strVhost.'/login/oauth/provider/identica',
        'siteUrl' => 'https://identi.ca/api/oauth',
        'consumerKey' => 'b9fbdb19e1bd55b4d1688e1def2bd1c8',
        'consumerSecret' => 'ef78ff72983c3681dd5158b1da8efe7c',
        'info_uri' => 'https://identi.ca/api/account/verify_credentials.json',
        'key_fullname' => 'name',
        'key_email' => 'screen_name'
    ]
];
