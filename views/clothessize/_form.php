<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Clothessize */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clothessize-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_brand')->dropDownList(\app\models\Brands::getMapBrands()) ?>

    <?= $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'growth')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
