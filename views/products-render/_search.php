<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\ProductsRender;
/* @var $this yii\web\View */
/* @var $model app\Models\ProductsRenderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-render-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<!--    --><?//= $form->field($model, 'id') ?>
<!---->
<!--    --><?= $form->field($model, 'category')->dropDownList( [ 0 => 'ANY'] + ArrayHelper::map(ProductsRender::find()->select('category')->distinct()->all(), 'category','category')) ?>
<!---->
    <?= $form->field($model, 'subcategory')->dropDownList([ 0 => 'ANY'] + ArrayHelper::map(ProductsRender::find()->select('subcategory')->distinct()->all(), 'subcategory','subcategory'));  ?>
<!---->

    <?= $form->field($model, 'has_nosubcategory')->checkbox(['label' => 'БЕЗ ПОДКАТЕГОРИИ']); ?>
    <?= $form->field($model, 'has_nocategory')->checkbox(['label' => 'БЕЗ КАТЕГОРИИ']); ?>
<!--    --><?= $form->field($model, 'name') ?>
<!---->
<!--    --><?//= $form->field($model, 'short_description') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'disactive') ?>

    <?php  echo $form->field($model, 'articul') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'price_old') ?>

    <?php // echo $form->field($model, 'price_buy') ?>

    <?php // echo $form->field($model, 'lost') ?>

    <?php // echo $form->field($model, 'ei') ?>

    <?php // echo $form->field($model, 'images') ?>

    <?php // echo $form->field($model, 'short_seo') ?>

    <?php // echo $form->field($model, 'full_seo') ?>

    <?php // echo $form->field($model, 'title_page') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'id_users') ?>

    <?php  echo $form->field($model, 'brand') ?>

    <?php // echo $form->field($model, 'sizes') ?>

    <?php // echo $form->field($model, 'sizes_rus') ?>

    <?php // echo $form->field($model, 'color') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
