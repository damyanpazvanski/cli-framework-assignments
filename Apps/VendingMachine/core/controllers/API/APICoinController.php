<?php

namespace Apps\VendingMachine\Core\Controllers\API;

use CommonF\Controllers\ControllerAbstract;
use Apps\VendingMachine\Core\Requests\Request;
use Apps\VendingMachine\Core\Services\VendingMachineHardService;
use Apps\VendingMachine\Core\Validators\VendingMachineMediumValidator;

header('Content-Type: application/json');

class APICoinController extends ControllerAbstract
{
    protected VendingMachineHardService $vendingMachineHardService;
    protected array $dbConfig;

    public function __construct(Request $request, VendingMachineHardService $vendingMachineHardService, array $dbConfig) {
        parent::__construct($request);
        $this->vendingMachineHardService = $vendingMachineHardService;
        $this->dbConfig = $dbConfig;

        $this->vendingMachineHardService->setDbConfig($dbConfig);   // Attach to repositories
    }


    public function save() {
        $vendingMachineMediumValidator = $this->getValidator(VendingMachineMediumValidator::class);
        $this->vendingMachineHardService->setValidator($vendingMachineMediumValidator);

        $price = ((float) $this->request->post('price'));

        echo json_encode($this->vendingMachineHardService->insertCoin(compact('price')));
        exit;
    }

    public function delete() {
        $vendingMachineMediumValidator = $this->getValidator(VendingMachineMediumValidator::class);
        $this->vendingMachineHardService->setValidator($vendingMachineMediumValidator);

        $id = $this->request->post('coinId');

        echo json_encode($this->vendingMachineHardService->deleteCoin($id));
        exit;
    }
}
