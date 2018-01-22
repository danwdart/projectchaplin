<?php
class Chaplin_Model_Video_Licence
{
    const FIELD_TEXT = 'Text';
    const FIELD_URL = 'URL';

    const ID_CCBY = 'ccby';
    const ID_CCBYSA = 'ccbysa';
    const ID_CCBYND = 'ccbynd';
    const ID_CCBYNC = 'ccbync';
    const ID_CCBYNCSA = 'ccbyncsa';
    const ID_CCBYNCND = 'ccbyncnd';
    const ID_CC0 = 'cc0';

    const TEXT_CCBY = 'CC-BY';
    const TEXT_CCBYSA = 'CC-BY-SA';
    const TEXT_CCBYND = 'CC-BY-ND';
    const TEXT_CCBYNC = 'CC-BY-NC';
    const TEXT_CCBYNCSA = 'CC-BY-NC-SA';
    const TEXT_CCBYNCND = 'CC-BY-NC-ND';
    const TEXT_CC0 = 'CC-0';

    const HOST_CC = "http://creativecommons.org";
    const PATH_LICENCES = "/licenses";

    const URL_CCBY = self::HOST_CC.'/by/3.0/';
    const URL_CCBYSA = self::HOST_CC.'/licenses/by-sa/3.0/';
    const URL_CCBYND = self::HOST_CC.'/licenses/by-nd/3.0/';
    const URL_CCBYNC = self::HOST_CC.'/licenses/by-nc/3.0/';
    const URL_CCBYNCSA = self::HOST_CC.'/licenses/by-nc-sa/3.0/';
    const URL_CCBYNCND = self::HOST_CC.'/licenses/by-nc-nd/3.0/';
    const URL_CC0 = self::HOST_CC.'/publicdomain/zero/1.0/';

    private static $_arrInfo = [
    self::ID_CCBY => [
    self::FIELD_TEXT    => self::TEXT_CCBY,
    self::FIELD_URL     => self::URL_CCBY
    ],
    self::ID_CCBYSA => [
    self::FIELD_TEXT    => self::TEXT_CCBYSA,
    self::FIELD_URL     => self::URL_CCBYSA
    ],
    self::ID_CCBYND => [
    self::FIELD_TEXT    => self::TEXT_CCBYND,
    self::FIELD_URL     => self::URL_CCBYND
    ],
    self::ID_CCBYNC => [
    self::FIELD_TEXT    => self::TEXT_CCBYNC,
    self::FIELD_URL     => self::URL_CCBYNC
    ],
    self::ID_CCBYNCSA => [
    self::FIELD_TEXT    => self::TEXT_CCBYNCSA,
    self::FIELD_URL     => self::URL_CCBYNCSA
    ],
    self::ID_CCBYNCND => [
    self::FIELD_TEXT    => self::TEXT_CCBYNCND,
    self::FIELD_URL     => self::URL_CCBYNCND
    ],
    self::ID_CC0 => [
    self::FIELD_TEXT    => self::TEXT_CC0,
    self::FIELD_URL     => self::URL_CC0
    ]
    ];

    private $_strId;

    public static function getSelectOptions()
    {
        $arrOut = [];
        foreach (self::$_arrInfo as $strId => $arrOption) {
            $arrOut[$strId] = $arrOption[self::FIELD_TEXT];
        }
        return $arrOut;
    }

    public static function createWithVimeoId($id)
    {
        return new self('cc'.(is_null($id)?'0':str_replace('-', '', $id)));
    }

    public function __construct($strId)
    {
        if (!isset(self::$_arrInfo[$strId])) {
            throw new OutOfBoundsException('Invalid id: '.$strId);
        }

        $this->_strId = $strId;
    }

    public function getId()
    {
        return $this->_strId;
    }

    public function getText()
    {
        return self::$_arrInfo[$this->_strId][self::FIELD_TEXT];
    }

    public function getURL()
    {
        return self::$_arrInfo[$this->_strId][self::FIELD_URL];
    }
}
