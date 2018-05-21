<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Subcategories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subcategories-view">

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
            [
                'label' =>  'Категория',
                'format' => 'raw',
                'value' => function ($model) {

                    return $model->category->source->name." >>" .$model->category->name;



                }
            ],

            [
                'label' =>  'Главная подкатегория',
                'format' => 'raw',
                'value' => function ($model) {

                    return $model->mainsubcategory->name;



                }
            ],
            [
                'label' =>  'Ресурс',
                'format' => 'raw',
                'value' => function ($model) {

                    return $model->category->source->name;



                }
            ],
            'name',
            'link',
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

        ],
    ]) ?>

</div>
