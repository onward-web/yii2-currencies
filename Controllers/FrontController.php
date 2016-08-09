<?php

namespace jarrus90\Currencies\Controllers;

use Yii;
use jarrus90\Core\Web\Controllers\FrontController as Controller;

/**
 * LanguageController
 * Uses for updating current system language for user
 */
class CurrencyController extends Controller {

    /**
     * Update current currency.
     * @param string $code
     * @return \yii\web\Response Redirects to previous page
     */
    public function actionSet($code) {
        Yii::$app->session->set('currency', $code);
        $redirectUrl = Yii::$app->request->referrer;
        if (!$redirectUrl) {
            $redirectUrl = \yii\helpers\BaseUrl::home(true);
        }
        return $this->redirect($redirectUrl);
    }

}
