<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\components\CsvParser;

class CsvController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload()
    {
        try {
            CsvParser::loadFile()->store();
            Yii::$app->session->setFlash('success', 'File uploaded');
        } catch (\Exception $error) {
            Yii::$app->session->setFlash('error', $error->getMessage());
        }
        Yii::$app->response->redirect(Url::toRoute('csv/index'));
    }
}
