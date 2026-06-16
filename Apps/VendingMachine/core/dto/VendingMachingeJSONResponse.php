<?php

namespace Apps\VendingMachine\Core\DTO;

class VendingMachingeJSONResponse
{
    public string $msg;
    public bool $success;
    public $data;

    public static function build(string $msg, bool $success = true, $data = []): array {
        $response = [
            'msg' => $msg,
            'success' => $success,
            'data' => $data,
        ];

        return $response;
    }
}