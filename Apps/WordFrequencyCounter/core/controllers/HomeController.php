<?php

namespace Apps\WordFrequencyCounter\Core\Controllers;

use CommonF\Controllers\ControllerAbstract;
use Apps\WordFrequencyCounter\Core\Requests\Request;

class HomeController extends ControllerAbstract
{
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function home() {
        return [
            'home', [
                'listWordsUrl' => $this->app->urlByRouteName('wordsFrequencyCounterList'),
                'addWordsUrl' => $this->app->urlByRouteName('wordsFrequencyCounter'),
            ]
        ];
    }

    public function notFound404() {
        return [
            'notFound404', [
                'title' => '404: Page Not Found',
                'error' => 'Oops.. Something went wrong!',
                'homeUrl' => $this->app->urlByRouteName('home'),
            ]
        ];
    }
}
