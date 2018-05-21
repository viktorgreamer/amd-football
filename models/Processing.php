<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.04.2018
 * Time: 12:55
 */

namespace app\models;

use app\components\ColorWidget;
use app\models\Categories;
use app\utils\D;
use app\utils\P;
use app\models\Parsing;
use yii\db\Expression;
use yii\helpers\Html;
use app\utils\R;

class Processing
{

    const UPDATE_CATEGORY_PERIOD = 50; // hours

    const PARSING = 1;
    const PARSING_CATEGORIES = 2;
    const COLORING = 5;
    const MODERATE_CATEGORIES = 6;
    const RENDERING = 7;
    const CONVERTING = 8;
    const RESET_CATEGORIES = 9;
    const RESET_SUBCATEGORIES = 10;

    public static function parsingCategories($id_source = null, $test = false, $options = [])
    {

        D::info("ПАРСИНГ КАТЕГОРИЙ");

        $query = Categories::find();
        // ->where(['id_source' => 2])
        //   ->andwhere(['id' => 84]);
        if ($options['id_category']) $query->andWhere(['in', 'id', $options['id_category']]);
        else {
            $query->where(['<', 'time', time() - Processing::UPDATE_CATEGORY_PERIOD * 60 * 60])
                ->orWhere(['IS', 'time', NULL]);
        }

        if ($id_source) $query->andWhere(['id_source' => $id_source]);

        $count = $query->count();

        D::echor("<br> ЕСТЬ " . $count . " КАТЕГОРИИ");
        if ($count == 0) return false;

        $unparsed_categories = $query->orderBy(new Expression('rand()'))->limit(1)->all();
        foreach ($unparsed_categories as $category) {
            D::echor("РАСПАРСИВАЕМ КАТЕГОРИЮ <b>" . $category->name . "</b> в ресурсе <b>" . $category->source->name . "</b>");
            $subcategories = $category->subcategories;
            if ($subcategories) {
                D::echor("<br> ЕСТЬ " . count($subcategories) . " ПОДКАТЕГОРИИИ");
                foreach ($subcategories as $subcategory) {
                    Parsing::doit($category, $subcategory, $test);
                    $subcategory->time = time();
                    $subcategory->save();
                }
            } else {
                Parsing::doit($category, 0, $test);
            }
            $category->time = time();
            $lost_products = Products::find()
                ->where(['<', 'time', time() - 24 * 60 * 60])
                ->andWhere(['id_category' => $category->id])
                ->all();
            if ($lost_products) {
                D::alert("ЕСТЬ ТОВАРЫ, КОТОРЫЕ ПРОПАЛИ С ПРЕДЫДУЩЕЙ ПРОВЕРКИ", 'danger');;
                foreach ($lost_products as $product) {
                    D::info($product->name . " ID =" . $product->id);
                    $product->status_source = 0;
                    $product->save();
                }


            }

            $category->save();
            D::echor("<hr>");


        }
        return true;
    }

    public static function Branding()
    {
        D::info("БРЕНДИНГ");
        $patterns = [];
        foreach (Brands::find()->where(['<>', 'id', 0])->all() as $brand) {
            $pattern = "/" . preg_replace("/ /", "\s", $brand->name) . "/i";
            //  D::echor("<br>brand_pattern = ".$pattern);
            $patterns[$brand->id] = $pattern;
        }
        //  D::dump($patterns);
        $count = Products::find()->where(['branded' => 0])->count();


        D::echor(" lost = " . $count);
        if ($count == 0) return false;
        $products = Products::find()->where(['branded' => 0])->limit(100)->all();
        foreach ($products as $product) {
            foreach ($patterns as $key => $pattern) {
                //  D::echor("<br> SEARCHING " . $pattern . " IN " . $product->brand_text);
                if (preg_match($pattern, $product->brand_text)) {
                    $product->id_brand = $key;
                    D::echor("<br>brand =" . $product->brand_text . " id_brand  = " . $product->id_brand . " getBrand = " . $product->brand->name);
                    break;
                } else {

                    //  D::echor('<br> NO MATCHES');
                }

            }
            $product->branded = 1;
            $product->save();

        }
        return true;


    }

    public static function DetailedParsing($limit = 10, $options = [])
    {
        D::info("ДЕТАЛЬНЫЙ ПАРСИНГ");
        $query = Processing::getREADY(self::PARSING, $options);
        $count = $query->count();

        D::echor("<br> LOST = " . $count);
        if ($count == 0) return false;


        $products = $query->limit($limit)->all();

        foreach ($products as $product) {
            sleep(1);
            D::echor("<br>" . Html::a($product->source_link, $product->source_link, ['target' => "_blank"]));
            $response_page = Parsing::getSource($product->source_link);
            if (!$response_page) {
                $product->parsed = 4;
            } else {
                if (Parsing::Detailed($product, $response_page) == P::PAGE_NOT_FOUND) $product->status_source = 0;
                $product->parsed = 1;
                $product->convert_sizes_status = 0;
                $product->time = time();

            }

            //$pq_page = \phpQuery::newDocument($response_page);

            // D::echor("<br>pq_page " . $pq_page);

            if (!$options['not_save']) {
                if (!$product->save()) D::dump($product->getErrors());

            }


        }
        return true;
    }

    public static function ConvertSizes($limit = 10, $options = [])
    {
        D::info("КОНВЕРТАЦИЯ РАЗМЕРОВ");

        $query = Processing::getREADY(self::CONVERTING, $options);
        $count = $query->count();
        D::echor("<br> LOST =  " . $count);
        if ($count == 0) return false;

        $products = $query
            ->limit(50)
            ->all();
        foreach ($products as $product) {

            D::echor("<br> ПРОДУКТ " . $product->name);
            if ($product->category->subcategories) {
                D::echor("ЕСТЬ ПОДКАТЕГОРИИ");
                D::dump(" КАТЕГОРИЯ " . $product->subcategory->name);
                $size_type = $product->subcategory->size_type;

            } else {
                D::echor("НЕТ ПОДКАТЕГОРИИ");
                $size_type = $product->category->size_type;

            }
            if ($size_type) D::echor("<br> ТИПО-РАЗМЕР " . Products::getSize_type($size_type));
            if (!$size_type) {
                D::echor("<br> НЕТ РАЗМЕРОВ ");
                $product->sizes_rus = '-';
                $product->convert_sizes_status = 1;
            } elseif ($size_type == Products::SIZE_DEFAULT) {
                D::echor("<br> КАК ЕСТЬ ");
                if ($product->sizes) $product->sizes_rus = $product->sizes;
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

            if (!$options['not_save']) {
                if (!$product->save()) D::dump($product->getErrors());

            }


        }

        return true;

    }

    public static function ResetCategories($options = [])
    {


        $query = Processing::getREADY(self::RESET_SUBCATEGORIES, $options);
        D::info("LOST  MAINSUBCATEGORIES= " . $query->count());
        $products = $query->limit(10)->all();

        foreach ($products as $product) {
            $category = $product->calculateMainsubcategory();
            if ($category) {
                D::alert(" Нашли  главную ПОДКАТЕГОРИЮ " . $category->name . " id=" . $category->id, 'success');
                $product->id_mainsubcategory = $category->id;
            } else {
                D::alert("Не Нашли главную ПОДКАТЕГОРИЮ ", 'danger');
                $product->id_maincategory = 0;
            }

            $product->save();

        }

        $query = Processing::getREADY(self::RESET_CATEGORIES, $options);
        D::info("LOST  MAINCATEGORIES = " . $query->count());
        $products = $query->limit(10)->all();

        foreach ($products as $product) {
            D::echor($product->info());
            $category = $product->calculateMaincategory();
            if ($category) {
                D::alert(" Нашли главную категорию " . $category->name . " id=" . $category->id, 'success');
            } else {
                D::alert("Не Нашли главную категорию ", 'danger');
                $product->manual_maincategory = 1;
            }

           if (!$product->save()) D::dump($product->getErrors());

        }



    }

    public static function Render($options = [])
    {
        D::info("РЕНДЕРИНГ");
        $query = Processing::getREADY(Processing::RENDERING, $options);
        $count = $query->count();

        D::info("LOST = " . $count);

        if ($count == 0) return false;

        if ($options['limit']) $limit = $options['limit']; else $limit = 50;
        $products = $query->limit($limit)->all();
        foreach ($products as $product) {
            D::echor("<hr> ЭКСПОРТИРУЕМ ПРОДУКТ");
            D::echor($product->info());
            //   D::echor(" id=" . Html::a($product->id, (['products/view', 'id' => $product->id]), ['target' => '_blank']));

            $maincategory = $product->maincategory;

            $mainsubcategory = $product->mainsubcategory;
            if (!$maincategory) {
                D::alert(" НЕ УДАЛОСЬ ОПРЕДЕЛИТЬ КАТЕГОРИЮ, ПРОПУСКАЕМ....", 'danger');
                $error_export++;
                $product->render_status = Products::RENDERED_ERROR_CATEGORY;
                $product->save();
                d::echor("<hr style='background-color: red; height: 10px;'>");

                continue;
            }
            D::echor(" В MAIN_CATEGORY = <b>" . $maincategory->name . "</b>");
            if ($mainsubcategory) D::echor(" В MAIN_SUBCATEGORY =  <b>" . $mainsubcategory->name . "</b>");

            $same_products = $product->getSameproductsQuery()->all();
            if ($same_products) {
                if (count($same_products) > 1) {
                    if (($product->id_color) AND ($product->id_color != 99)) {
                        D::alert("ЕСТЬ ВСЕГО " . count($same_products) . " ТОВАР(а|ов) С ПОХОЖИМ  АРТИКУЛОМ и  ЦВЕТОМ ", 'danger');
                    } else {
                        D::alert("<b> ЕСТЬ ВСЕГО " . count($same_products) . " ТОВАР(а|ов) С ПОХОЖИМ  АРТИКУЛОМ  = " . $product->articul, 'success');

                    }
                } else  D::alert("ДАННЫЙ ТОВАР В ЕДИНСТВЕННОМ ЭКЗЕМПЛЯРЕ ", 'success');

                $all_sizes = '';
                foreach ($same_products as $same_product) {
                    //   D::echor($same_product->info());
                    $sizes = explode(";", $same_product->sizes_rus);
                    D::echor(R::asTable([$same_product->info(), " РАЗМЕРЫ ", Products::renderSizes($sizes)], ['class' => 'table table-bordered']));
                    //   D::echor($product->url);
                    //   D::echor($same_product->url);
                    // D::info("ЕСТЬ ПРОДУКТ С ПОХОЖИМ  АРТИКУЛОМ = ".$product->articul ." same_articul =".$same_product->articul);
                    // $same_product_sizes = preg_split("/;/", $same_product->sizes);
                    $all_sizes .= ";" . $same_product->sizes_rus;
                    //  D::dump($same_product_sizes_rus);
                    $same_product->render_status = Products::RENDERED_SUCCESS;
                    $same_product->save();
                }


            }
            // объединяем, уникализируем  и сортируем размеры
            $sizes_rus = array_diff(array_unique(explode(";", $all_sizes)), ['', '-']);
            asort($sizes_rus);

            if (count($sizes_rus) >= 1) {
                D::echor(R::asTable([" ИТОГОВЫЕ РАЗМЕРЫ ", Products::renderSizes($sizes_rus)]));
                //  D::dump($sizes_rus);
                //  D::echor(Products::renderSizes($sizes_rus));
            } else {
                if ($product->hasSizes()) {
                    D::alert("РАЗМЕРОВ НЕТ ВООБЩЕ,  НО ОНИ ДОЛЖНЫ БЫТЬ... ПРОПУСКАЕМ", 'danger');
                    $error_export++;
                    $product->render_status = Products::RENDERED_ERROR_SIZES;
                    $product->save();
                    d::echor("<hr style='background-color: red; height: 10px;'>");

                    continue;

                    $render = new ProductsRender();
                    $render->loadProduct($product, $maincategory, $mainsubcategory, Products::SOURCE_DISACTIVE, '');
                    if (!$render->save()) {
                        $product->render_status = Products::RENDERED_ERROR_VALIDATION;
                        D::dump($render->getErrors());
                    }


                }

                $sizes_rus = [0 => ''];
            }

            // creating the same renders products with size modification

            foreach ($sizes_rus as $key => $size_rus) {
                $render = new ProductsRender();
                $render->loadProduct($product, $maincategory, $mainsubcategory, 1, $size_rus);
                if (!$render->save()) D::dump($render->getErrors());
            }
            d::echor("<hr style='background-color: red; height: 10px;'>");

            $product->render_status = Products::RENDERED_SUCCESS;
            $product->save();


        }

        D::echor(" Успешно экспортировали " . (count($products) - $error_export) . "  из " . $count . " записей");
        return true;

    }

    public static function ModerateCategories($limit = 10)
    {

        $query = Products::find();

        $query->from(['p' => Products::tableName()]);
        // присоединяем связи
        $query->joinWith(['source AS s']);
        $query->joinWith(['category AS c']);
        $query->joinWith(['subcategory AS subc']);
        $query->joinWith(['brand AS b']);

        $query->andWhere(['OR',
                ['c.manual_category' => 1],
                ['subc.manual_category' => 1]]
        );
        $query->andWhere([
                'AND',
                ['p.manual_maincategory' => 0],
                ['p.manual_mainsubcategory' => 0]
            ]
        );
        $query->andWhere(['OR',
            ['c.not_parsing' => 0],
            ['IS', 'c.not_parsing', NULL]
        ]);
        $query->andWhere(['OR',
            ['subc.not_parsing' => 0],
            ['IS', 'subc.not_parsing', NULL]
        ]);
        $count = $query->count();
        D::info("LOST = " . $count);

        $products = $query->limit($limit)->all();
        foreach ($products as $product) {
            D::echor(Html::a($product->name, $product->source_link, ['target' => '_blank']));
            D::echor(Html::a("УСТАНОВИТЬ ВРУЧНУЮ", ['products/update', 'id' => $product->id], ['target' => '_blank']));
            $russian_words = P::SearchRussianWords($product->name);
            if ($russian_words) {
                $body = '';
                foreach ($russian_words as $russian_word) {

                    if (mb_strlen($russian_word) < 3) continue;
                    if (mb_strlen($russian_word) > 5) $russian_word = mb_substr($russian_word, 0, mb_strlen($russian_word) - 3, 'UTF-8');
                    $body .= "<br> SEARCHING WORD = " . $russian_word;

                    $Categories = MainCategories::find()->where(['like', 'name', $russian_word])->all();
                    if ($Categories) {
                        foreach ($Categories as $category) {

                            if ($category->getMainsubcategories()) continue;
                            $class = "btn btn-success";
                            $body .= "<br> " . Html::button($category->name,
                                    ['class' => $class . " btn-xs set-attr-value",
                                        'data' => [
                                            'id' => $product->id,
                                            'attr' => 'manual_maincategory',
                                            'value' => $category->id]
                                    ]);
                        }
                    }

                    $subcategories = MainSubcategories::find()->where(['like', 'name', $russian_word])->all();

                    if ($subcategories) {
                        foreach ($subcategories as $category) {
                            $class = "btn btn-success";
                            $body .= "<br> SUB ->>> " . Html::button($category->maincategory->name . "-->>" . $category->name,
                                    ['class' => $class . " btn-xs set-attr-value",
                                        'data' => [
                                            'id' => $product->id,
                                            'attr' => 'manual_mainsubcategory',
                                            'value' => $category->id]
                                    ]);
                        }
                    }


//                    $query_sameproducts = Products::find();
//                    $query_sameproducts->from(['p' => Products::tableName()]);
//                    // присоединяем связи
//                    $query_sameproducts->joinWith(['source AS s']);
//                    $query_sameproducts->joinWith(['category AS c']);
//                    $query_sameproducts->joinWith(['subcategory AS subc']);
//                    $query_sameproducts->joinWith(['brand AS b']);
//                    $query_sameproducts->andWhere(['OR',
//                            ['c.manual_category' => 0],
//                            ['subc.manual_category' => 0]]
//                    );
//                    $query_sameproducts->where(['like', 'p.name', $russian_word]);
//
//
//                    $products_category = $query_sameproducts->groupBy('p.id_category,p.id_subcategory')->all();
//
//                    //  D::dump($id_categories);
//
//                    if ($products_category) {
//                        foreach ($products_category as $product) {
//                            $fullname = '';
//                            $maincategory = $product->getMaincategory();
//                            if ($maincategory->mainsubcategories) $attr = "manual_mainsubcategory"; else $attr = "manual_maincategory";
//                            if ($maincategory) $fullname .= $maincategory->name;
//                            $mainsubcategory = $product->getMainsubcategory();
//                            if ($mainsubcategory) $fullname .= "->>>".$mainsubcategory->name;
//
//                            $fullname .= "->>>".$product->name;
//                            $class = "btn btn-success";
//                            $body .= "<br> SUB ->>> " . Html::button($fullname,
//                                    ['class' => $class . " btn-xs set-attr-value",
//                                        'data' => [
//                                            'id' => $product->id,
//                                            'attr' => $attr,
//                                            'value' => $category->id]
//                                    ]);
//                        }
//                    }

                }
            }

            D::echor($body);
            D::echor("<hr>");


        }

    }

    public static function moderateColor()
    {

        $query = self::getREADY(self::COLORING);

        $count = $query->count();
        D::info("LOST = " . $count);

        $products = $query->limit(10)->all();
        foreach ($products as $product) {
            D::echor("arcitul = " . $product->articul);

            $articuled_products = Products::find()->where(['articul' => $product->articul])->andWhere(['<>', 'id', $product->id])->all();
            $images = explode(" ", $product->images);
            D::echor("<div class='row table-bordered'>");
            D::echor("<div class='col-lg-3'>");
            D::echor(Html::img($images[0], ['width' => '100px']) . " " . Html::a($product->source->name, $product->source_link, ['target' => '_blank']));

            D::echor(ColorWidget::widget(['select' => true, 'id' => $product->id]));
            D::echor("</div>");
            D::echor("<div class='col-lg-9'>");
            if ($articuled_products) {
                D::info("Есть также другие торвары с данным артикулом <b>" . $product->articul . "</b>", 'danger');
                D::echor("<div class='row table-bordered'>");
                foreach ($articuled_products as $articuled_product) {
                    D::echor("<div class='col-lg-3'>");
                    //  D::echor("arcitul = " . $articuled_product->articul);

                    $images = explode(" ", $articuled_product->images);
                    D::echor(Html::img($images[0], ['width' => '100px']) . " " . Html::a($articuled_product->source->name, $articuled_product->source_link, ['target' => '_blank']));

                    D::echor(ColorWidget::widget(['select' => true, 'id' => $articuled_product->id], false));
                    D::echor("</div>");

                }
                D::echor("</div>");
            }
            D::echor("</div>");
            D::echor("</div>");
            d::echor("<hr style='background-color: red; height: 10px;'>");


        }


    }


    public static function getREADY($type, $option = [])
    {

        $query = Products::find();

        $query->from(['p' => Products::tableName()]);
        // присоединяем связи
        $query->joinWith(['source AS s']);
        $query->joinWith(['category AS c']);
        $query->joinWith(['subcategory AS subc']);
        $query->joinWith(['brand AS b']);
        $query->joinWith(['color AS color']);
        $query->joinWith(['maincategory AS mainc']);
        $query->joinWith(['mainsubcategory AS mainsubc']);

        $query->andWhere(['<>','c.not_parsing', 1]);
        $query->andWhere(['<>','c.not_render', 1]);


        switch ($type) {
            case Processing::PARSING:
                {
                    if ($option['id']) {
                        $query->andWhere(['p.id' => $option['id']]);
                    } else {
                        $query->Where(
                            ['OR',
                                ['NOT IN', 'p.parsed', [1, 4]],
                                ['IS', 'p.parsed', NULL]]
                        );
                        if ($option['id_source']) {
                            D::info(" РАБОТАЕМ С " . Sources::findOne($option['id_source'])->name);
                            $query->andWhere(['p.id_source' => $option['id_source']]);
                        }

                        if ($option['id_category']) $query->andWhere(['in', 'p.id_category', $option['id_category']]);

                        $query->orderBy(new Expression('rand()'));
                    }


                    break;
                }
            case Processing::COLORING:
                {
                    $query->andWhere(['p.id_color' => 0]);
                    $query->andWhere(['<>', 'p.articul', 0]);
                    $query->andWhere(['OR',
                        ['c.colored' => 1],
                        ['subc.colored' => 1],
                    ]);

                    $query->orderBy(new Expression('rand()'));
                    $query->groupBy('articul');
                    break;
                }

            case Processing::MODERATE_CATEGORIES:
                {

                    $query->andWhere(['OR',
                            ['c.manual_category' => 1],
                            ['subc.manual_category' => 1]]
                    );
                    $query->andWhere([
                            'AND',
                            ['p.manual_maincategory' => 0],
                            ['p.manual_mainsubcategory' => 0]
                        ]
                    );
                    $query->andWhere(['OR',
                        ['c.not_parsing' => 0],
                        ['IS', 'c.not_parsing', NULL]
                    ]);
                    $query->andWhere(['OR',
                        ['subc.not_parsing' => 0],
                        ['IS', 'subc.not_parsing', NULL]
                    ]);

                    break;
                }
            case Processing::RENDERING:
                {

                    if ($option['id']) {
                        $query->where(['p.id' => $option['id']]);

                    } else {
                        $query->where(['in', 'p.status_source', [Products::SOURCE_ACTIVE, Products::SOURCE_DISACTIVE]])
                            ->andWhere(
                                ['OR',
                                    ['p.render_status' => 0],
                                    ['IS', 'p.render_status', NULL]]
                            )// ->andWhere(['id' => 4206])
                        ;
                        if ($option['id_category']) $query->andFilterWhere(['p.id_category' => $option['id_category']]);
                        $query->andFilterWhere(['in', 'p.convert_sizes_status', [1, 2]]);

                        //  if ($option['limit']) $query->limit($option['limit']); else $query->limit(30);
                        $query->groupBy('p.articul');
                    }
                }
                break;

            case Processing::CONVERTING:
                {
                    if ($option['id']) {
                        $query->andWhere(['p.id' => $option['id']]);
                    } else {
                        $query->where(['OR',
                            ['convert_sizes_status' => 0],
                            ['IS', 'convert_sizes_status', NULL],
                        ]);
                    }

                    if ($option['id_source']) {
                        // D::info(" РАБОТАЕМ С " . Sources::findOne($options['id_source'])->name);
                        $query->andWhere(['p.id_source' => $option['id_source']]);
                    }

                    $query->orderBy('c.size_type');


                }
                break;

            case Processing::RESET_CATEGORIES:
                {
                    if ($option['id']) {
                        $query->andWhere(['p.id' => $option['id']]);
                    } else {
                        $query->where(['OR',
                            ['IS', 'p.id_maincategory', NULL],
                            ['p.id_maincategory' => Products::BROKEN_CATEGORY],
                            ['p.id_maincategory' => 0]

                        ]);
                        $query->andWhere( ['<>','p.manual_maincategory', 1]);
                    }

                    if ($option['id_source']) {
                        // D::info(" РАБОТАЕМ С " . Sources::findOne($options['id_source'])->name);
                        $query->andWhere(['p.id_source' => $option['id_source']]);
                    }


                }
                break;
                case Processing::RESET_SUBCATEGORIES:
                {
                    if ($option['id']) {
                        $query->andWhere(['p.id' => $option['id']]);
                    } else {
                        $query->where(['IS', 'p.id_mainsubcategory', NULL]);
                    }

                    if ($option['id_source']) {
                        // D::info(" РАБОТАЕМ С " . Sources::findOne($options['id_source'])->name);
                        $query->andWhere(['p.id_source' => $option['id_source']]);
                    }


                }
                break;


        }

        return $query;


    }


    public static function getREADYCategories($options = [])
    {

        $query = Categories::find();
        // ->where(['id_source' => 2])
        //   ->andwhere(['id' => 84]);
        if ($options['id_category']) $query->andWhere(['in', 'id', $options['id_category']]);
        else {
            $query->where(['<', 'time', time() - Processing::UPDATE_CATEGORY_PERIOD * 60 * 60])
                ->orWhere(['IS', 'time', NULL]);
        }

        if ($options['id_source']) $query->andWhere(['id_source' => $options['id_source']]);


        return $query;


    }
}