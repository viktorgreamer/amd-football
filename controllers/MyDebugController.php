<?php

namespace app\controllers;

use app\models\Processing;
use Curl;
use app\utils\D;
use yii\helpers\Html;

class MyDebugController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionParsingCategory($id)
    {

        Processing::parsingCategories(null, false, ['id_category' => $id]);

        return $this->render('index');
    }

    public function actionDetailedParsing($id)
    {

        Processing::DetailedParsing(1, ['id' => $id]);

        return $this->render('index');
    }

    public function actionTest()
    {
        $link = "https://shop.premier-football.ru/shop/butsy/";

        $page = 2;
        D::echor(" ПРОХОДИМ СТРАНИЦУ " . Html::a($page, $link."page-".$page, ['target' => '_blank']));
        $curl = new Curl\Curl();
        $curl->get("https://shop.premier-football.ru/shop/butsy/page-2/");
        $response = $curl->response;
        D::echor("ТЕЛО ".$response);
        $curl->close();
        return $this->render('index');
    }

}
