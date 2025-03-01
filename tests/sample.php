<?php

// TESTS
// - 00 - enum vals
require_once(__DIR__.'/sample_00.php');
function Test00() {

    $result = EnumTest_SetAndGetEnums();
    var_dump($result);

    $result = EnumTest_CompareExpl();
    var_dump($result);
}

// - 01 - enums
require_once(__DIR__.'/sample_01.php');
function Test01() {

    $result = EnumTest_SetEnum1();
    var_dump($result);

    $result = EnumTest_SetEnum2();
    var_dump($result);

    $result = EnumTest_SetEnum3();
    var_dump($result);

    $result = EnumTest_EnumComp();
    var_dump($result);
}