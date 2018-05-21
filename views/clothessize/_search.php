<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClothessizeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clothessize-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-lg-2">
            <?= $form->field($model, 'dimension')->dropDownList(\app\models\Products::renderDimension()) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'id_brand')->dropDownList(\app\models\Brands::getMapBrands()) ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'mark') ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'size') ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'mark') ?>
        </div>
        <div class="col-lg-1">
            <?= $form->field($model, 'growth') ?>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>


    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'height') ?>



    <?php ActiveForm::end(); ?>

</div>
