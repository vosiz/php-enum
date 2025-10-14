<?php

namespace Vosiz\Enums;

use Vosiz\Utils\Collections\Dictionary;

interface IEnum {

    /**
     * Initialize enumeration class
     */
    public static function Init(): void;

    /** 
     * Get all enumeration values
     * - solved in Enum class
    */
    public static function GetValues(): Dictionary;

}