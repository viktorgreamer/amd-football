<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_source',
            'id_category',
            'id_subcategory',
            'manual_maincategory',
            'manual_mainsubcategory',
            'id_maincategory',
            'id_mainsubcategory',
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


                    return $body;
                }
            ],
        ],
    ]) ?>

</div>
