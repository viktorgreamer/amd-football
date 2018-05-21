<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Footsize */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="footsize-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_brand')->textInput() ?>

    <?= $form->field($model, 'uk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'us')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cm2')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
