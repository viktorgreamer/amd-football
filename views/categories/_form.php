<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_source')->dropDownList(\app\models\Sources::getSources()) ?>
    <?= $form->field($model, 'id_mainsubcategory')->dropDownList([0 => 'НЕТ'] + \app\models\MainSubcategories::getMap()) ?>

    <?= $form->field($model, 'id_maincategory')->dropDownList([0 => 'НЕТ'] + \app\models\MainCategories::getMap()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
