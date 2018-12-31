<?php

namespace App\Utils;

class ResponseWrapper {
    public static function wrap($success, $code, $name, $data) {
        $res = [
            "success" => $success,
            "code" => $code,
            $name => $data
        ];
        return $res;
    }
}
