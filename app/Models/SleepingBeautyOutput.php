<?php

namespace App\Models;

class SleepingBeautyOutput {
    public string $status;
    public int $code;
    public string $message;
    public string $contentType;
    public string $embedded;

    public function __construct() {
    }

    /**
     * @param string $contentType
     * @param string $embedded
     * @return SleepingBeautyOutput
     */
    public static function success(string $contentType, string $embedded): SleepingBeautyOutput {
        $output = new SleepingBeautyOutput();
        $output->contentType = $contentType;
        $output->embedded = $embedded;
        $output->code = 0;
        $output->message = '';
        $output->status = 'success';
        return $output;
    }

    /**
     * @param int $code
     * @param string $message
     * @return SleepingBeautyOutput
     */
    public static function error(int $code, string $message): SleepingBeautyOutput {
        $output = new SleepingBeautyOutput();
        $output->code = $code;
        $output->message = $message;
        $output->status = 'error';
        return $output;
    }
}
