<?php

namespace App\Models;

use JetBrains\PhpStorm\Pure;

class AjaxResponse {
    public $code;
    public $message;

    /**
     * @param $code
     * @param $message
     */
    public function __construct($code, $message) {
        $this->code = $code;
        $this->message = $message;
    }


    /**
     * @return AjaxResponse
     */
    #[Pure] public static function success(): AjaxResponse {
        return new AjaxResponse(0, "success");
    }
}
