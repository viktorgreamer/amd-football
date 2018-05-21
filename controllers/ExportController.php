<?php

namespace app\controllers;
use Yii;
use yii\web\Controller;

class ExportController extends MainController
{

    //...

    /**
     * Экспорт товаров в csv файла
     */
    public function actionExportCSV()
    {
        $data = "Название товара;Артикль;Цена;Описание;Количество;Производитель\r\n";
        $model = Goods::model()->findAll();
        foreach ($model as $value) {
            $data .= $value->name .
                ';' . $value->article .
                ';' . $value->cost .
                ';' . $value->description .
                ';' . $value->count .
                ';' . $value->producer .
                "\r\n";
        }
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . date('d.m.Y') . '.csv"');
        //echo iconv('utf-8', 'windows-1251', $data); //Если вдруг в Windows будут кракозябры

    }

    //... код

}