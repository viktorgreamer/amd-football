<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductsRender */

$this->title = Yii::t('app', 'Create Products Render');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products Renders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-render-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
