<?php

namespace jarrus90\Currencies;

/**
 * Bootstrap class registers module and application component
 */
class Bootstrap implements \yii\base\BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\base\Application $app the application currently running
     */
    public function bootstrap($app) {
        /**
         * @var $module Module 
         */
        if (!$app->hasModule('currencies') || !($module = $app->getModule('currencies')) instanceof Module) {
            
        }
    }

}
