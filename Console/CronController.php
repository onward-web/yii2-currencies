<?php

namespace jarrus90\Currencies\Console;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use jarrus90\Currencies\Models\Currency;

/**
 * Currencies controller
 * @package app\commands\cron
 */
class CronController extends Controller {

    /**
     * Update current currencies rates
     */
    public function actionUpdate() {
        $rates = $this->getRates();
        $this->stdout("Started update\n", Console::FG_BLUE);
        foreach ($rates AS $key => $rate) {
            $rateItem = Currency::findOne(['code' => $key]);
            if ($rateItem) {
                $rateItem->scenario = 'update';
                $rateItem->rate = $rate;
                $rateItem->save();
            }
        }
    }

    /**
     * http://query.yahooapis.com/v1/public/yql?format=json&q=SELECT%20*%20FROM%20yahoo.finance.xchange%20WHERE%20pair%20IN%20(%27RUBUSD%27,%20%27RUBEUR%27)&env=store://datatables.org/alltableswithkeys
     * @return type
     */
    protected function getRates() {
        $default = Currency::find()->andWhere(['is_default' => 1])->asArray()->one();
        $currencies = Currency::find()->andWhere(['is_default' => 0])->asArray()->all();
        $curList = [];
        foreach ($currencies AS $currency) {
            $curList[] = "'{$default['code']}{$currency['code']}'";
        }
        $params = "SELECT * FROM yahoo.finance.xchange WHERE pair IN (" . implode(', ', $curList) . ")";
        $query = 'http://query.yahooapis.com/v1/public/yql?' . http_build_query([
                    'format' => 'json',
                    'q' => $params,
                    'env' => 'store://datatables.org/alltableswithkeys',
        ]);
        $result = file_get_contents($query);
        $rates = [];
        if ($result) {
            $data = json_decode($result, true);
            foreach ($data['query']['results']['rate'] AS $rate) {
                $rates[substr($rate['id'],3)] = $rate['Bid'];
            }
        }
        return $rates;
    }

}
