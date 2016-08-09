<?php

namespace jarrus90\Currencies\Controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use jarrus90\Currencies\Models\Currency;

/**
 * Class CurrencyController
 * @package app\commands\cron
 */
class CronController extends Controller {
    
    public function actionUpdate(){
        $rates = $this->getRates();
        $this->stdout("Started update\n", Console::FG_BLUE);
        foreach($rates AS $key => $rate) {
            $rateItem = Currency::findOne(['code' => $key]);
            if($rateItem) {
                $rateItem->rate = $rate;
                $rateItem->save();
            }
        }
    }
    
    protected function getRates(){
        $dataRaw = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=" . Yii::$app->params['currency.api_key']);
        $data = json_decode($dataRaw, true);
        $rates = $data['rates'];
        return $rates;
    }
}