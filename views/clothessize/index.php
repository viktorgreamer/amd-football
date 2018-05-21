<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClothessizeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Clothessizes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clothessize-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Clothessize'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            [
                'label' => 'Brand',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->brand->name;
                }
            ],
            'mark',
            'size',
            'rus',
            'age',
            'growth',
            'width',
            'height',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
