<?php

namespace Apps\VendingMachine\Core\Controllers;

use CommonF\Controllers\ControllerAbstract;
use Apps\VendingMachine\Core\Requests\Request;


class VendingMachineController extends ControllerAbstract
{
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function show() {
        return [
            'vending_machine_show', []
        ];
    }
}
