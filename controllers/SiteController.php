<?php

namespace app\controllers;

use app\models\Brands;
use app\models\Categories;
use app\models\Clothessize;
use app\models\Curling;
use app\models\Footsize;
use app\models\MainCategories;
use app\models\MainSubcategories;
use app\models\Parsing;
use app\models\ParsingConfiguration;
use app\models\Processing;
use app\models\Products;
use app\models\ProductsRender;
use app\models\Sources;
use app\models\Subcategories;
use app\utils\D;
use app\utils\P;
use Codeception\Exception\ElementNotFound;
use phpDocumentor\Reflection\DocBlock\Tags\Source;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use ruskid\csvimporter\MultipleImportStrategy;
use app\models\User;

class SiteController extends MainController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionAddUser()
    {
        $user = new User();
        $user->username = 'Nikolay';
        $user->email = 'mkjj@gmail.com';
        $user->setPassword('1234');
        $user->generateAuthKey();
        if ($user->save()) {
            echo 'good';
        }

        $user = new User();
        $user->username = 'Andrey';
        $user->email = 'sdfmkjj@gmail.com';
        $user->setPassword('1234');
        $user->generateAuthKey();
        if ($user->save()) {
            echo 'good';
        }
    }


    public function actionMain($autorefresh = 0)
    {

        D::echor(Html::a('ЗАЦИКЛИТЬ', ['site/main', 'autorefresh' => 1]));


        if (!Processing::parsingCategories()) {
            if (!Processing::DetailedParsing(20)) {
                if (!Processing::Branding()) {
                    if (!Processing::ConvertSizes(100)) {
                        if (!Processing::Render()) {
                            D::info(" ОБРАБОТКА ЗАКОНЧЕНА");
                        }


                    };
                }
            };
        };


        return $this->render('index');
    }

    public function actionExport()
    {
        $product_render = new ProductsRender();

        $Products = ProductsRender::find()->where(['<>', 'id', 0])->asArray()->limit(110)->all();

        //echo iconv('utf-8', 'windows-1251', $data); //Если вдруг в Windows будут кракозябры


        $fp = fopen('file.csv', 'w');
        fputcsv($fp, $product_render->attributeLabels(), ",");

        foreach ($Products as $fields) {
            fputcsv($fp, $fields, ",");
        }

        fclose($fp);
        return $this->render('index');

    }

    public function actionFile()
    {

        $files = scandir("export/");
        array_shift($files);
        array_shift($files);
        if ($files) {
            foreach ($files as $file) {
                D::echor("<br>" . Html::a($file, "/web/export/" . $file));
            }

        }
        D::dump($files);

        return $this->render('index', compact('message'));

    }


    public function actionTestSearchMainCategories()
    {

        $product = Products::findOne(4149);

        D::info(" name = " . $product->name);
        $maincategory = $product->getMaincategory();
        D::info(" maincategory_name = " . $maincategory->name);

        $mainsubcategory = $product->getMainsubcategory();
        D::info(" mainsubcategory_name = " . $mainsubcategory->name);

        return $this->render('index', compact('message'));


    }


    public function actionTestSportDepo()
    {
        Processing::DetailedParsing(1, ['id_source' => 1, 'not_save' => true]);
        return $this->render('index', compact('message'));


    }


    public function actionModerateManualCategory()
    {

        Processing::ModerateCategories(10);
        return $this->render('index', compact('message'));

    }

    public function action2kSport()
    {
        /// $parsing = new Parsing();
        //  $parsing->collectCategoriesAndSubcategories(3,true);

        Processing::parsingCategories(3, true, ['id_category' => [138, 139, 140]]);
        Processing::Branding();
        Processing::DetailedParsing(10, ['id_source' => 3, 'id_category' => [138, 139, 140]]);
        Processing::ConvertSizes(5, ['id_source' => 3]);


        return $this->render('index', compact('message'));

    }


    public function actionDebug()
    {
        $query = Products::find()
            // ->where(['id_category' => 3])
            ->where(['<>', 'debug_status', 6]);
        D::echor(" LOST " . $query->count());
        $products = $query->limit(100)->all();

        foreach ($products as $product) {
            $product->images = preg_replace("/;/", " ", $product->images);
            $product->debug_status = 6;
            $product->save();
        }

        return $this->render('index', compact('message'));

    }

    public function actionBranding()
    {
        $patterns = [];
        foreach (Brands::find()->where(['<>', 'id', 0])->all() as $brand) {
            $pattern = "/" . preg_replace("/ /", "\s", $brand->name) . "/i";
            //  D::echor("<br>brand_pattern = ".$pattern);
            $patterns[$brand->id] = $pattern;
        }
        //  D::dump($patterns);
        $count = Products::find()->where(['branded' => 0])->count();
        D::echor(" lost = " . $count);
        $products = Products::find()->where(['branded' => 0])->limit(100)->all();
        foreach ($products as $product) {
            foreach ($patterns as $key => $pattern) {
                D::echor("<br> SEARCHING " . $pattern . " IN " . $product->brand_text);
                if (preg_match($pattern, $product->brand_text)) {
                    $product->id_brand = $key;
                    D::echor("<br>brand =" . $product->brand_text . " id_brand  = " . $product->id_brand . " getBrand = " . $product->brand->name);
                    break;
                } else {

                    D::echor('<br> NO MATCHES');
                }

            }
            $product->branded = 1;
            $product->save();

        }

        return $this->render('index', compact('message'));

    }

    public function actionColoring()
    {
        return $this->render('color');
    }

    public function actionResetCategories($id = null)
    {

        Processing::ResetCategories(['id' => $id]);
        return $this->render('index', compact('message'));
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPremier()
    {
        $id_source = 2; // премьер спорт
        $source = Sources::findOne($id_source); // премьер спорт

        $response = Curling::getting("https://shop.premier-football.ru/");
        $pq_page = \phpQuery::newDocument($response);
        $pq_categories = $pq_page->find("ul.catalog-filter__list > li");
        foreach ($pq_categories as $pq_category) {

            $div_category = \phpQuery::pq($pq_category);
            $name_category = $div_category->find("a")->eq(0)->text();
            $new_category = new Categories();
            $new_category->id_source = $id_source;
            $new_category->name = $name_category;
            $new_category->link = $source->url . "" . $div_category->find("a")->eq(0)->attr('href');
            if (!$new_category->isExisted()) {
                D::echor("<br> СОЗДАЕМ НОВУЮ КАТЕГОРИЮ<br>");
                if (!$new_category->save()) D::dump($new_category->getErrors());

            } else {
                D::echor("СУЩЕСТВУЮЩАЯ КАТЕГОРИЯ");
                $new_category = Categories::find()->where(['id_source' => $id_source])->andWhere(['link' => $new_category->link])->one();
            }
            D::echor("СВОЙСТВА КАТЕГОРИИ");
            D::renderProperties($new_category, ['id_source', 'name', 'link']);
            $pq_subcategories = $div_category->find("ul.dropdown-category > li");
            $count_SUB_CATEGORIES = count($pq_subcategories);
            D::echor("<br>COUNT SUBCATEGORIES = " . $count_SUB_CATEGORIES);
            if (!$new_category->save()) D::dump($new_category->getErrors());
            // если есть подкатегории то
            if ($count_SUB_CATEGORIES) {
                foreach ($pq_subcategories as $pq_subcategory) {
                    $new_subcategory = new Subcategories();
                    $div_subcategory = \phpQuery::pq($pq_subcategory);
                    $name_subcategory = $div_subcategory->find("a")->text();
                    $new_subcategory->link = $source->url . "" . $div_subcategory->find("a")->eq(0)->attr('href');

                    $new_subcategory->id_category = $new_category->id;
                    $new_subcategory->name = $name_subcategory;
                    D::echor("СВОЙСТВА ПОДКАТЕГОРИИ");
                    D::renderProperties($new_subcategory, ['id_category', 'name', 'link']);

                    if (!$new_subcategory->isExisted()) {
                        D::echor("СОЗДАЕМ НОВУЮ ПОДКАТЕГОРИЮ");
                        // if (!$new_subcategory->save()) D::dump($new_subcategory->getErrors());

                    } else {
                        D::echor("СУЩЕСТВУЮЩАЯ ПОДКАТЕГОРИЯ");
                        $new_subcategory = Subcategories::find()->andWhere(['link' => $new_subcategory->link])->one();
                    }

                    D::echor("ПОДКАТЕГОРИЯ---->>" . $name_subcategory);
                }
            }


        }


        //D::echor($pq_category);
        return $this->render('index');
    }

    public function actionIndex1()
    {
        $file = fopen('uploads/export_football_csv1.csv', 'r');
        while (($fileop = fgetcsv($file, 1000, ";")) !== false) {
            $product = new  Products();
            $i = 1;
            foreach ($product->attributeLabels() as $key => $attributeLabel) {
                // echo "<br>".$key;
                if ($key == 'price') $product[$key] = preg_replace("/,/", ".", $fileop[$i]);
                elseif ($key == 'id') 1;
                else {
                    $product[$key] = $fileop[$i];

                }
                //  if ($key == 'lost') echo  "<br> lost =".$fileop[$i];
                $i++;

            }

            if (!$product->validate()) {

                var_dump($product->getErrors());
            }
            if (!$product->save()) {
                echo "<pre>";
                var_dump($product->getErrors());
                echo "<pre>";

            }

        }

        // return $this->render('index');
    }

    public function actionIndex2()
    {
        $importer = new CSVImporter;

        //Will read CSV file
        $importer->setData(new CSVReader([
            'filename' => "uploads/export_football_csv2.csv",
            'fgetcsvOptions' => [
                'delimiter' => ';'
            ]
        ]));
        echo "<pre>";
        //  var_dump($importer->getData());
        foreach ($importer->getData() as $fileop) {
            $product = new  Products();
            $i = 1;
            foreach ($product->attributeLabels() as $key => $attributeLabel) {
                // echo "<br>".$key;
                if ($key == 'price') $product[$key] = preg_replace("/,/", ".", $fileop[$i - 1]);
                else if ($key == 'id') echo "";
                else {
                    $product[$key] = $fileop[$i - 1];

                }
                //  if ($key == 'lost') echo  "<br> lost =".$fileop[$i];
                $i++;

            }

            if (!$product->validate()) {

                var_dump($product->getErrors());
            }
            if (!$product->save()) {
                echo "<pre>";
                var_dump($product->getErrors());
                echo "<pre>";

            }

        }


        return $this->render('index');
    }


    public function actionCsvImportFootSize()
    {
        $importer = new CSVImporter;

        //Will read CSV file
        $importer->setData(new CSVReader([
            'filename' => "uploads/2k_sport.csv",
            'fgetcsvOptions' => [
                'delimiter' => ';'
            ]
        ]));

        // Транспонируем массив
        $sizes = $importer->getData();
        array_unshift($sizes, null);
        $sizes = call_user_func_array("array_map", $sizes);

        foreach ($sizes as $csv_size) {
            $size = new Footsize();
            $size->id_brand = 13;
            $size->eur = $csv_size[0];
            $size->rus = $csv_size[1];
            $size->uk = $csv_size[2];
            $size->us = $csv_size[3];
            $size->cm = $csv_size[4];
            $size->cm2 = $csv_size[5];
            $size->jp = $csv_size[6];
            //  D::dump($size);
            $properties = ['eur', 'rus', 'uk', 'us', 'cm', 'cm2', 'jp'];
            foreach ($properties as $property) {
                {
                    D::echor("<br> " . $property . " = <b>" . $size[$property] . "</b>");

                }

            }
            // $size->save();

        }


        return $this->render('index');
    }

    public function actionCsvImportClothesSize()
    {
        $importer = new CSVImporter;

        //Will read CSV file
        $importer->setData(new CSVReader([
            'filename' => "uploads/clothes/legea2.csv",
            'fgetcsvOptions' => [
                'delimiter' => ';'
            ]
        ]));

        // Транспонируем массив
        $sizes = $importer->getData();
        array_unshift($sizes, null);
        $sizes = call_user_func_array("array_map", $sizes);
        D::echor("<br> ВСЕГО РАЗМЕРОВ  " . count($sizes));

        foreach ($sizes as $csv_size) {
            $size = new Clothessize();
            $size->id_brand = 15;
            $size->dimension = 2; //  детская
            $size->mark = $csv_size[0];
            $size->size = $csv_size[1];
            $size->growth = $csv_size[2];
            $size->age = $csv_size[3];
            $size->rus = $csv_size[4];
            $size->width = $csv_size[4];
            $size->height = $csv_size[6];

            //  D::dump($size);
            D::echor("<br> BRAND IS " . $size->brand->name);
            D::echor("<br> РАЗМЕРНОСТЬ  " . Products::renderDimension($size->dimension));
            $properties = ['mark', 'size', 'rus', 'age', 'growth', 'height', 'width'];
            foreach ($properties as $property) {
                {
                    D::echor("<br> " . $property . " = <b>" . $size[$property] . "</b>");

                }

            }
            D::echor("<hr>");
            // if (!$size->save()) D::dump($size->getErrors());

        }


        return $this->render('index');
    }

    public function actionParsing()
    {

        $file = file_get_contents("sources/sportdepo/categories.html");
        $pq = \phpQuery::newDocument($file);
        $categories = $pq->find(".catalog");
        foreach ($categories as $category) {
            D::echor("<hr>");
            $category_html = \phpQuery::pq($category)->find('a');
            $category_name = $category_html->attr('title');
            $category_link = $category_html->attr('href');
            D::echor("<br>CATEGORIES =" . Html::a($category_name, $category_link, ['target' => '_blank']));
            $category_new = new Categories();
            $category_new->name = $category_name;
            $category_new->id_source = 1;
            $category_new->link = $category_link;
            $source = Sources::findOne(1);

            if (!$category_new->isExisted()) 1; // $category_new->save();
            else {
                D::echor("<br> Данная категория уже существует");
                $sub_categories_html = \phpQuery::pq($category)->find('h3');
                $id_category = Categories::find()->where(['link' => $category_new->link])->one();
                foreach ($sub_categories_html as $sub_category_html) {
                    $sub_category_html = \phpQuery::pq($sub_category_html)->find('a');
                    $sub_category = new Subcategories();
                    $sub_category->id_category = $id_category;
                    $sub_category->link = $sub_category->link . "" . $sub_category_html->attr('href');
                    $sub_category->name = $sub_category_html->attr('title');
                    D::echor("<br>SUBCATEGORY =" . Html::a($sub_category->name, $sub_category->link, ['target' => '_blank']));
                    if (!$sub_category->isExisted()) $sub_category->save();
                    else {
                        D::echor("<br> Данная кодкатегория уже существует");
                    }

                }
            }
        }

        return $this->render('debug');


    }


    public function actionParsingMain($autorefresh = false)
    {
        D::echor(Html::a('ЗАЦИКЛИТЬ', ['site/parsing-main', 'autorefresh' => true]));
        $query = Categories::find()
            // ->where(['id_source' => 2])
            //   ->andwhere(['id' => 84]);
            ->where(['<', 'time', time() - 24 * 60 * 60])
            ->orWhere(['IS', 'time', NULL]);
        D::echor("<br> ЕСТЬ " . $query->count() . " КАТЕГОРИИ");

        $unparsed_categories = $query->orderBy(new Expression('rand()'))->limit(1)->all();
        foreach ($unparsed_categories as $category) {
            $subcategories = $category->subcategories;
            if ($subcategories) {
                D::echor("<br> ЕСТЬ " . count($subcategories) . " ПОДКАТЕГОРИИИ");
                foreach ($subcategories as $subcategory) {
                    Parsing::doit($category, $subcategory);
                    $subcategory->time = time();
                    $subcategory->save();
                }
            } else {
                Parsing::doit($category);
            }
            $category->time = time();
            $category->save();
            D::echor("<hr>");


        }
        if ($autorefresh) {
            if ($query->count() == 0) {
                D::echor("<h2>Парсинг закончен</h2>");

            } else {
                // D::echor($message);
                return $this->refresh();
            }
        }


        return $this->render('debug');
    }

    public function actionReset()
    {
        Yii::$app->db->createCommand('UPDATE `products` SET `parsed`=0 WHERE 1;')
            ->execute();
        $message = " СБРОСИЛИ детальный парсинг";

        return $this->render('index', compact('message'));
    }


    public function actionDetailedParsing($autorefresh = 0)
    {

        Processing::DetailedParsing();


        return $this->render('debug');
    }


    public function actionParsing1()
    {
        $parsing_configuration = ParsingConfiguration::findOne(1);

        if ($parsing_configuration) {
            $parsing = new Parsing();
            $response = $parsing->collect($parsing_configuration);


        }

        return $this->render('debug');


    }

    public function actionCopyCategories()
    {
        $categories = Categories::find()->where(['id_source' => 2])->orderBy('id')->all();
        foreach ($categories as $category) {
            $new_amd_category = MainCategories::find()->where(['name' => $category->name])->one();
            //  $new_amd_category->name = $category->name;
            // if (!$new_amd_category->save()) D::dump($new_amd_category->getErrors());
            $category->id_maincategory = $new_amd_category->id;
            D::info($category->name);
            $subcategories = $category->subcategories;
            if ($subcategories) {
                foreach ($subcategories as $subcategory) {
                    $new_amd_subcategory = MainSubcategories::find()->where(['name' => $subcategory->name])->andWhere(['id_maincategory' => $new_amd_category->id])->one();
                    if (!$new_amd_subcategory) {
                        D::echor("MAIN_SUB_CATEGORY IS NOT EXISTS --> CREATING ...");
                        $new_amd_subcategory = new MainSubcategories();
                        $new_amd_subcategory->id_maincategory = $new_amd_category->id;
                        $new_amd_subcategory->name = $subcategory->name;
                        if (!$new_amd_subcategory->save()) D::dump($new_amd_subcategory->getErrors());

                    } else {
                        D::echor("MAIN_SUB_CATEGORY IS EXISTS");

                    }

                    $subcategory->id_mainsubcategory = $new_amd_subcategory->id;
                    D::echor("->>" . $subcategory->name);
                    if (!$subcategory->save()) D::dump($subcategory->getErrors());
                }
            }
            //if (!$category->save()) D::dump($category->getErrors());
        }
        return $this->render('debug');
    }


    public function actionDebug3()
    {

        $query = Products::find()->where(['<>', 'debug_status', 11]);
        D::echor(" LOST= " . $query->count());
        $products = $query->limit(200)->all();
        if ($products) {
            foreach ($products as $product) {
                //  $product->sizes_rus = preg_replace("/,/", ".", $product->sizes_rus);
                //  $product->sizes = preg_replace("/,/", ".", $product->sizes);
                $product->debug_status = 11;
                $product->save();

            }

        }
        return $this->render('debug');

    }

    public function actionResetConvert()
    {
        Yii::$app->db->createCommand('UPDATE `products` SET `convert_sizes_status`=0 WHERE 1;')
            ->execute();
        $message = " СБРОСИЛИ КОНВЕРТАЦИЮ РАЗМЕРОВ";

        return $this->render('info', compact('message'));
    }

    public function actionDebug4()
    {

        $query = Footsize::find();
        D::echor(" LOST= " . $query->count());
        $products = $query->all();
        if ($products) {
            foreach ($products as $product) {
                $product->eur = preg_replace("/,/", ".", $product->eur);
                $product->us = preg_replace("/,/", ".", $product->us);
                $product->uk = preg_replace("/,/", ".", $product->uk);
                $product->rus = preg_replace("/,/", ".", $product->rus);
                $product->cm = preg_replace("/,/", ".", $product->cm);
                $product->cm2 = preg_replace("/,/", ".", $product->cm2);

                $product->save();

            }

        }
        return $this->render('debug');

    }

    public function actionDebugCategories()
    {

        $query = Products::find();
        $id = 427;
        if (!$id) $query->orderBy(new Expression('rand()'));
        else  $query->where(['id' => $id]);
        D::echor(" LOST= " . $query->count());
        $products = $query->limit(100)->all();
        if ($products) {
            foreach ($products as $product) {

                $maincategory = $product->category->maincategory->name;
                if (!$maincategory) {
                    $maincategory = $product->category->mainsubcategory->maincategory->name;
                }
                if (!$maincategory) {
                    $maincategory = $product->subcategory->mainsubcategory->maincategory->name;
                }

                $mainsubcategory = $product->subcategory->mainsubcategory->name;
                if (!$mainsubcategory) {
                    $mainsubcategory = $product->category->mainsubcategory->name;
                }


                if (!$id) {
                    if (!$maincategory) {
                        D::echor(" <hr> ");
                        D::echor(" ID =" . $product->id);
                        D::echor(" NAME =" . $product->name);
                        D::echor("MAINCATEGORY = " . $maincategory);
                        D::echor("MAINSUBCATEGORY = " . $mainsubcategory);
                        D::echor("CATEGORY = " . $product->category->name);
                        D::echor("SUBCATEGORY = " . $product->subcategory->name);
                    }
                } else {
                    D::echor(" <hr> ");
                    D::echor(" ID =" . $product->id);
                    D::echor(" NAME =" . $product->name);
                    D::echor("MAINCATEGORY = " . $maincategory);
                    D::echor("MAINSUBCATEGORY = " . $mainsubcategory);
                    D::echor("CATEGORY = " . $product->category->name);
                    D::echor("SUBCATEGORY = " . $product->subcategory->name);
                }


                $product->save();

            }

        }
        return $this->render('debug');

    }

    public function actionConvertSizes()
    {

        $query = Products::find();
        $query->from(['p' => Products::tableName()]);
        // присоединяем связи
        $query->joinWith(['source AS s']);
        $query->joinWith(['category AS c']);
        $query->joinWith(['subcategory AS subc']);
        $query->joinWith(['brand AS b']);
        //   $query->where(['c.size_type' => Products::SIZE_FOOTS]);
        //  $query->where(['p.id' => 944]);
        $query->andwhere(['OR',
            ['convert_sizes_status' => 0],
            ['IS', 'convert_sizes_status', NULL],
        ]);

        $query->orderBy('c.size_type');
        D::echor("<br> LOST =  " . $query->count());

        $products = $query
            ->limit(50)
            ->all();
        foreach ($products as $product) {

            D::echor("<hr> РАЗМЕРНОСТЬ " . Products::renderDimension($product->dimension));
            D::echor("<br> ПРОДУКТ " . $product->name);
            if ($product->category->subcategories) {
                D::echor("ЕСТЬ ПОДКАТЕГОРИИ");
                D::dump(" КАТЕГОРИЯ " . $product->subcategory->name);
                $size_type = $product->subcategory->size_type;

            } else {
                D::echor("НЕТ ПОДКАТЕГОРИИ");
                $size_type = $product->category->size_type;

            }
            D::echor("<br> ТИПО-РАЗМЕР " . Products::getSize_type($size_type));

            if (!$size_type) {
                D::echor("<br> НЕТ РАЗМЕРОВ ");
                $product->sizes_rus = '-';
                $product->convert_sizes_status = 1;
            } elseif ($size_type == Products::SIZE_DEFAULT) {
                D::echor("<br> КАК ЕСТЬ ");
                $product->sizes_rus = $product->sizes;
                $product->convert_sizes_status = 1;
            } elseif ($size_type == Products::SIZE_RUS) {
                D::echor("<br>РУССКИЕ ");
                // $product->sizes_rus = $product->sizes;
                $product->convert_sizes_status = 1;
            } elseif ($size_type == Products::SIZE_GETRES) {
                $product->sizes_rus = $product->sizes;
                D::echor("<br> ГЕТРЫ ");
                $product->convert_sizes_status = 3;
            } else {
                $sizes = $product->sizes;
                if ($sizes) {
                    D::echor("<br> ПРОДУКТ SIZES WAS ");
                    $sizes = explode(";", $sizes);
                    D::echor(Products::renderSizes($sizes));
                    $rus_sizes = [];
                    $convert_counter = 0;
                    foreach ($sizes as $size) {
                        if ($size_type == Products::SIZE_FOOTS) {
                            $query = Footsize::find()
                                ->where(['id_brand' => $product->id_brand]);
                            // ->andwhere(['dimension' => $product->dimension])
                            if ($product->id_brand != 1) $query->andWhere(['uk' => $size]);
                            else $query->andWhere(['us' => $size]);

                            $size_rus = $query->one();
                        } elseif ($size_type == Products::SIZE_CLOTHES) {
                            $query = Clothessize::find()
                                ->where(['id_brand' => $product->id_brand])
                                // ->andwhere(['dimension' => $product->dimension])
                                ->andWhere(['OR',
                                    ['mark' => $size],
                                    ['mark' => Clothessize::Similar($size)],
                                ]);

                            $size_rus = $query->one();
                        }

                        if ($size_rus->rus) {
                            D::echor(" НАШЛИ РУССКИЙ РАЗМЕР");
                            $convert_counter++;
                            array_push($rus_sizes, $size_rus->rus);
                        } else {
                            D::echor("НЕ НАШЛИ РУССКИЙ РАЗМЕР");

                        }
                    }
                    if ($rus_sizes) {
                        $product->sizes_rus = implode(";", $rus_sizes);
                        D::echor("<br> ПРОДУКТ SIZES_RUS ");
                        D::echor(Products::renderSizes($rus_sizes));
                    }

                } else {
                    $product->convert_sizes_status = 1;
                }
                if ($convert_counter == 0) {
                    D::info("НЕ НАШЛИ РАЗМЕРОВ ВООБЩЕ", 'danger');

                    $product->sizes_rus = $product->sizes;
                    $product->convert_sizes_status = Products::SIZE_CONVERT_STATUS_ERROR_FULL;
                } elseif ($convert_counter == count($sizes)) $product->convert_sizes_status = 1;
                elseif ($convert_counter < count($sizes)) $product->convert_sizes_status = 2; // не все размеры встали верно
                // D::dump( $rus_sizes);
            }


            if (!$product->save()) D::dump($product->getErrors());


        }


        return $this->render('debug');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public
    function actionLogin()
    {

        $this->layout = 'login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public
    function actionTest()
    {
        $response = Curling::getting("http://store.jontay.com/alligator_buckles.aspx");
        D::echor($response);
        return $this->render("index");
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public
    function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public
    function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public
    function actionAbout()
    {
        return $this->render('about');
    }
}
