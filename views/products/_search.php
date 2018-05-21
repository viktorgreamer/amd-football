<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\Models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-lg-1">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'status_source')->dropDownList([ 10 => 'ANY'] + \app\models\Products::SourceStatuses()) ?>
        </div> <div class="col-lg-2">
            <?= $form->field($model, 'dimension')->dropDownList([ 0 => 'Любая'] + \app\models\Products::renderDimension()) ?>
        </div>
        <div class="col-lg-2">

        <? echo $form->field($model, 'id_brand')->dropDownList([ 0 => 'Любой'] + \app\models\Brands::getMapBrands()) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($model, 'id_source')->dropDownList([ 0 => 'Любой'] +  \app\models\Sources::getSources()) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'id_maincategory')->dropDownList([ 0 => 'Любая'] + [\app\models\Products::MANUAL_CATEGORY => "ВРУЧНУЮ"] + [\app\models\Products::BROKEN_CATEGORY => "БЕЗ КАТЕГОРИИ"] + \app\models\MainCategories::getMap()) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'id_mainsubcategory')->dropDownList([ 0 => 'Любая'] + [\app\models\Products::MANUAL_CATEGORY => "ВРУЧНУЮ"] + [\app\models\Products::BROKEN_CATEGORY => "БЕЗ КАТЕГОРИИ"] +  \app\models\MainSubcategories::getMap($model->id_maincategory)) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'articul') ?>
          </div> <div class="col-lg-3">
            <?= $form->field($model, 'unique')->dropDownList([ 0 => 'нет', 1 => 'Да'])->label('Уникальные') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'convert_sizes_status')->dropDownList(\app\models\Products::renderSize_statuses(NUll))->label("Конвертация") ?>
        </div> <div class="col-lg-3">
            <?= $form->field($model, 'rendered')->dropDownList([ 10 => 'Любой'] + \app\models\Products::getRender_statuses())->label("Статус экспорта") ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'images')->dropDownList([ 0 => 'Any', 1 => " ДА",2 => " НЕТ" ])->label("Фото") ?>
        </div>
        <div class="col-lg-3">
        <?= $form->field($model, 'name') ?>
        </div>
        <?= $form->field($model, 'no_articul')->checkbox(['label' => 'Без артикула']) ?>
        <? //  echo  $form->field($model, 'source_status')->checkbox(['label' => 'Пропали из источника']) ?>

        СБРОСИТЬ СТАТУСЫ
        <?= Html::button('ЭКСПОРТ',
        ['class' => "btn btn-success btn-xs set-many",
        'data' => [
        'attr' => 'render_status',
        'value' => 0]
        ]);?>

        <?= Html::button('ПАРСИНГ',
        ['class' => "btn btn-success btn-xs set-many",
        'data' => [
        'attr' => 'parsed',
        'value' => 0]
        ]);?>
        <?= Html::button('CONVERT',
        ['class' => "btn btn-success btn-xs set-many",
        'data' => [
        'attr' => 'convert_sizes_status',
        'value' => 0]
        ]);?>

        <?= Html::button('BRANDING',
        ['class' => "btn btn-success btn-xs set-many",
        'data' => [
        'attr' => 'branded',
        'value' => 0]
        ]);?>
    </div>


    <?php // echo $form->field($model, 'short_description') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'disactive') ?>

    <?php // echo $form->field($model, 'articul') ?>

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

    <?php // echo $form->field($model, 'title_modification') ?>

    <?php // echo $form->field($model, 'id2') ?>

    <?php // echo $form->field($model, 'id_users') ?>

    <?php // echo $form->field($model, 'name_setting') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'name_property_modification') ?>

    <?php // echo $form->field($model, 'value_property_modification') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
