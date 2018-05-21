<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $model app\models\Products */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <? //  echo  Html::a(Yii::t('app', 'Create Products'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'rowOptions' => function ($model) {

            return ['class' => \app\models\Products::getColors($model->status_source)];

        },
        'columns' => [
            //  ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\models\Products::renderStatus($model->status_source);
                }
            ],
            'id',
            [
                'label' => 'time',
                'format' => 'raw',
                'value' => function ($model) {
                    return Yii::$app->formatter->asRelativeTime($model->time);
                }
            ],
            [
                'label' => 'image',
                'format' => 'raw',
                'value' => function ($model) {
                    $images = explode(" ", $model->images);
                    return Html::a(Html::img($images[0], ['width' => '100px']), $model->source_link, ['target' => "_blank"]);

                }
            ],
            //  'manual_maincategory',
            [
                'label' => 'Ресурс/КАТ/ПОДКАТ',
                'format' => 'raw',
                'value' => function ($model) {
                    $body = '';
                    if ($model->id_category) $body .= Html::a("CAT_" . $model->id_category, ['categories/view', 'id' => $model->id_category], ['target' => '_blank']);
                    if ($model->id_subcategory) $body .= "<br>" . Html::a("SUCAT_" . $model->id_subcategory, ['subcategories/view', 'id' => $model->id_subcategory], ['target' => '_blank']);
                    $id_maincategory = $model->maincategory->id;
                    $id_mainsubcategory = $model->mainsubcategory->id;
                    if ($id_maincategory) $body .= "<br>" . Html::a("MAINCAT_" . $id_maincategory, ['main-categories/view', 'id' => $id_maincategory], ['target' => '_blank']);
                    if ($id_mainsubcategory) $body .= "<br>" . Html::a("MAINSUBCAT_" . $id_mainsubcategory, ['main-subcategories/view', 'id' => $id_mainsubcategory], ['target' => '_blank']);
                    $body .= "<br>" . $model->source->name;
                    return $body;
                }
            ],
            //  'id_in_source',

            [
                'label' => 'Brand',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->id_brand) return "<b>" . $model->brand->name . "</b>";
                    else return $model->brand_text;

                }
            ],
            'name',
            //'short_description:ntext',
            //'description:ntext',
            //'disactive',
            // 'brand_text',
            // 'color',
            'articul',
            'price',
            'price_old',
            [
                'label' => "images",
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->images) {
                        $images = explode(" ", $model->images);
                        $body = '';
                        foreach ($images as $key => $image) {
                            $body .= Html::a("Image_" . ($key + 1), $image) . "<br>";
                        }
                    }
                    return $body;
                }
            ],
            [
                'label' => "Цвет",
                'format' => 'raw',
                'value' => function ($model) {
                    return \app\components\ColorWidget::widget(['colors' => $model->color->code]);
                }
            ],
            //  'sizes',
            //'sizes_rus',
            [
                'label' => 'Размеры',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->hasSizes()) {
                        $body = Html::a('ЕСТЬ', null, [
                            'class' => "btn btn-success  btn-xs set-attr-value",
                            'data' => [
                                'attr' => 'no_sizes',
                                'id' => $model->id,
                                'value' => 1
                            ]]);
                        if ($model->sizes) $body .= "SIZES " . \app\models\Products::renderSizes(explode(";", $model->sizes));
                        if ($model->sizes_rus) $body .= "<hr>SIZES_RUS " . \app\models\Products::renderSizes(explode(";", $model->sizes_rus));

                    } else $body = Html::a('НЕТ', null, [
                        'class' => "btn btn-danger btn-xs set-attr-value",
                        'data' => [
                            'attr' => 'no_sizes',
                            'id' => $model->id,
                            'value' => 0
                        ]]);;

                    return $body;
                }
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->parsed != 0) $class_parsed = "btn btn-success";
                    else $class_parsed = "btn btn-default";
                    $body = Html::button('parsed',
                        ['class' => $class_parsed . " btn-xs set-attr-value",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'parsed',
                                'value' => 0]
                        ]);
                    if ($model->convert_sizes_status == 1) $class_converted = "btn btn-success";
                    elseif ($model->convert_sizes_status == 2) $class_converted = "btn btn-info";
                    elseif ($model->convert_sizes_status == 3) $class_converted = "btn btn-danger";
                    elseif ($model->convert_sizes_status == 4) $class_converted = "btn btn-warning";
                    else $class_converted = "btn btn-danger";

                    $body .= Html::button('convert',
                        ['class' => $class_converted . " btn-xs set-attr-value",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'convert_sizes_status',
                                'value' => 0]
                        ]);
                    if ($model->id_brand) $class_branded = "btn btn-success";
                    elseif ($model->branded) $class_branded = "btn btn-danger";
                    else $class_branded = "btn btn-default";

                    $body .= Html::button('branded',
                        ['class' => $class_branded . " btn-xs set-attr-value",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'branded',
                                'value' => 0]
                        ]);

                    if ($model->render_status != 0) $class_rendered = "btn btn-success";
                    else $class_rendered = "btn btn-default";

                    $body .= Html::button('rendered',
                        ['class' => $class_rendered . " btn-xs set-attr-value",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'render_status',
                                'value' => 0]
                        ]);

                    $body .= Html::a('ПАРСИТЬ СЕЙЧАС', \yii\helpers\Url::to(['my-debug/detailed-parsing', 'id' => $model->id]),
                        ['class' => "btn btn-success btn-xs", "target" => "_blank"]);
                    $body .= Html::a('РЕНДЕРИТЬ СЕЙЧАС', \yii\helpers\Url::to(['products-render/manual-render', 'id' => $model->id]),
                        ['class' => "btn btn-success btn-xs", "target" => "_blank"]);

                    $body .= Html::a('ОПРЕДЕЛИТЬ AMD-КАТЕГОРИЮ', \yii\helpers\Url::to(['site/reset-categories', 'id' => $model->id]),
                        ['class' => "btn btn-success btn-xs", "target" => "_blank"]);


                    return $body;
                }
            ],
            //'price_buy',
            //'lost',
            //'ei',
            //'images:ntext',
            //'short_seo:ntext',
            //'full_seo',
            //'title_page',
            //'meta_keywords',
            //'meta_description',
            //'url:ntext',
            //'title_modification',
            //'id2',
            //'id_users:ntext',
            //'name_setting',
            //'value',
            //'name_property_modification',
            //'value_property_modification',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
