<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Clothessize */

$this->title = Yii::t('app', 'Create Clothessize');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clothessizes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clothessize-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
