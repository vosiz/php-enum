<?php

namespace Vosiz\Enums;

use Vosiz\Utils\Collections\Collection as Collection;

require_once(__DIR__.'/Ienum.php');
require_once(__DIR__.'/exc.php');

abstract class Enum implements IEnum {

    private static $Values; // Enum containers

    private $Value;     public function GetValue()      { return $this->Value;      }
    private $Key;       public function GetKey()        { return $this->Key;        }
    private $EnumType;  public function GetType()       { return $this->EnumType;   }
    private $Display;   public function GetDisplay()    { return $this->Display;    }

    /**
     * Constructor
     * @param string $key name of enum
     * @param int $value value of enum
     * @param string $type classname of enum
     * @param array $additional parameters
     */
    public function __construct(string $key, int $value, string $type, array $pars = []) {

        $this->Value = $value;
        $this->Key = $key;
        $this->EnumType = $type;

        $this->Display = $key;

        // process pars
        if(!empty($pars) && count($pars) == 1) {

            try {

                // pars format
                // - [0]: (string) display
                $index = 0;

                // display
                $display = $pars[$index++];
                if(!is_string($display)) {

                    throw new EnumException("Wrong parameter[display]: $display");
                }
                $this->Display = $display;

            } catch (Exception $exc) {

                throw $exc;
            }

        }
    }

    /**
     * ToString override
     */
    public function __toString() {

        return sprintf("%s[%s]=0x%04X (%s)",
            typeof($this), tostr($this->Key), $this->Value, tostr($this->Value));
    }


    /**
     * IEnum required
     * @return Vosiz\Utils\Collections\Collection enumerations so far defined
     */
    public static function GetValues(): Collection {

        $collection = self::GetEnumCollection();
        if($collection->IsEmpty()) {
            
            $class = static::GetEnumType();
            $class::Init();
            $collection = self::GetEnumCollection();
        }

        return $collection;
    }

    /**
     * Gets an enum instance by name
     * @param string $name enum name/key
     * @return Enum
     * @throws EnumException
     */
    public static function GetEnum(string $name) {

        $collection = self::GetEnumCollection();
        $class = self::GetEnumType();

        if ($collection->IsEmpty()) {
            $class::Init();
        }

        $name = str_camel($name);
        if (!$collection->HasKey($name)) {
            throw new EnumException("Cannot get enum $name from $class");
        }

        return $collection->$name;
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
     * @param array $values assoc array, values can be arrays too
     * @throws EnumException
     */
    public static function AddValues(array $values = []) {

        $collection = self::GetEnumCollection();

        foreach ($values as $k => $v) {

            // additional parameters
            $params = array();

            // value check
            if(is_array($v)) { // value can be array

                if(count($v) != 2) {

                    throw new EnumException("Unsupported value (found defective array)");    
                }

                $params = $v;       // value is array
                $v = (int)$v[0];    // first param is value
                unsetra($params, 0);// unset value parameter and reorder

            } else if (!is_numeric($v)) { // value is number

                throw new EnumException("Value add failed: $v is not an integer");
            }

            // key check
            if (!is_string($k)) {

                if(is_numeric($k)) {

                    $k = (string)$k;

                } else {

                    throw new EnumException("Value add failed: $k is not a string");
                }
            }

            self::AddValue($collection, $k, (int)$v, $params);
        }
    }

    /**
     * Add single value to enum container
     * @param Collection $enum enum collection
     * @param string $key name of enum
     * @param int $value value of enum
     * @param array $pars additional parameters
     */
    public static function AddValue(Collection &$enum, string $key, int $value, array $pars) {

        $class = self::GetEnumType();
        $e = new $class($key, $value, $class, $pars);
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
     * Alias for GetValues
     * @return Collection
     */
    public static function GetAll() {

        return self::GetValues();
    }

    /**
     * Get enum collection/container
     * @param bool $strict leave at false
     * @return Collection
     * @throws EnumException
     */
    protected static function GetEnumCollection(bool $strict = false) {

        if (self::$Values === null) { // total init, no enums at all
            self::$Values = new Collection();
        }

        $name = static::GetEnumType();

        if (!self::$Values->HasKey($name)) {
            if ($strict) {
                throw new EnumException("GetEnum failed, key not found");
            }
            self::$Values->Add(new Collection(), $name);
        }

        $collection = self::$Values->{$name};       
        return $collection;
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
