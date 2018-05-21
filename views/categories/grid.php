<?php
use yii\grid\GridView;
use yii\helpers\Html;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model) {
        if (($model->mainsubcategory) or ($model->maincategory) or ($model->manual_category)) $class = 'success';
        else $class = 'danger';
        return ['class' => $class];

    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',

        [
            'label' => 'Время парсинга',
            'format' => 'raw',
            'value' => function ($model) {
                return Yii::$app->formatter->asRelativeTime($model->time);
            }
        ],
        [
            'label' => 'amd_category',
            'format' => 'raw',
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
                    ['class' => $class." btn-xs set-attr-value-category",
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
                    ['class' => $class." btn-xs set-attr-value-category",
                        'data' => [
                            'id' => $model->id,
                            'attr' => 'not_parsing',
                            'value' => $value]
                    ])
                ;

                if ($model->not_render) {
                    $class = "btn btn-success";
                    $value = 0;
                } else {
                    $class = "btn btn-danger";
                    $value = 1;
                }
                $body .= Html::button('not_render',
                    ['class' => $class." btn-xs set-attr-value-category",
                        'data' => [
                            'id' => $model->id,
                            'attr' => 'not_render',
                            'value' => $value]
                    ]);

                $body .= Html::button('ПЕРЕПАРСИТЬ',
                    ['class' => "btn btn-success btn-xs set-model-attr-value",
                        'data' => [
                            'id' => $model->id,
                            'model_name' => CATEGORY,
                            'attr' => TIME,
                            'value' => 0]
                    ]);
                $body .= Html::a('ПАРСИТЬ СЕЙЧАС', \yii\helpers\Url::to(['my-debug/parsing-category', 'id' => $model->id]),
                    ['class' => "btn btn-success btn-xs","target" => "_blank"]);

                $body .= Html::a('РЕНДЕРИТЬ СЕЙЧАС', \yii\helpers\Url::to(['products-render/manual-render', 'id' => 0, 'id_category' => $model->id]),
                    ['class' => "btn btn-success btn-xs","target" => "_blank"]);

                return $body;

            }
        ],
        [
            'label' => 'Цветозависимость',
            'format' => 'raw',
            'value' => function ($model) {
                $body = '';
                if ($model->colored) {
                    $class = 'btn-success';
                    $title = "ДА";
                    $value = 0;
                } else {
                    $class = 'btn-danger';
                    $title = "Нет";
                    $value = 1;
                }

                $body .= Html::button($title,
                    ['class' => "btn btn-xs set-attr-value-category ".$class,
                        'data' => [
                            'id' => $model->id,
                            'attr' => 'colored',
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
]); ?>