<?php

namespace jarrus90\Currencies\Widgets;

use Yii;
use jarrus90\Currencies\Models\Currency;

class CurrencyConverter {

    public static function format($value, $showSymbol = true) {
        $currency = self::getCurrency();
        return sprintf("%.2f", $value * $currency['rate']) . ($showSymbol ? ' ' . $currency['symbol'] : '');
    }

    protected static function getCurrency() {
        if (!Yii::$app->params['currency']) {
            $currency = Currency::getCurrency(['code' => Yii::$app->session->get('currency', 'USD')]);
            if ($currency) {
                Yii::$app->params['currency'] = $currency;
            } else {
                Yii::$app->params['currency'] = [
                    'rate' => 1.00,
                    'symbol' => '$',
                    'code' => 'USD'
                ];
            }
        }
        return Yii::$app->params['currency'];
    }

}
