<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\Models\ProductsRenderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products Renders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-render-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Удалить все'), ['delete-all'], ['class' => 'btn btn-danger']) ?>
    </p>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="true" aria-controls="collapseOne">
                        Поиск
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
                       aria-expanded="true" aria-controls="collapseOne">
                        СТАТИСТИКА
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <?php echo $this->render('_stats', compact('dataProvider')); ?>

                </div>
            </div>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'category',
            'subcategory',
            'name',
            // 'short_description:ntext',
            // 'description:ntext',
            //'disactive',
            'articul',
            [
                'attribute' => 'price',
                'contentOptions' => ['style' => 'width:5%;'],
            ],
            [
                'attribute' => 'price_old',
                'contentOptions' => ['style' => 'width:5%;'],
            ],

            //'price_buy',
            'lost',
            'ei',
            //'images:ntext',
            //'short_seo:ntext',
            //'full_seo',
            //'title_page',
            //'meta_keywords',
            //'meta_description',
            'url:ntext',
            //'id_users:ntext',\
            // 'id2',
            'brand',
            'sizes',
            'sizes_rus',
            'color',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
