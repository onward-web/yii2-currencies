<?php

namespace jarrus90\Currencies\Models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord {

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('currencies', 'Currency code'),
            'name' => Yii::t('currencies', 'Currency name'),
            'symbol' => Yii::t('currencies', 'Currency symbol'),
            'rate' => Yii::t('currencies', 'Currency rate'),
        ];
    }

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return '{{%system_currency}}';
    }

    public static function getCurrency($code) {
        return self::getDb()->cache(function ($db) use ($code) {
                    return self::find()->where(['code' => $code])->asArray()->one();
                });
    }

    /**
     * Function for building array for language select
     * @return array
     */
    public static function getMenuList() {
        $list = self::getDb()->cache(function ($db) {
            return self::find()->asArray()->all();
        }, 1800);
        $result = [];
        foreach ($list AS $item) {
            $result[$item['code']] = [
                'label' => $item['symbol'],
                'url' => Url::toRoute(['/catalog/currency/set', 'code' => $item['code']])
            ];
        }
        return $result;
    }

    /**
     * Search using provided params
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params) {
        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (($this->load($params) && $this->validate())) {

        }
        return $dataProvider;
    }

}
