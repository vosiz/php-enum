<?php

// TESTS
// - 00
require_once(__DIR__.'/sample_00.php');
function Test00() {

    $result = EnumTest_SetAndGetEnums();
    var_dump($result);

    $result = EnumTest_CompareExpl();
    var_dump($result);
}
