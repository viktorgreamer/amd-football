<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\Models\SubcategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Subcategories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subcategories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Subcategories'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'amd_subcategory',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->mainsubcategory->maincategory->name . " >> " .$model->mainsubcategory->name;
                }
            ],
            [
                'label' => 'Категория',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->category->name;
                }
            ],
            'name',
            [
                'label' => 'link',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a("link", $model->link, ['target' => "_blank"]);
                }
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->manual_category) $value = 0; else $value = 1;
                    $body .= Html::button('MAN_CAT',
                        ['class' => "btn-xs set-attr-value-subcategory",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'manual_category',
                                'value' => $value]
                        ]);

                    if ($model->not_parsing) $value = 0; else $value = 1;
                    $body .= Html::button('not_parsing',
                        ['class' => "btn-xs set-attr-value-subcategory",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'manual_category',
                                'value' => $value]
                        ])
                    ;
                    if ($model->not_render) $value = 0; else $value = 1;
                    $body .= Html::button('not_render',
                        ['class' => "btn-xs set-attr-value-subcategory",
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'not_render',
                                'value' => $value]
                        ]);

                    return $body;

                }
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    $body = '';
                    if ($model->colored) {
                        $class = 'success';
                        $title = "ЦВЕТ";
                        $value = 0;
                    } else {
                        $class = 'danger';
                        $title = "ЦВЕТ";
                        $value = 1;
                    }

                    $body .= Html::button($title,
                        ['class' => "btn btn-xs set-attr-value-subcategory ".$class,
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
                    foreach (\app\models\Products::getSize_type() as $key=>$type_size) {
                        if ($model->size_type == $key) $class = "btn-primary";
                        else $class = "btn-default";
                        $body .= "<button type=\"button\" class=\"btn ".$class." btn-sm set-size-type-subcategory\" data-id_subcategory=\"".$model->id."\" data-size_type=\"".$key."\">".$type_size."</button>";
                    }

                    return $body;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
