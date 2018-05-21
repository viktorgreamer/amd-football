<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>
<!---->
<!--    --><?= $form->field($model, 'id_brand')->dropDownList([ 0 => 'Нет'] + \app\models\Brands::getMapBrands());  ?>
<!---->
<!--    --><?//= $form->field($model, 'id_in_source')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'status_source')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>
<!---->
<!--    --><?//= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
<!---->
<!--    --><?//= $form->field($model, 'disactive')->textInput() ?>
<!---->
<?= $form->field($model, 'articul')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'price_old')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'price_buy')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'lost')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'ei')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'images')->textarea(['rows' => 6]) ?>
<!---->
<!--    --><?//= $form->field($model, 'short_seo')->textarea(['rows' => 6]) ?>
<!---->
<!--    --><?//= $form->field($model, 'full_seo')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'title_page')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'url')->textarea(['rows' => 6]) ?>
<!---->
<!--    --><?//= $form->field($model, 'title_modification')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'id2')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'id_users')->textarea(['rows' => 6]) ?>
<!---->
<!--    --><?//= $form->field($model, 'name_setting')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manual_mainsubcategory')->dropDownList([ 0=> 'НЕТ'] + \app\models\MainSubcategories::getMap()) ?>

    <?= $form->field($model, 'manual_maincategory')->dropDownList([ 0=> 'НЕТ'] + \app\models\Maincategories::getMap()) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
