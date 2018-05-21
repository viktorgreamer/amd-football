<?php

namespace app\controllers;

use app\models\Categories;
use app\models\Products;
use app\models\Subcategories;

class ActionsController extends MainController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSet($model_name, $id, $attr, $value)
    {

        switch ($model_name) {
            case CATEGORY:
                {
                    $model = Categories::findOne($id);

                }
                break;
            case SUBCATEGORY:
                {
                    $model = Subcategories::findOne($id);

                }
                break;

                case PRODUCT :
                {
                    $model = Products::findOne($id);

                }
                break;

        }

        $model[$attr] = $value;

        if ($model->save()) return "СМЕНИЛИ " . $attr . "  у " . $id . " модели " . $model_name . " на " . $value;
    }

}
