<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Categories;
use app\models\Sources;
/* @var $this yii\web\View */
/* @var $model app\Models\SubcategoriesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subcategories-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_source')->dropDownList(Sources::getSources()) ?>
    <?= $form->field($model, 'id_category')->dropDownList([ 0=> 'Любая'] + Categories::getMapCategories($model->id_source)) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
