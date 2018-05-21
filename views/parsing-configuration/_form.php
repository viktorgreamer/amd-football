<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ParsingConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parsing-configuration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'stop_page')->textInput() ?>

    <?= $form->field($model, 'id_source')->dropDownList(\app\models\Sources::getSources()) ?>
    <?= $form->field($model, 'type')->dropDownList(\app\models\ParsingConfiguration::getTYPES()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
