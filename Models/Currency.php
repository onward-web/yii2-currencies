<?php

namespace jarrus90\Currencies\Models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord {


    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'required' => [['code', 'rate', 'decimal_place'], 'required', 'on' => ['create', 'update']],
            'safe' => [['symbol', 'name'], 'safe'],
            'boolean' => [['is_default', 'is_active'], 'boolean'],
            'safeSearch' => [['code', 'rate', 'is_default', 'is_active'], 'safe', 'on' => ['search']],
        ];
    }

    public function scenarios() {
        return [
            'create' => ['code', 'symbol', 'name', 'rate', 'is_default', 'is_active'],
            'update' => ['code', 'symbol', 'name', 'rate', 'is_default', 'is_active'],
            'search' => ['code', 'symbol', 'name', 'rate', 'is_default', 'is_active'],
        ];
    }
    
    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('currencies', 'Code'),
            'name' => Yii::t('currencies', 'Name'),
            'symbol' => Yii::t('currencies', 'Symbol'),
            'rate' => Yii::t('currencies', 'Rate'),
            'decimal_place' => Yii::t('currencies', 'Decimal place'),
            'is_default' => Yii::t('currencies', 'Default'),
            'is_active' => Yii::t('currencies', 'Active'),
        ];
    }

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return '{{%currency}}';
    }

    public static function listMap() {
        return ArrayHelper::map(static::find()->asArray()->all(), 'code', function($model) {
            return $model['name'] . ($model['is_active'] ? '' : ' (' . Yii::t('currencies', 'disabled') . ')');
        });
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

    public function afterSave($insert, $changedAttributes) {
        if($this->is_default) {
            self::updateAll(['is_default' => false], ['NOT', ['code' => $this->code]]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete() {
        $currentDefault = self::findOne(['is_default' => true]);
        if(!$currentDefault) {
            $newDefault = self::findOne(['code' => 'USD']);
            $newDefault->scenario = 'update';
            $newDefault->setAttributes([
                'is_default' => 1,
                'rate' => 1
            ]);
            $newDefault->save();
        }
        return parent::afterDelete();
    }

    public static function getCurrency($code) {
        return self::getDb()->cache(function ($db) use ($code) {
                    return self::find()->where(['code' => $code])->asArray()->one();
                });
    }

    public static function convert($amount, $currency) {
        $curr = self::getCurrency($currency);
        return $amount / $curr['rate'];
    }
}
