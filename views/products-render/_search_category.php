<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sources;
use app\models\Categories;

/* @var $this yii\web\View */
/* @var $model app\Models\CategoriesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-search">

    <?php $form = ActiveForm::begin([
        'action' => ['manual-render'],
        'method' => 'get',
    ]); ?>

    <?= Html::dropDownList('id_category', $id_category, [0 => 'нет'] + Categories::getMapCategories(),['label' => 'Категория']) ?>
    <?= Html::textInput('id', $id, ['label' => 'id']); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
