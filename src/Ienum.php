<?php

namespace Vosiz\Enums;

// use Vosiz\Utils\Collections;
use Vosiz\Utils\Collections\Collection as Collection;

interface IEnum {

    /**
     * Initialize enumeration class
     */
    public static function Init(): void;

    /** 
     * Get all enumeration values
     * - solved in Enum class
    */
    public static function GetValues(): Collection;

}