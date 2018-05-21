<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.03.2018
 * Time: 13:34
 */

namespace app\models;

use app\models\Curling;
use app\utils\D;
use yii\helpers\Html;
use Curl\Curl;
use app\utils\P;
use phpQuery;

/* @var $parsing_configuration ParsingConfiguration */
class Parsing
{
    /// метод который выбирает, работаем ли мы с кешированным контентом ("@wev/html_source)  или делаем запрос в web
    public static function getSource($url)
    {

        if (PARSING_TEST) {
            return self::CashedSource($url);

        } else {
            $curl = new Curl();
            $curl->get($url);
            if ($curl->error) {
                // если ошибка соединения
                echo '<br>PROXY Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
                return false;
            } else {
                // если все хорошо
                return $curl->response;
            }

        }


    }

    public static function CashedSource($url)
    {

        $filename = "cashed_html/" . preg_replace("/\//", "-", str2url($url)) . ".html";
        if (file_exists($filename)) {
            D::echor(' ФАЙЛ СУЩЕСТВУЕТ');
            $response = file_get_contents($filename);
        } else {
            D::echor(' ФАЙЛ ОТСУТСТВУЕТ, БЕРЕМ ИЗ web');
            $curl = new Curl();
            $curl->get($url);
            if ($curl->error) {
                // если ошибка соединения
                echo '<br>PROXY Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            } else {
                // если все хорошо
                $response = $curl->response;
            }

            file_put_contents($filename, $response);
        }

        return $response;
    }

    public function collect($parsing_configuration)
    {

        switch ($parsing_configuration->type) {
            case ParsingConfiguration::TYPE_CATEGORIES:
                //временно чтобы не нагружать сервер
                // $parsing_configuration->url = 'sources/sportdepo/categories.html';
                $response = Curling::getting($parsing_configuration->url, '', '');
                $source = Sources::findOne($parsing_configuration->id_source);
                Categories::creating($response, $source);

                // D::echor($response);
                return $response;
        }


    }

    public static function doit($category, $subcategory = 0, $test = false)
    {

        switch ($category->id_source) {
            case 1: // sportdepo
                $source = Sources::findOne($category->id_source);
                if ($subcategory) $link = $subcategory->link;
                else $link = $category->link;
                //временно чтобы не нагружать сервер
                $link .= "?page=all";
                $link .= "&shops=%5B80%5D%5B1%5D%5B2%5D%5B4%5D%5B11%5D%5B3%5D%5B14%5D%5B16%5D%5B18%5D";
                //  $link .= Subcategories::SHOPS_URL_MODIFICATION;

                $curl = new Curl();
                $curl->get($link);
                $response = $curl->response;
                //  D::echor($response);
                $curl->close();

                // ищем цвета
                //  $pq = \phpQuery::newDocument($response);
                //  $filters = $pq->find(".filters")->html();

                // ищем доступные цвета
                // preg_match("/<span class=\"checkbox\" data-value=\"(.+)\">/U", $input_line, $output_array);
//                if (preg_match_all("/<span class=\"checkbox\" data-value=\"([0-9a-fA-F]{6})\">/U", $filters, $output_array)) {
//                    $colors = $output_array[1];
//                  //  D::dump($colors);
//                    D::echor("<br> ДОСТУПНЫ ЦВЕТА = ".implode(",", $output_array[1]));
//                    foreach ($colors as $color) {
//                        $colored_link = $link . "?colors=%5B".$color."%5D";
//                        D::echor("<br>colored_link = " .Html::a('colored_link', $colored_link));
//
//                        $response = Curling::getting($colored_link, '', $source->url);
//                        Products::creating($response, $category, $subcategory,['color' => $color]);
//
//                    }
//
//                } else {
//
//                    Products::creating($response, $category, $subcategory);
//
//
//                }
                Products::creating($response, $category, $subcategory);

                //   Products::creating($response, $category, $subcategory);

                // D::echor($response);
                return $response;
                break;

            case 2: // premier
                $source = Sources::findOne($category->id_source);
                if ($subcategory) $link = $subcategory->link;
                else $link = $category->link;

                $curl = new Curl();
                $curl->get($link);
                $response = $curl->response;
                //   D::echor($response);
                $curl->close();
                // пределение количества страниц
                $pq_page = phpQuery::newDocument($response);
                $href_pages = $pq_page->find("ul.catalog__pagination > li")->html();
                D::echor($href_pages);
                if (preg_match_all("/page-(\d+)/", $href_pages, $output_array)) {
                    $count_pages = max($output_array[1]);
                    D::echor("COUNT PAGES = " . $count_pages);
                }


                Products::creating($response, $category, $subcategory);
                if ($count_pages) {
                    D::info(" ЕСТЬ ПАГИНАЦИЯ  в " . $count_pages . " страниц", 'danger');


                    foreach (range(2, $count_pages) as $page) {
                        sleep(0.5);
                        D::echor(" ПРОХОДИМ СТРАНИЦУ " . Html::a($page, $link . "page-" . $page . "/", ['target' => '_blank']));
                        $curl = new Curl();
                        $curl->get($link . "page-" . $page . "/");
                        $response = $curl->response;
                        //  D::echor("ТЕЛО ".$response);
                        $curl->close();

                        // $response = Curling::getting($link . "page-" . $page, '', $source->url);
                        Products::creating($response, $category, $subcategory);
                    }
                } else {
                    D::info("НЕТ ПАГИНАЦИИ", 'danger');

                }


                return $response;
            case 3: // 2k

                $source = Sources::findOne($category->id_source);
                if ($subcategory) $link = $subcategory->link;
                else $link = $category->link;
                D::echor(Html::a($category->link, $category->link));

                $response = Parsing::getSource($category->link);
                //  D::echor(" RESPONSE =".$response);
                //    $response = Curling::getting($link, '', $source->url);
                // пределение количества страниц
                $tree = str_get_html($response);

                if ($tree->find("ul.pagination", 0)) {
                    $pagination = $tree->find("ul.pagination", 0)->find("li a");
                    $pages = [];
                    foreach ($pagination as $item) {
                        if (preg_match("/(\d+)/", $item->plaintext, $output_array)) {
                            array_push($pages, $output_array[1]);
                        };
                    }
                }

                //   D::dump($pages);


//
                Products::creating($response, $category, $subcategory, true);
                if ($pages) {
//
                    D::echor("COUNT PAGES = " . count($pages));
//
                    foreach (range(2, count($pages)) as $page) {
                        D::echor(" ПРОХОДИМ СТРАНИЦУ " . $page);
                        $generated_link = $link . "?PAGEN_1=" . $page;
                        D::echor(Html::a($generated_link, $generated_link));

                        $response = Parsing::CashedSource($generated_link);
                        Products::creating($response, $category, $subcategory, $test);
                    }
                }


                return $response;
        }


    }

    public function doit1($category, $subcategory = 0)
    {

        switch ($category->id_source) {
            case 1: // sportdepo
                $source = Sources::findOne($category->id_source);
                if ($subcategory) $link = $subcategory->link;
                else $link = $category->link;

                $link .= "?page=all";
                $link .= Subcategories::SHOPS_URL_MODIFICATION;
                $response = Curling::getting($link, '', $source->url);
                Products::creating($response, $category, $subcategory);

                // D::echor($response);
                return $response;
        }


    }


    public function collectCategoriesAndSubcategories($id_source, $test = false)
    {
        switch ($id_source) {
            case 2:
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
                }
            case 3:
                {

                    $id_source = 3; // 2k-shop
                    $source = Sources::findOne($id_source); // 2k-shop
                    $response = Parsing::CashedSource("https://2k-shop.ru/");
                    $html = str_get_html($response);

                    //  D::echor($html);
                    $categories = $html->find("nav.nav div.drop ul li a");
                    //D::echor($nav);

                    foreach ($categories as $category) {
                        $name_category = $category->plaintext;
                        $link = $source->url . $category->href;
                        D::echor(" name_category = " . $name_category);
                        D::echor(Html::a($link, $link));
                        $new_category = new Categories();
                        if ($test) $new_category->test = 1;
                        $new_category->id_source = $id_source;
                        $new_category->name = $name_category;
                        $new_category->link = $link;
                        if (!$new_category->isExisted()) {
                            D::echor("<br> СОЗДАЕМ НОВУЮ КАТЕГОРИЮ");
                            if (!$new_category->save()) D::dump($new_category->getErrors());

                        } else {
                            D::echor("СУЩЕСТВУЮЩАЯ КАТЕГОРИЯ");
                            $new_category = Categories::find()->where(['id_source' => $id_source])->andWhere(['link' => $new_category->link])->one();
                        }
                        D::echor("СВОЙСТВА КАТЕГОРИИ");
                        D::renderProperties($new_category, ['id_source', 'name', 'link']);


                    }
                }


        }


    }

    public static function Detailed($product, $page)
        /* @var $product \app\models\Products */
    {

        $pq_page = \phpQuery::newDocument($page);
        $simple = str_get_html($page);

        switch ($product->id_source) {
            case 1:
                { //sport-depo
                    // определяем размеры
                    $razmer_nums = [];
                    D::echor("<br>" . $product->name);
                    $options = $simple->find("select.mag option");
                    D::echor("SIMPLE = " . $options);
                    //  $options = $pq_page->find("select.mag > option");
                    if ($options) {
                        D::echor("<br> НАШЛИ ОПЦИИ select.mag");
                        // D::echor($options->html());
                        foreach ($options as $option) {
                            $razmer_nums[] = $option->plaintext;
                            //   D::echor(" <br> размер = " . $option);
                            //   D::dump($option);
                        }
                    } else {
                        $options = $simple->find("select.inet option");
                        if ($options) {
                            // D::echor($options->html());
                            D::echor("<br> НАШЛИ ОПЦИИ select.inet");
                            foreach ($options as $option) {
                                $razmer_nums[] = $option->plaintext;
                                //  D::echor(" <br> размер = " . $option->getAttribute('value'));
                            }
                        }
                    }
                    if ($razmer_nums) {
                        // удаляем первое нулевое значение
                        array_shift($razmer_nums);
                        D::echor(" НАШЛИ РАЗМЕРЫ " . Products::renderSizes($razmer_nums));
                        $product->sizes = preg_replace("/,/", ".", implode(";", $razmer_nums));
                        if ($product->hasSizes()) $product->status_source = Products::SOURCE_ACTIVE;


                    } else {
                        D::echor("НЕ НАШЛИ РАЗМЕРЫ ");
                        $product->sizes = '';
                        if ($product->hasSizes()) $product->status_source = Products::SOURCE_DISACTIVE;
                    }
                    if (preg_match("/XXXS|XXS|XXL|XXXL|XXXXL/", $product->sizes)) {

                        $product->sizes = Clothessize::PREG_REPLACE($product->sizes);
                        D::echor(" ПРИВОДИМ В РАЗМЕРЫ В ЕДИНЫЙ ВИД " . Products::renderSizes($razmer_nums));

                    }

                    // meta descrition
                    preg_match("/<meta name=\"description\" content=\"(.+)\">/U", $pq_page->html(), $output_array);
                    $product->meta_description = $output_array[1];

                    // meta keywords
                    preg_match("/<meta name=\"KeyWords\" content=\"(.+)\">/U", $pq_page->html(), $output_array);
                    $product->meta_keywords = $output_array[1];
                    // descriptios
                    $product->description = $pq_page->find('#tab_description')->text();

                    // div thumbs
                    $div_thumbs = $pq_page->find(".product_img")->html();
                    // D::echor("<br>div_thimbs".$div_thumbs);
                    if (preg_match_all("/\/gallery\/(.{1,15})_80x80\.jpeg\"/U", $div_thumbs, $output_array)) {
                        $images = [];
                        foreach ($output_array[1] as $imagelink) {
                            $imagelink = "https://www.sportdepo.ru/gallery/" . $imagelink . ".jpeg";

                            D::echor("<br>" . Html::a($imagelink, $imagelink, ['target' => '_blank']));
                            // D::echor(Html::img($imagelink));
                            array_push($images, $imagelink);
                        }

                        $product->images = implode(" ", $images);
                    } elseif (preg_match("/\/gallery\/(.{1,15})\.jpeg\"/U", $div_thumbs, $output_array)) {
                        $imagelink = "https://www.sportdepo.ru/gallery/" . $output_array[1] . ".jpeg";

                        D::echor("<br>" . Html::a($imagelink, $imagelink, ['target' => '_blank']));
                        // D::echor(Html::img($imagelink));
                        $product->images = $imagelink;
                    } elseif (preg_match_all("/\/gallery\/(.{1,15})_80x80\.png\"/U", $div_thumbs, $output_array)) {
                        $images = [];
                        foreach ($output_array[1] as $imagelink) {
                            $imagelink = "https://www.sportdepo.ru/gallery/" . $imagelink . ".png";

                            D::echor("<br>" . Html::a($imagelink, $imagelink, ['target' => '_blank']));
                            // D::echor(Html::img($imagelink));
                            array_push($images, $imagelink);
                        }

                        $product->images = implode(" ", $images);
                    } elseif (preg_match("/\/gallery\/(.{1,15})\.png\"/U", $div_thumbs, $output_array)) {
                        $imagelink = "https://www.sportdepo.ru/gallery/" . $output_array[1] . ".png";

                        D::echor("<br>" . Html::a($imagelink, $imagelink, ['target' => '_blank']));
                        // D::echor(Html::img($imagelink));
                        $product->images = $imagelink;
                    } else {
                        D::echor(" ФОТО НЕ НАШЛИ ВООБЩЕ");
                    }


                    $pq_price = $pq_page->find('#priceSP > p')->text();
                    $product->price_old = preg_replace("/\D+/", "", $pq_price);

                    $pq_price = $pq_page->find('#priceSP > span')->text();
                    $product->price = preg_replace("/\D+/", "", $pq_price);

                    $div_product_title = $pq_page->find(".product_title")->text();

                    if (preg_match_all("/Размерность:.+Взрослая/", $div_product_title, $output_array)) $product->dimension = 1;
                    if (preg_match_all("/Размерность:.+Детская/", $div_product_title, $output_array)) $product->dimension = 2;


                    // распечатывание свойств
                    //  $properties = ['name', 'sizes', 'meta_description', 'meta_keywords', 'images', 'color', 'description', 'price_old', 'price'];
                    $properties = ['name', 'price_old', 'price', 'dimension', 'sizes'];
                    foreach ($properties as $property) {
                        {
                            D::echor("<br> " . $property . " = <b>" . $product[$property] . "</b>");

                        }

                    }
                    D::echor("<hr> ");
                    return P::PAGE_EXISTS;
                }
                break;
            case 2:
                { //premier
                    // определяем размеры
                    $razmer_nums = [];
                    D::echor("<br>" . $product->name);
                    // $razmer_type = $pq_page->find(".item-page__sizes")->html();
                    $pq_sizes = $pq_page->find("#select_size > option");
                    foreach ($pq_sizes as $pq_size) {
                        $pq_size = \phpQuery::pq($pq_size)->text();
                        break;
                    }
                    if (preg_match("/RUS/", $pq_size)) {

                    };
                    // D::dump($pq_sizes);
                    D::info(" РАЗМЕРНОСТЬ " . $pq_size);

                    $options = $pq_page->find(".item-page__sizes-list-wrapper > ul > li.active")->html();

                    if (!$product->hasSizes()) {
                        D::echor("ТОВАР НЕ ИМЕЕТ РАЗМЕРОВ");
                        $product->status_source = Products::SOURCE_ACTIVE;
                    }
                    else  {
                        $product->status_source = Products::SOURCE_ACTIVE;
                        if ($options) {
                            D::info("ЕСТЬ АКТИВНЫЕ РАЗМЕРЫ");
                            preg_match_all("/размер.+\">(.+)<\/label>/", $options, $output_array);
                            if ($product->hasSizes()) $product->status_source = Products::SOURCE_ACTIVE;

                            //  D::dump($output_array);
                            //  D::echor("<br>".$options);

                        } else {
                            D::info("НЕТ АКТИВНЫХ РАЗМЕРОВ");
                            $options = $pq_page->find(".item-page__sizes-list-wrapper > ul > li")->html();
                            if ($options) {
                                D::info("ЕСТЬ ПРОСТО РАЗМЕРЫ");
                               // D::echor($options);
                                preg_match_all("/размер.+>(.+)<\/label>/", $options, $output_array);
                              //  D::dump($output_array);
                                if ($product->hasSizes()) $product->status_source = Products::SOURCE_ACTIVE;
                                else D::echor("ТОВАР НЕ ИМЕЕТ РАЗМЕРОВ");
                            } else {
                                if ($product->hasSizes()) $product->status_source = Products::SOURCE_DISACTIVE;
                               // else $product->status_source = Products::SOURCE_ACTIVE;
                            }
                        }
                    }


                    //  D::dump($output_array[1]);
                    if (preg_match("/RUS/", $pq_size)) {
                        if ($output_array[1]) $product->sizes_rus = preg_replace("/,/", ".", implode(";", $output_array[1]));

                    } else {
                        if ($output_array[1]) $product->sizes = preg_replace("/,/", ".", implode(";", $output_array[1]));


                    }

                    if (preg_match("/XXXS|XXS|XXL|XXXL|XXXXL/", $product->sizes)) {

                        $product->sizes = Clothessize::PREG_REPLACE($product->sizes);
                        D::echor(" ПРИВОДИМ В РАЗМЕРЫ В ЕДИНЫЙ ВИД " . Products::renderSizes($razmer_nums));

                    }


                    $slider__slides = $pq_page->find(".item-slider__slide");
                    $images = [];
                    if ($slider__slides) {
                        foreach ($slider__slides as $key => $item) {
                            $slider__slide = \phpQuery::pq($item)->attr('href');
                            $imagelink = $product->source->url . "" . $slider__slide;
                            D::echor(" image_" . ($key + 1) . " = " . Html::a('link', $product->source->url . "" . $slider__slide));
                            array_push($images, $imagelink);
                        }
                    }
                    if ($images) $product->images = implode(" ", $images);


                    $item_page__info = $pq_page->find(".item-page__info")->text();
//
                    if (preg_match_all("/Модель:.+Мужская/", $item_page__info, $output_array)) $product->dimension = 1;
                    if (preg_match_all("/Модель:.+Детская/", $item_page__info, $output_array)) $product->dimension = 2;


                    // распечатывание свойств
                    //  $properties = ['name', 'sizes', 'meta_description', 'meta_keywords', 'images', 'color', 'description', 'price_old', 'price'];
                    $properties = ['name', 'price_old', 'price', 'dimension', 'sizes_rus', 'sizes', 'images'];
                    D::renderProperties($product, $properties);
                    D::echor("<hr> ");
                    return P::PAGE_EXISTS;
                }
            case 3:
                { // 2k-shop
                    // определяем размеры
                    //  body > div.page > div > div > div > div.item-wrapper > div.info-item > div.holder > dl > dd:nth-child(2)
                    if ($pq_page->find("font.errortext")->text()) return P::PAGE_NOT_FOUND;
                    $tree = $simple;

                  //  D::echor($tree->find("div.holder dl dd", 0));
                    $product->articul = $tree->find("div.holder dl dd", 0)->plaintext;
                    $razmer_nums = [];
                    D::echor("<br>" . $product->name);
                    $div_sizes = $tree->find("ul.size-list li a");
                    $sizes = [];
                    foreach ($div_sizes as $size) {
                        array_push($sizes, $size->plaintext);

                    }
                  if (count($sizes) == 0) {
                        D::alert(" РАЗМЕРОВ НЕТ", 'danger');
                      if ($product->hasSizes()) $product->status_source = Products::SOURCE_DISACTIVE;
                  } else {
                      if ($product->hasSizes()) $product->status_source = Products::SOURCE_ACTIVE;
                  }
                    $product->sizes = implode(";", $sizes);
                    // D::dump($sizes);
                    D::info(" РАЗМЕРНОСТЬ " . $pq_size);
                 //   d::alert("strong.old-price ".$tree->find("strong.old-price", 0)->plaintext);
                    $product->price_old = Parsing::ExtractNumders($tree->find("strong.old-price", 0)->plaintext);
                    if ($product->price_old) {
                        D::echor(" ЕСТЬ СТАРАЯ ЦЕНА");
                    }

                    $product->price = Parsing::ExtractNumders($tree->find("strong.price", 0)->plaintext);

                    D::echor("РАЗМЕРЫ" . Products::renderSizes(explode(";", $product->sizes)));
                    if (preg_match("/XXXS|XXS|XXL|XXXL|XXXXL/", $product->sizes)) {
                        $product->sizes = Clothessize::PREG_REPLACE($product->sizes);
                        D::echor(" ПРИВОДИМ В РАЗМЕРЫ В ЕДИНЫЙ ВИД " . Products::renderSizes(explode(";", $product->sizes)));
                    }


                    $div_slideset = $tree->find("div.slideset div.slide a img");
                    //   D::dump($div_slideset);
                    $images = [];
                    if ($div_slideset) {
                        foreach ($div_slideset as $key => $item) {
                            $link = $item->src;
                            $imagelink = $product->source->url . "" . $link;
                            D::echor(" image_" . ($key + 1) . " = " . Html::a($imagelink, $imagelink));
                            array_push($images, $imagelink);
                        }
                    }
                    if ($images) $product->images = implode(" ", $images);

                    // if (preg_match_all("/Модель:.+Мужская/", $item_page__info, $output_array)) $product->dimension = 1;
                    //  if (preg_match_all("/Модель:.+Детская/", $item_page__info, $output_array)) $product->dimension = 2;


                    // распечатывание свойств
                    //  $properties = ['name', 'sizes', 'meta_description', 'meta_keywords', 'images', 'color', 'description', 'price_old', 'price'];
                    $properties = ['articul', 'name', 'price_old', 'price', 'dimension', 'sizes_rus', 'sizes', 'images'];
                    D::renderProperties($product, $properties);
                    D::echor("<hr> ");
                    return P::PAGE_EXISTS;
                }
        }

        //sleep(0.3);


    }

    public static function ExtractNumders($input)
    {
        return preg_replace("/\D+/", '', $input);
    }
}