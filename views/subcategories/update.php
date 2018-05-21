<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Subcategories */

$this->title = Yii::t('app', 'Update Subcategories: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="subcategories-update">

    <h1><?= Html::encode($this->title ." ИЗ  ".$model->category->source->name ) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
