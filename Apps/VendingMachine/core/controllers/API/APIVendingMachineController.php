<?php

namespace Apps\VendingMachine\Core\Controllers\API;

use CommonF\Controllers\ControllerAbstract;
use Apps\VendingMachine\Core\Requests\Request;
use Apps\VendingMachine\Core\Services\VendingMachineHardService;
use Apps\VendingMachine\Core\Validators\VendingMachineMediumValidator;

header('Content-Type: application/json');

class APIVendingMachineController extends ControllerAbstract
{
    protected VendingMachineHardService $vendingMachineHardService;
    protected array $dbConfig;

    public function __construct(Request $request, VendingMachineHardService $vendingMachineHardService, array $dbConfig) {
        parent::__construct($request);
        $this->vendingMachineHardService = $vendingMachineHardService;
        $this->dbConfig = $dbConfig;

        $this->vendingMachineHardService->setDbConfig($dbConfig);   // Attach to repositories
    }

    public function initial() {
        $products = $this->vendingMachineHardService->getPossibleProducts();
        $coins = $this->vendingMachineHardService->getPossibleCoins();

        echo json_encode(compact('products', 'coins'));
        exit;
    }

    public function putCoin() {
        $vendingMachineMediumValidator = $this->getValidator(VendingMachineMediumValidator::class);
        $this->vendingMachineHardService->setValidator($vendingMachineMediumValidator);

        $coin = (float) $this->request->post('coin');
        $fullAmount = (float) $this->request->post('fullAmount');

        echo json_encode($this->vendingMachineHardService->putCoin($coin, $fullAmount));
        exit;
    }

    public function getChange() {
        $fullAmount = (float) $this->request->post('fullAmount');

        echo json_encode($this->vendingMachineHardService->getCoins($fullAmount));
        exit;
    }

    public function buyProduct() {
        $product = $this->request->post('product');
        $fullAmount = (float) $this->request->post('fullAmount');

        echo json_encode($this->vendingMachineHardService->buyDrink($product, $fullAmount));
        exit;
    }

    public function viewAmount() {
        $fullAmount = (float) $this->request->post('fullAmount');

        echo json_encode($this->vendingMachineHardService->viewAmount($fullAmount));
        exit;
    }
}
