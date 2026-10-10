<?php

namespace Vosiz\Enums;

use Vosiz\Utils\Collections\Dictionary;

require_once(__DIR__.'/Ienum.php');
require_once(__DIR__.'/exc.php');

abstract class Enum implements IEnum {

    private static $Values = [];        // Enum containers, by enum class name
    private static $Initialized = [];   // Enums with Init() already run, by enum class name

    private $Value;     public function GetValue()      { return $this->Value;      }
    private $Key;       public function GetKey()        { return $this->Key;        }
    private $EnumType;  public function GetType()       { return $this->EnumType;   }
    private $Display;   public function GetDisplay()    { return $this->Display;    }

    /**
     * Constructor, enums are created only by AddValues/AddValue
     * @param string $key name of enum
     * @param int $value value of enum
     * @param string $type classname of enum
     * @param array $pars additional parameters
     * @throws EnumException
     */
    protected function __construct(string $key, int $value, string $type, array $pars = []) {

        $this->Value = $value;
        $this->Key = $key;
        $this->EnumType = $type;

        $this->Display = $key;

        // process pars
        // pars format
        // - [0]: (string) display
        if(count($pars) > 1) {

            throw new EnumException("Too many parameters for $key: ".count($pars));
        }

        if(!empty($pars)) {

            $index = 0;

            // display
            $display = $pars[$index++];
            if(!is_string($display)) {

                throw new EnumException("Wrong parameter[display]: ".tostr($display));
            }
            $this->Display = $display;
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
     * - returns a copy, changing it does not change the enum
     * - keys are camelized (my_key is held as MyKey), GetKey() of an enum keeps the key as written
     * @return Vosiz\Utils\Collections\Dictionary enumerations so far defined
     */
    public static function GetValues(): Dictionary {

        self::EnsureInit();

        return new Dictionary(self::GetEnumCollection()->ToArray());
    }

    /**
     * Gets an enum instance by name
     * @param string $name enum name/key
     * @return Enum
     * @throws EnumException
     */
    public static function GetEnum(string $name) {

        self::EnsureInit();

        $collection = self::GetEnumCollection();
        $class = self::GetEnumType();

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
     * - values added from outside of Init() land after the preset ones
     * - value is an integer or a string holding one, or array [value, display]
     * - keys are camelized, my_key and MyKey are the same key
     * @param array $values assoc array, values can be arrays too
     * @throws EnumException
     */
    public static function AddValues(array $values = []) {

        self::EnsureInit();

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
                $v = $v[0] ?? NULL; // first param is value
                unsetra($params, 0);// unset value parameter and reorder
            }

            if(!self::IsEnumValue($v)) {

                throw new EnumException("Value add failed: ".tostr($v)." (".gettype($v).") is not an integer or a string holding one");
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
     * @param Dictionary $enum enum collection
     * @param string $key name of enum
     * @param int $value value of enum
     * @param array $pars additional parameters
     * @throws EnumException
     */
    public static function AddValue(Dictionary &$enum, string $key, int $value, array $pars) {

        $class = self::GetEnumType();
        if($enum->HasKey($key)) {

            throw new EnumException("Key $key is already registered in $class");
        }

        $e = new $class($key, $value, $class, $pars);
        $enum->Add($e, $key);
    }

    /**
     * Finds first enum by value
     * @param int $value value to find
     * @return Enum|null if exists
     */
    public static function Find(int $value) {

        self::EnsureInit();

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
     * @return Dictionary
     */
    public static function GetAll() {

        return self::GetValues();
    }

    /**
     * Get enum collection/container
     * @param bool $strict leave at false
     * @return Dictionary
     * @throws EnumException
     */
    protected static function GetEnumCollection(bool $strict = false) {

        $name = static::GetEnumType();

        if (!isset(self::$Values[$name])) {
            if ($strict) {
                throw new EnumException("GetEnum failed, key not found");
            }
            self::$Values[$name] = new Dictionary();
        }

        $collection = self::$Values[$name];
        return $collection;
    }

    /**
     * Gets name of current enum
     * @return string class name of current enum
     */
    protected static function GetEnumType() {

        return static::class;
    }

    /**
     * Runs Init() of current enum, once per enum class
     */
    protected static function EnsureInit() {

        $class = static::GetEnumType();
        if(isset(self::$Initialized[$class])) {

            return;
        }

        self::$Initialized[$class] = true; // set before Init(), it adds values itself
        $class::Init();
    }

    /**
     * Checks if value can be used as enum value
     * @param mixed $value
     * @return bool true for integer or string holding one
     */
    protected static function IsEnumValue($value) {

        return is_int($value) || (is_string($value) && preg_match('/^-?\d+$/D', $value) === 1);
    }


    /**
     * Compares enum to enum
     * @param Enum $enum
     * @return bool true if same enum type and same value
     */
    public function Compare(Enum $enum) {

        return $enum->GetType() === $this->EnumType && $enum->GetValue() == $this->Value;
    }

    /**
     * Alias for GetKey
     * @return string key as written when added, not camelized
     */
    public function GetName() {

        return $this->GetKey();
    }

}
