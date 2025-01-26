<?php

namespace Vosiz\Enums;

use Vosiz\Utils\Collections\Collection as Collection;

require_once(__DIR__.'/Ienum.php');
require_once(__DIR__.'/exc.php');

abstract class Enum implements IEnum {

    private static $Values; // collection of collections


    public static function GetValues(): Collection {

        $enum = self::GetEnumCollection();

        return $enum;
    }

    public static function GetEnum(string $enum_key) {

        $enum = self::GetEnumCollection();
        $class = static::class;
        if($enum->IsEmpty()) {
            $class::Init();
        }

        $enum_key = str_camel($enum_key);
        $val = $enum->{$enum_key};
        if($val === NULL)
            throw new EnumException("$enum_key was not found in enum $class");

        return $val;
    }


    public static function AddValues(array $values = array()) {

        $enum = self::GetEnumCollection();

        foreach($values as $k => $v) {

            // check value
            if(!is_numeric($v)) {

                throw new EnumException("Value add failed: $v is not a integer");
            }

            // check key
            if(!is_string($k)) {

                throw new EnumException("Value add failed: $k is not a string");
            }

            self::AddValue($enum, $k, $v);
        }
    }

    public static function AddValue(Collection &$enum, string $key, int $value) {

        $enum->Add($value, $key);
    }


    // chlivek, not enum
    protected static function GetEnumCollection(bool $strict = false) {

        // checking values
        if(self::$Values === NULL) {
            self::$Values = new Collection();
        }

        $name = str_camel(static::class);

        // checking if exists
        if(!self::$Values->HasKey($name)) {

            if($strict)
                throw new EnumException("GetEnum failed, key not found");

            self::$Values->Add(new Collection(), $name);
            self::GetEnumCollection(true); // try one more time
        }

        $enum = self::$Values->{$name};
        return $enum;
    }
}