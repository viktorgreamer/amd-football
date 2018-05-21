<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\Models\ParsingConfigurationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parsing-configuration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<!--    --><?//= $form->field($model, 'id') ?>
<!---->
<!--    --><?//= $form->field($model, 'url') ?>
<!---->
<!--    --><?//= $form->field($model, 'time') ?>
<!---->
<!--    --><?//= $form->field($model, 'stop_page') ?>

    <?= $form->field($model, 'id_source')->dropDownList(\app\models\Sources::getSources()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
