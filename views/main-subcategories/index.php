<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\D;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MainSubcategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Main Subcategories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-subcategories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Main Subcategories'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Главная Категория',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->maincategory->name;
                }
            ],
            'name',
            [
                'label' => 'Подкатегории',
                'format' => 'raw',
                'value' => function ($model) {
                    $subcategories = $model->subcategories;
                    $body = '';
                    if ($subcategories) {

                        foreach ($subcategories as $subcategory) {
                            $body .= Html::a($subcategory->category->source->name . " " . $subcategory->name, $subcategory->link, ['target' => '_blank']) . "<hr>";

                        }
                        $categories = $model->categories;

                        if ($categories) {
                            foreach ($categories as $category) {
                                $body .= Html::a($category->source->name . " " . $category->name, $category->link, ['target' => '_blank']) . "<hr>";

                            }
                        }


                    }


                    return $body;

                }
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($model) {
                    $body = '';
                    if ($model->colored) {
                        $class = 'success';
                        $title = "ЦВЕТ";
                        $value = 0;
                    } else {
                        $class = 'danger';
                        $title = "ЦВЕТ";
                        $value = 1;
                    }

                    $body .= Html::button($title,
                        ['class' => "btn btn-xs set-attr-value-mainsubcategory ".$class,
                            'data' => [
                                'id' => $model->id,
                                'attr' => 'colored',
                                'value' => $value]
                        ]);


                    return $body;

                }
            ],

            // 'time:datetime',
            //   'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
