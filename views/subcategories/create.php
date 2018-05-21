<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Subcategories */

$this->title = Yii::t('app', 'Create Subcategories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subcategories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
