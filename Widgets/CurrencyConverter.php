<?php

namespace jarrus90\Currencies\Widgets;

use Yii;
use yii\helpers\Html;
use jarrus90\Currencies\Models\Currency;

class CurrencyConverter extends yii\base\Component {
    
    public function __construct($registry) {
        parent::__construct();
        
    }

    public static function format($value, $showSymbol = true) {
        
        
        $settings = Yii::$app->settings;  
        $decimal_point = $settings->get('currency.decimal_point'); // Получаем разделителя целой и дробной части
        $thousand_point = $settings->get('currency.thousand_point'); // Получаем разделитея розрядов
        
        
        // Получаем текущую валюту        
        $currency = self::getCurrency(Yii::$app->session->get('currency', 'USD'));        
        
        $amount = $value * $currency['rate'];
		
    	$amount = round($amount, (int)$currency['decimal_place']);
        
        $string = number_format($amount, (int)$currency['decimal_place'], $decimal_point, $thousand_point);
        
        $out = Html::beginTag('div', ['class' => 'currency-price']);
        $out .= $string;
        $out .=  Html::endTag('div');
        
        
        if($showSymbol){
            $out .= Html::tag('div', $currency['symbol'], ['class' => 'currency-value']);
        }        
        
        return $out;
        
    }
    
    
    
    public static function convert($value, $to, $from = false){
        /*
        if($from == false){
            $from = Yii::$app->session->get('currency', 'USD');
        }
        */
        
        
        
    }

    protected static function getCurrency($code) {
        
        if (!Yii::$app->params['currency'][$code]) {
            return $currency = Currency::getCurrency(['code' => $code]);
           
            
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
        
        return Yii::$app->params['currency'][$code];
    }

}
