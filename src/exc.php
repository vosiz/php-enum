<?php

namespace Vosiz\Enums;

class EnumException extends \Exception {

    /**
     * Basic constructor
     * @param string $msg message
     */
    public function __construct(string $msg) {

        return parent::__construct($msg);
    }
}