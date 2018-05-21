<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MainCategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Main Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Main Categories'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'label' => 'Главные подкатегории',
                'format' => 'raw',
                'value' => function ($model) {
                    $body = '';
                    $categories = $model->mainsubcategories;
                    if ($categories) {
                        // $body = '';
                        foreach ($categories as $category) {
                            $body .= $category->maincategory->name . " >> " . $category->name . "<hr>";

                        }
                    }
                    return $body;

                }
            ],
            [
                'label' => 'Категории ресурсов',
                'format' => 'raw',
                'value' => function ($model) {
                    $categories = $model->categories;
                    if ($categories) {
                        $body = '';
                        foreach ($categories as $category) {
                            $body .= Html::a($category->source->name . " >> " . $category->name, $category->link, ['target' => '_blank']) . "<hr>";

                        }
                        return $body;

                    }

                }
            ],

            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    $body = '';
                    if ($model->colored) {
                        $class = 'btn-success';
                        $title = "ЦВЕТ";
                        $value = 0;
                    } else {
                        $class = 'btn-danger';
                        $title = "ЦВЕТ";
                        $value = 1;
                    }

                    $body .= Html::button($title,
                        ['class' => "btn btn-xs set-attr-value-maincategory ".$class,
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'colored',
                                'value' => $value]
                        ]);


                    return $body;

                }
            ],

            // 'time:datetime',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
