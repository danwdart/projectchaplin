<?php


namespace Chaplin\Model\Video;

use OutOfBoundsException;

class Privacy
{
    const FIELD_TEXT = 'Text';
    const FIELD_DESC = 'Description';

    const ID_PUBLIC = 0;
    const ID_PRIVATE = 1;

    const TEXT_PUBLIC = 'Public';
    const TEXT_PRIVATE = 'Private';

    const DESC_PUBLIC = 'Everyone';
    const DESC_PRIVATE = 'Only yourself';

    private static $arrInfo = [
    self::ID_PUBLIC => [
    self::FIELD_TEXT    => self::TEXT_PUBLIC,
    self::FIELD_DESC    => self::DESC_PUBLIC
    ],
    self::ID_PRIVATE => [
    self::FIELD_TEXT    => self::TEXT_PRIVATE,
    self::FIELD_DESC    => self::DESC_PRIVATE
    ]
    ];

    private $strId;

    public static function getSelectOptions()
    {
        $arrOut = [];
        foreach (self::$arrInfo as $strId => $arrOption) {
            $arrOut[$strId] = $arrOption[self::FIELD_DESC];
        }
        return $arrOut;
    }

    public function __construct($strId)
    {
        if (!isset(self::$arrInfo[$strId])) {
            throw new OutOfBoundsException('Invalid id: '.$strId);
        }

        $this->strId = $strId;
    }

    public function getText()
    {
        return self::$arrInfo[$this->strId][self::FIELD_TEXT];
    }

    public function getDescription()
    {
        return self::$arrInfo[$this->strId][self::FIELD_DESC];
    }

    public function isPublic()
    {
        return self::ID_PUBLIC == $this->strId;
    }

    public function isPrivate()
    {
        return self::ID_PRIVATE == $this->strId;
    }
}
