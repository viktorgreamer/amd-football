<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\MainCategories;
/* @var $this yii\web\View */
/* @var $model app\models\MainSubcategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="main-subcategories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_maincategory')->dropDownList(MainCategories::getMap()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <? // echo  $form->field($model, 'time')->textInput() ?>

    <?  // echo $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
