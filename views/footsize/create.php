<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Footsize */

$this->title = Yii::t('app', 'Create Footsize');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Footsizes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="footsize-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
