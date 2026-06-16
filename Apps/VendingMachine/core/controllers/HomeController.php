<?php

namespace Apps\VendingMachine\Core\Controllers;

use CommonF\Controllers\ControllerAbstract;
use Apps\VendingMachine\Core\Requests\Request;

class HomeController extends ControllerAbstract
{
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function home() {
        return [
            'home', [
                'showVendingMachine' => $this->app->urlByRouteName('showVendingMachine'),
            ]
        ];
    }

    public function notFound404() {
        header('HTTP/1.0 404 Not Found', true, 404);

        return [
            'notFound404', [
                'title' => '404: Page Not Found',
                'error' => 'Oops.. Something went wrong!',
                'homeUrl' => $this->app->urlByRouteName('home'),
            ]
        ];
    }
}
