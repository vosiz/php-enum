<?php

namespace Vosiz\Enums;

use Vosiz\Utils\Collections\Collection as Collection;

require_once(__DIR__.'/Ienum.php');
require_once(__DIR__.'/exc.php');

abstract class Enum implements IEnum {

    private static $Values; // Enum containers

    private $Value;     public function GetValue()  { return $this->Value;  }
    private $Key;       public function GetKey()    { return $this->Key;    }
    private $EnumType;  public function GetType()   { return $this->EnumType;   }

    /**
     * Constructor
     * @param string $key name of enum
     * @param int $value value of enum
     * @param string $type classname of enum
     */
    public function __construct(string $key, int $value, string $type) {

        $this->Value = $value;
        $this->Key = $key;
        $this->EnumType = $type;
    }

    /**
     * IEnum required
     * @return Vosiz\Utils\Collections\Collection enumerations so far defined
     */
    public static function GetValues(): Collection {

        return self::GetEnumCollection();
    }

    /**
     * Gets an enum instance by name
     * @param string $name enum name/key
     * @return Enum
     * @throws EnumException
     */
    public static function GetEnum(string $name) {

        $enum = self::GetEnumCollection();
        $class = self::GetEnumType();

        if ($enum->IsEmpty()) {
            $class::Init();
        }

        $name = str_camel($name);
        if (!$enum->HasKey($name)) {
            throw new EnumException("Cannot get enum $name from $class");
        }

        return $enum->$name;
    }

    /**
     * Gets enum value by key
     * @param string $enum_key
     * @return int
     */
    public static function GetEnumVal(string $enum_key) {

        $enum = self::GetEnum($enum_key);
        return $enum->GetValue();
    }

    /**
     * Add values to enum container
     * @param array $values assoc array
     * @throws EnumException
     */
    public static function AddValues(array $values = []) {

        $enum = self::GetEnumCollection();

        foreach ($values as $k => $v) {

            if (!is_numeric($v)) {
                throw new EnumException("Value add failed: $v is not an integer");
            }

            if (!is_string($k)) {
                throw new EnumException("Value add failed: $k is not a string");
            }

            self::AddValue($enum, $k, (int)$v);
        }
    }

    /**
     * Add single value to enum container
     * @param Collection $enum enum collection
     * @param string $key name of enum
     * @param int $value value of enum
     */
    public static function AddValue(Collection &$enum, string $key, int $value) {

        $class = self::GetEnumType();
        $e = new $class($key, $value, $class);
        $enum->Add($e, $key);
    }

    /**
     * Finds first enum by value
     * @param int $value value to find
     * @return Enum|null if exists
     */
    public static function Find(int $value) {

        $enum = self::GetEnumCollection();
        foreach ($enum->ToArray() as $key => $e) {
 
            if ($e->GetValue() === $value) {

                return $e;
            }
        }
        
        return null;
    }

    /**
     * Get enum collection/container
     * @param bool $strict leave at false
     * @return Collection
     * @throws EnumException
     */
    protected static function GetEnumCollection(bool $strict = false) {

        if (self::$Values === null) {
            self::$Values = new Collection();
        }

        $name = static::GetEnumType();

        if (!self::$Values->HasKey($name)) {
            if ($strict) {
                throw new EnumException("GetEnum failed, key not found");
            }
            self::$Values->Add(new Collection(), $name);
        }

        return self::$Values->{$name};
    }

    /**
     * Gets name of current enum
     * @return string class name of current enum
     */
    protected static function GetEnumType() {

        return str_camel(static::class);
    }

    /**
     * Compares value to enum
     * @param Enum $enum
     * @return bool true if same
     */
    public function Compare(Enum $enum) {

        return $enum->GetValue() == $this->Value;
    }

    /**
     * Alias for GetKey
     * @return string
     */
    public function GetName() {

        return $this->GetKey();
    }
}
