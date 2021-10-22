<?php

namespace App\Tasks;

use App\Forms\ApplicationForm;
use App\Models\SleepingBeautyOutput;

class SleepingBeautyTask {
    public static function run(ApplicationForm $form): ?SleepingBeautyOutput {
        for ($i = 0; $i < $form->seconds; $i++) {
            sleep(1);
        }

        $output = null;
        $png = imagecreate(400, 300);

        if ($png !== false) {
            $bg = imagecolorallocate($png, 192, 192, 192);
            $tx = imagecolorallocate($png, 0, 0, 0);
            imagefilledrectangle($png, 0, 0, 800, 500, $bg);

            imagestring($png, 20, 20, 50, $form->email, $tx);
            imagestring($png, 20, 20, 250, $form->address, $tx);

            ob_start();
            if (imagepng($png) !== false) {
                $raw = ob_get_clean();
                $output = SleepingBeautyOutput::success("image/png", 'data:image/png;base64,' . base64_encode($raw) . '');
            }

            imagedestroy($png);
        }
        return $output;
    }
}
