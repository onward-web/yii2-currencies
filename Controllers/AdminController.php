<?php

namespace jarrus90\Currencies\Controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use jarrus90\Admin\Web\Controllers\AdminController AS BaseController;
use jarrus90\Currencies\Models\Currency;

class AdminController extends BaseController {

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

    /**
     * Update blacklist word
     * @param string $id word number
     * @return string
     */
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

    /**
     * Delete blacklist word
     * @param integer $code currency code
     * @return \yii\web\Response
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
            Yii::$app->getSession()->setFlash('danger', Yii::t('currencies', 'Currency set as defaul failed.'));
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
