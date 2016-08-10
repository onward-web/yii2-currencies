<?php

namespace jarrus90\Currencies;

use Yii;
use yii\i18n\PhpMessageSource;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
/**
 * Bootstrap class registers module and application component
 */
class Bootstrap implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\base\Application $app the application currently running
     */
    public function bootstrap($app) {
        /**
         * @var $module Module 
         */
        if ($app->hasModule('currencies') && ($module = $app->getModule('currencies')) instanceof Module) {
            if (!isset($app->get('i18n')->translations['currencies*'])) {
                $app->get('i18n')->translations['currencies*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }
            if (!$app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'jarrus90\Currencies\Controllers';
                $rule = Yii::createObject([
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => $module->urlPrefix,
                    'routePrefix' => 'currencies',
                    'rules' => $module->urlRules,
                ]);
                $app->urlManager->addRules([$rule], false);
                $app->params['admin']['menu']['currencies'] = [
                    'label' => Yii::t('currencies', 'Currencies'),
                    'position' => 91,
                    'icon' => '<i class="fa fa-fw fa-usd"></i>',
                    'url' => '/currencies/admin/index'
                ];
            } else {
                if(empty($app->controllerMap['currencies'])) {
                    $app->controllerMap['currencies'] = [
                        'class' => \jarrus90\Currencies\Console\CronController::className()
                    ];
                }
            }
            $app->params['yii.migrations'][] = '@jarrus90/Currencies/migrations/';
        }
    }

}
