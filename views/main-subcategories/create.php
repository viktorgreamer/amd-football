<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MainSubcategories */

$this->title = Yii::t('app', 'Create Main Subcategories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Main Subcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-subcategories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>