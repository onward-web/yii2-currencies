<?php

namespace jarrus90\Currencies\Controllers;

use Yii;
use yii\helpers\Url;
use jarrus90\Currencies\Models\Currency;
use jarrus90\Admin\Web\Controllers\AdminCrudAbstract;

/**
 * CurrencyController manages currency editing
 */
class AdminController extends AdminCrudAbstract {

    protected $modelClass = 'jarrus90\Currencies\Models\Currency';
    protected $formClass = 'jarrus90\Currencies\Models\Currency';
    protected $searchClass = 'jarrus90\Currencies\Models\Currency';

    protected function getItem($id) {

    }

}
