<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsRender */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products Renders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-render-view">

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
            'category',
            'subcategory',
            'name',
            'short_description:ntext',
            'description:ntext',
            'disactive',
            'articul',
            'price',
            'price_old',
            'price_buy',
            'lost',
            'ei',
            'images:ntext',
            'short_seo:ntext',
            'full_seo',
            'title_page',
            'meta_keywords',
            'meta_description',
            'url:ntext',
            'id_users:ntext',
            'brand',
            'sizes',
            'sizes_rus',
            'color',
        ],
    ]) ?>

</div>
