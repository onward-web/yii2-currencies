<?php

namespace jarrus90\Currencies;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule {

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'currencies';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        'set/<currency:[A-Za-z0-9_-]+>' => 'front/set'
    ];

}
