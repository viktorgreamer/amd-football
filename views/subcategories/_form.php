<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Subcategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subcategories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_category')->dropDownList(\app\models\Categories::getMapCategories(),['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'id_mainsubcategory')->dropDownList([ 0=> 'НЕТ'] + \app\models\MainSubcategories::getMap()) ?>

    <?= $form->field($model, 'id_maincategory')->dropDownList([ 0=> 'НЕТ'] + \app\models\Maincategories::getMap()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'link')->textInput(['disabled' => 'disabled']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
