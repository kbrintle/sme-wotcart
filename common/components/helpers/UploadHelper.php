<?php

namespace common\components\helpers;

use Yii;

class UploadHelper
{

    public static function hashFile($filename, $extension) {
        $filename = sha1($filename . time());
        return "$filename.$extension";
    }

}