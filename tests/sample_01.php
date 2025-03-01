<?php

use Vosiz\Utils\Collections\Collection as Collection;
use Vosiz\Enums\Enum;

require_once(__DIR__.'/../src/enum.php');

class LogEnum extends Enum {

    public static function Init(): void {

        $vals = [
            'none'      => 0,
            'debug'     => 1,
            'error'     => 2,
            'extreme'   => 50,
        ];
        self::AddValues($vals);
    } 
}

class Log {

    private $CurrentLogLevel; // enum

    public function __construct() {

        // default log level set to something
        $this->CurrentLogLevel = LogEnum::GetEnum('extreme');
    } 

    public function GetLl() {

        return $this->CurrentLogLevel;
    }

    public function SetLlByInt(int $byvalue) {

        $this->CurrentLogLevel = LogEnum::Find($byvalue);
    }

    public function SetLlByKey(string $bykey) {

        $this->CurrentLogLevel = LogEnum::GetEnum($bykey);
    }

    public function SetLlByEnum(LogEnum $byenum) {

        $this->CurrentLogLevel = $byenum;
    }

    public function ShouldLog(string $something, LogEnum $level) {

        //return 
    }
}

// Tests
function EnumTest_SetEnum1() {

    $log = new Log();
    $log->SetLlByInt(1);
    $enum = $log->GetLl();

    return $enum;
}

function EnumTest_SetEnum2() {

    $log = new Log();
    $log->SetLlByKey('none');
    $enum = $log->GetLl();

    return $enum;
}

function EnumTest_SetEnum3() {

    $log = new Log();
    $log->SetLlByEnum(LogEnum::GetEnum('error'));
    $enum = $log->GetLl();

    return $enum;
}

function EnumTest_EnumComp() {

    $log = new Log();
    return $log->getLl()->Compare(LogEnum::GetEnum('extreme'));
}

