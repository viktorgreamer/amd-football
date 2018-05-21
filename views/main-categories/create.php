<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MainCategories */

$this->title = Yii::t('app', 'Create Main Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Main Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
