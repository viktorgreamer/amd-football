<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\utils\R;
use app\models\Processing;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    $counts_moderate_coloring = Processing::getREADY(Processing::COLORING)->count();
    $counts_moderate_categories = Processing::getREADY(Processing::MODERATE_CATEGORIES)->count();
    $counts_convert = Processing::getREADY(Processing::CONVERTING)->count();
    $counts_parsing = Processing::getREADY(Processing::PARSING)->count();
    $counts_parsing_category = Processing::getREADYCategories()->count();
    $counts_parsing_Rendering = Processing::getREADY(Processing::RENDERING)->count();
    NavBar::begin([
        'brandLabel' => "amd-sport",
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right blue'],
        'items' => [
            ['label' => 'Главная База', 'url' => ['/products']],
             [
                'label' => 'Структура',
                'items' => [
                    ['label' => 'Категории AMD-SPORT', 'url' => ['/main-categories']],
                    ['label' => 'Подкатегории AMD-SPORT', 'url' => ['/main-subcategories']],
                    ['label' => 'Brands', 'url' => ['/brands']],
                    ['label' => 'Категории', 'url' => ['/categories/index']],
                    ['label' => 'Подкатегории', 'url' => ['/subcategories/index']],
                    ['label' => 'Источники', 'url' => ['/sources/index']],
                ],
            ],

            [
                'label' => 'Данные',
                'items' => [
                    ['label' => 'Размеры обуви', 'url' => ['/footsize']],
                    ['label' => 'Размеры одежды', 'url' => ['/clothessize']],
                    ['label' => 'Цвета', 'url' => ['/colors']],
                ],
            ],

            [
                'label' => R::renderAlertCount('Export', $counts_parsing_Rendering),
                'items' => [
                    ['label' => 'Просмотр', 'url' => ['/products-render/index']],
                    ['label' => R::renderAlertCount('Сформировать базу на экспорт',$counts_parsing_Rendering), 'url' => ['/products-render/create-render']],
                    ['label' => 'Ручной рендеринг', 'url' => ['/products-render/manual-render']],
                    ['label' => 'Save to Csv', 'url' => ['/products-render/save-csv']],
                    ['label' => " Просмотр файлов экспорта", 'url' => ['/products-render/view-files']],
                    ['label' => 'НАЧАТЬ ЭКСПОРТ ЗАНОВО', 'url' => ['/products-render/reset']],
                ],
            ],
            [
                'label' => R::renderAlertCount("Модерация", $counts_moderate_categories +$counts_moderate_coloring),
                'items' => [
                    ['label' => R::renderAlertCount("Модерация Категорий", $counts_moderate_categories) , 'url' => ['/site/moderate-manual-category']],
                    ['label' => R::renderAlertCount("Модерация цветов",$counts_moderate_coloring), 'url' => ['/site/coloring']],

                ],
            ],
            [
                'label' => R::renderAlertCount('Parsing', $counts_convert+$counts_parsing + $counts_parsing_category),
                'items' => [
                    ['label' => 'ГЛАВНЫЙ СКРИПТ', 'url' => ['/site/main']],
                    ['label' => 'Модерация Категорий', 'url' => ['/site/moderate-manual-category']],
                    ['label' => R::renderAlertCount('Парсинг наличия товаров', $counts_parsing_category), 'url' => ['/site/parsing-main']],
                    ['label' => R::renderAlertCount('Парсинг свойств', $counts_parsing), 'url' => ['/site/detailed-parsing']],
                    ['label' => 'Сбрасить Парсинг свойств', 'url' => ['/site/reset']],
                    ['label' => R::renderAlertCount('Конвертация размеров', $counts_convert), 'url' => ['/site/convert-sizes']],
                    ['label' => 'Сбросить конвертацию', 'url' => ['/site/reset-convert']],
                    ['label' => 'Брендирование', 'url' => ['/site/branding']],
                ],
            ],
            Yii::$app->user->isGuest ? (
            ['label' => 'Войти', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= \app\utils\D::printr() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
