<?php

use kartik\grid\GridView;
use yii\helpers\Html;


echo GridView::widget([
    'moduleId' => 'gridviewKrajee', // change the module identifier to use the respective module's settings
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'id',
            'width' => '50px',

        ],

        [
            'attribute' => 'Время парсинга',
            'format' => 'raw',
            'width' => '15px',
            'value' => function ($model) {
                return Yii::$app->formatter->asRelativeTime($model->time);
            }
        ],
        [
            'label' => 'amd_category',
            'format' => 'raw',
            'width' => '150px',
            'value' => function ($model) {
                return $model->maincategory->name;
            }
        ], [
            'label' => 'amd_subcategory',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->mainsubcategory->name;
            }
        ],
        'name',
        [
            'label' => 'Подкатегории',
            'format' => 'raw',
            'value' => function ($model) {
                $subcategories = $model->subcategories;
                if ($subcategories) {
                    $body = '';
                    foreach ($subcategories as $subcategory) {
                        $body .= Html::a($subcategory->name, $subcategory->link, ['target' => '_blank']) . "<br>";

                    }
                    return $body;

                } else return "НЕТ";
            }
        ],

        [
            'label' => 'Ресурс',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->source->name;
            }
        ], [
            'label' => 'link',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a("link", $model->link, ['target' => "_blank"]);
            }
        ], [
            'label' => 'Действия',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->manual_category) {
                    $class = "btn btn-success";
                    $value = 0;
                } else {
                    $class = "btn btn-danger";
                    $value = 1;
                }
                $body .= Html::button('MAN_CAT',
                    ['class' => $class . " btn-xs set-attr-value-category",
                        'data' => [
                            'id' => $model->id,
                            'attr' => 'manual_category',
                            'value' => $value]
                    ]);

                if ($model->not_parsing) {
                    $class = "btn btn-success";
                    $value = 0;
                } else {
                    $class = "btn btn-danger";
                    $value = 1;
                }
                $body .= Html::button('not_parsing',
                    ['class' => $class . " btn-xs set-attr-value-category",
                        'data' => [
                            'id' => $model->id,
                            'attr' => 'not_parsing',
                            'value' => $value]
                    ]);

                if ($model->not_render) {
                    $class = "btn btn-success";
                    $value = 0;
                } else {
                    $class = "btn btn-danger";
                    $value = 1;
                }
                $body .= Html::button('not_render',
                    ['class' => $class . " btn-xs set-attr-value-category",
                        'data' => [
                            'id' => $model->id,
                            'attr' => 'not_render',
                            'value' => $value]
                    ]);

                return $body;

            }
        ],
        [
            'label' => 'ТИП РАЗМЕРА',
            'format' => 'raw',
            'value' => function ($model) {
                $body = '';
                foreach (\app\models\Products::getSize_type() as $key => $type_size) {
                    if ($model->size_type == $key) $class = "btn-primary";
                    else $class = "btn-default";
                    $body .= "<button type=\"button\" class=\"btn " . $class . " btn-xs set-size-type-category\" data-id_category=\"" . $model->id . "\" data-size_type=\"" . $key . "\">" . $type_size . "</button>";
                }

                return $body;
            }
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
    // other widget settings
]);