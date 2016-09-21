<?php

namespace jarrus90\Currencies\Controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use kartik\grid\EditableColumnAction;
use jarrus90\Currencies\Models\Currency;
use jarrus90\Core\Web\Controllers\AdminController AS BaseController;

class AdminController extends BaseController {

    public function actions() {
        return ArrayHelper::merge(parent::actions(), [
            'update' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => Currency::className(),
                'findModel' => function($id, $action) {
                    $currency = Currency::findOne($id);
                    $currency->scenario = 'update';
                    return $currency;
                },
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return $model->$attribute;
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';
                },
                'showModelErrors' => true,
                'errorOptions' => ['header' => '']
            ]
        ]);
    }

    /** @inheritdoc */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin_super'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Show list of blacklisted words
     * @return string
     */
    public function actionIndex() {
        $currencyForm = Yii::createObject([
                    'class' => Currency::className(),
                    'scenario' => 'create'
        ]);
        $filterModel = Yii::createObject([
                    'class' => Currency::className(),
                    'scenario' => 'search'
        ]);
        Yii::$app->view->title = Yii::t('currencies', 'Currencies');
        return $this->render('index', [
                    'filterModel' => $filterModel,
                    'dataProvider' => $filterModel->search(Yii::$app->request->get()),
                    'currencyForm' => $currencyForm
        ]);
    }

    /**
     * Add new currency
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $currencyForm = Yii::createObject([
                    'class' => Currency::className(),
                    'scenario' => 'create'
        ]);

        if ($currencyForm->load(Yii::$app->request->post()) && $currencyForm->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('currencies', 'Currency was created.'));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('currencies', 'Currency creation failed.'));
        }
        return $this->redirect(Url::toRoute(['index']));
    }

    /*
      public function actionUpdate($code, $field) {
      $currency = $this->findCurrency($code);
      $currency->scenario = 'update';

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      if ($currency->load(Yii::$app->request->post()) && $currency->save()) {
      return ['output' => ISSET($currency->$field) ? $currency->$field : $currency->name];
      } else {
      return ['output' => '', 'message' => Yii::t('currencies', 'Currency update failed.')];
      }
      }
     */

    public function actionDelete($code) {
        $currencyObj = $this->findCurrency($code);
        if ($currencyObj->delete()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('currencies', 'Currency was deleted.'));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('currencies', 'Currency delete failed.'));
        }
        return $this->redirect(Url::toRoute(['index']));
    }

    public function actionEnable($code) {
        $currencyObj = $this->findCurrency($code);
        $currencyObj->scenario = 'update';
        $currencyObj->setAttributes([
            'is_active' => 1
        ]);
        if ($currencyObj->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('currencies', 'Currency enabled.'));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('currencies', 'Currency enabling failed.'));
        }
        return $this->redirect(Url::toRoute(['index']));
    }

    public function actionDisable($code) {
        $currencyObj = $this->findCurrency($code);
        $currencyObj->scenario = 'update';
        $currencyObj->setAttributes([
            'is_active' => 0
        ]);
        if ($currencyObj->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('currencies', 'Currency disabled.'));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('currencies', 'Currency disabling failed.'));
        }
        return $this->redirect(Url::toRoute(['index']));
    }

    /**
     * Delete blacklist word
     * @param integer $code currency code
     * @return \yii\web\Response
     */
    public function actionDefault($code) {
        $currencyObj = $this->findCurrency($code);
        $currencyObj->scenario = 'update';
        $currencyObj->setAttributes([
            'is_default' => 1,
            'rate' => 1
        ]);
        if ($currencyObj->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('currencies', 'Currency set as default.'));
        } else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('currencies', 'Currency set as default failed.'));
        }
        return $this->redirect(Url::toRoute(['index']));
    }

    /**
     * Finds the Currency model based on its code value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $code
     *
     * @return Currency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCurrency($code) {
        $currency = Currency::findOne($code);
        if ($currency === null) {
            throw new \yii\web\NotFoundHttpException('The requested currency does not exist');
        }
        return $currency;
    }

}
