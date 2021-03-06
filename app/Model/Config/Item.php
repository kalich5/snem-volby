<?php

declare(strict_types=1);

namespace Model\Config;

use Consistence\Enum\Enum;

/**
 * @method string getValue()
 */
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
class Item extends Enum
{
    public const VOTING_BEGIN   = 'voting_begin';
    public const VOTING_END     = 'voting_end';
    public const VOTING_PUBLISH = 'voting_publish';

    public function toString() : string
    {
        return $this->getValue();
    }

    public function __toString() : string
    {
        return $this->getValue();
    }

    public static function VOTING_BEGIN() : self
    {
        return self::get(self::VOTING_BEGIN);
    }

    public static function VOTING_END() : self
    {
        return self::get(self::VOTING_END);
    }

    public static function VOTING_PUBLISH() : self
    {
        return self::get(self::VOTING_PUBLISH);
    }
}
