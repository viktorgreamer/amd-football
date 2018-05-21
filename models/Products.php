<?php

namespace app\models;

use app\components\ColorWidget;
use app\utils\R;
use Yii;
use app\utils\D;
use yii\helpers\Html;
use yii\helpers\Url;
use phpQuery;

/**
 * This is the model class for table "products".
 *
 * @property int $id id
 * @property int $time время проверки
 * @property int $id_subcategory id подкатегории
 * @property int $id_category id категории
 * @property int $id_source id ресурса
 * @property int $id_brand id бренда
 * @property string $id_in_source id в ресурсе
 * @property int $status_source
 * @property string $name Название товара
 * @property string $short_description Короткое описание товара
 * @property string $description Полное описание товара
 * @property int $disactive Скрыт ли товар на сайте?
 * @property int $dimension Размерность
 * @property string $articul Артикул
 * @property string brand_text Бренд
 * @property string $price Цена продажи, без учёта скидок
 * @property string $price_old Старая цена
 * @property string $price_buy Закупочная цена
 * @property string $lost Остаток
 * @property int $ei Ед. измерения
 * @property int $convert_sizes_status статус конвертации размера
 * @property string $images Изображения товара
 * @property string $sizes размеры в оригинале
 * @property string $sizes_rus русские размеры
 * @property string $short_seo Короткое SEO описание товара
 * @property string $full_seo Полное SEO описание товара
 * @property string $title_page Заголовок страницы товара
 * @property string $meta_keywords Мета-тег keywords
 * @property string $meta_description Мета-тег description
 * @property string $url URL переменная пути
 * @property string $title_modification Описание модификации товара
 * @property string $id2 Идентификатор товара в магазине
 * @property string $id_users Пользовательский идентификатор товара
 * @property string $name_setting Название х-ки товара №1
 * @property string $value Значение х-ки товара №1
 * @property string $name_property_modification Название св-ва для модификации товара №1
 * @property string $value_property_modification Значение св-ва для модификации товара №1
 */
class Products extends \yii\db\ActiveRecord
{
    const SOURCE_DISACTIVE = 0;
    const SOURCE_ACTIVE = 1;
    const RED = 1;
    const BLACK = 2;
    const YELLOY = 3;
    const LIGHT_BLUE = 4;
    const BLUE = 5;
    const WHITE = 6;
    const ORANGE = 7;
    const GREEN = 8;
    const SALAT = 9;

    const SIZE_NO = 0;
    const SIZE_FOOTS = 1;
    const SIZE_CLOTHES = 2;
    const SIZE_GETRES = 3;
    const SIZE_RUS = 8; // сразу русские
    const SIZE_DEFAULT = 9;

    const SIZE_CONVERT_STATUS_NOT = 0;
    const SIZE_CONVERT_STATUS_SUCCESS = 1;
    const SIZE_CONVERT_STATUS_ERROR = 2;
    const SIZE_CONVERT_STATUS_ERROR_FULL = 5;
    const SIZE_CONVERT_STATUS_MISSED = 3;

    const RENDERED_NOT = 0;
    const RENDERED_SUCCESS = 1;
    const RENDERED_ERROR = 2;
    const RENDERED_ERROR_VALIDATION = 3;
    const RENDERED_ERROR_CATEGORY = 4;
    const RENDERED_ERROR_SIZES = 5;

// !!!
// ATTANTION THESE NUMBERS OF CATEGORIES IS RESERVED FOR BROKEN_CATEGORIES
    const BROKEN_CATEGORY = 9998;
    const MANUAL_CATEGORY = 9999;


    public static function renderStatus($status = 0)
    {

        $array = [
            0 => "<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span>",
            1 => " <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>"
        ];
        if (in_array($status, [0, 1])) {
            return $array[$status];
        } else return $array;

    }

    public static function SourceStatuses()
    {
        return [
            0 => "НЕТ",
            1 => "ЕСТЬ"
        ];
    }

    public static function getColors($status = 1)
    {
        $array = [
            0 => "danger",
            1 => "success"
        ];

        return $array[$status];
    }

    public static function getRender_statuses($status = 0)
    {
        $array = [
            Products::RENDERED_NOT => 'НЕТ',
            Products::RENDERED_SUCCESS => 'ДА',
            Products::RENDERED_ERROR => 'ОШИБКА',
            Products::RENDERED_ERROR_VALIDATION => 'ОШИБКА ВАЛИДАЦИИ',
            Products::RENDERED_ERROR_CATEGORY => 'ОШИБКА КАТЕГОРИИ',
            Products::RENDERED_ERROR_SIZES => 'ОШИБКА РАЗМЕРОВ',

        ];
        if ($status) return $array[$status];
        else return $array;
    }

    public static function getSize_type($size_type = 0)
    {
        $array = [
            Products::SIZE_NO => 'НЕТ',
            Products::SIZE_FOOTS => 'ОБУВЬ',
            Products::SIZE_CLOTHES => 'ОДЕЖДА',
            Products::SIZE_GETRES => 'ГЕТРЫ',
            Products::SIZE_DEFAULT => 'КАК ЕСТЬ',
            Products::SIZE_RUS => 'РУССКИЕ',
        ];
        if ($size_type) return $array[$size_type];
        else return $array;
    }


    public static function renderDimension($dimension = 0)
    {
        $array = [
            1 => "Взрослая",
            2 => "Детская"
        ];

        if ($dimension) {
            return $array[$dimension];
        } else return $array;
    }

    public static function renderSize_statuses($status = null)
    {
        $array = [
            Products::SIZE_CONVERT_STATUS_NOT => "НЕТ",
            Products::SIZE_CONVERT_STATUS_SUCCESS => "Успешно",
            Products::SIZE_CONVERT_STATUS_ERROR => "Частично",
            Products::SIZE_CONVERT_STATUS_ERROR_FULL => "ОШИБКА",
            Products::SIZE_CONVERT_STATUS_MISSED => "ПРОПУСК",
        ];
        if ($status === NULL) return $array;
        return $array[$status];

    }

    public static function renderSize($text)
    {
        return "<span class=\"badge\" style='background-color: #0b58a2'><b>" . $text . "</b></span>";
    }

    public static function renderSizes($array)
    {
        $body = '';
        if (is_array($array)) {
            foreach ($array as $size) {
                $body .= Products::renderSize($size);
            }
        }
        return $body;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_source', 'status_source', 'disactive', 'ei', 'id_brand', 'manual_mainsubcategory', 'manual_maincategory', 'id_color'], 'integer'],
            [['short_description', 'description', 'images', 'short_seo', 'url', 'id_users', 'brand_text', 'source_link'], 'string'],
            [['id_in_source'], 'string', 'max' => 50],
            [['name', 'articul', 'title_page', 'title_modification', 'name_setting', 'value', 'name_property_modification', 'value_property_modification'], 'string', 'max' => 256],
            [['price', 'price_old', 'lost'], 'string', 'max' => 10],
            [['price_buy'], 'string', 'max' => 20],
            [['full_seo', 'meta_keywords', 'meta_description',], 'string', 'max' => 1000],
            [['id2'], 'string', 'max' => 30],

        ];
    }

    public function init()
    {

        $this->id_color = 0;
        parent::init(); // TODO: Change the autogenerated stub
    }





    public function getSameproductsQuery()
    {
        $query = Products::find()->where(['AND',
                ['<>', 'articul', ''],
                ['IS NOT', 'articul', NULL]]
        );

        if (($this->id_color) AND ($this->id_color != 99)) {
            $query->where(['AND',
                    ['articul' => $this->articul],
                    ['id_color' => $this->id_color]
                ]
            );
        } else {
            $query->andwhere(['articul' => $this->articul]);
        }

        // $query->andWhere(['<>', 'id', $product->id]);
        $query->andWhere(['status_source'=> Products::SOURCE_ACTIVE]);
        $query->andWhere(['in', 'convert_sizes_status', [1, 2]]);

        return $query;


    }

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'id_category']);

    }


    public function getSubcategory()
    {
        return $this->hasOne(Subcategories::className(), ['id' => 'id_subcategory']);

    }

    public function getColor()
    {
        return $this->hasOne(Colors::className(), ['id' => 'id_color']);

    }

    public function hasSizes()
    {

        if (($this->category->size_type) or ($this->subcategory->size_type) AND (!$this->no_sizes)) return true; else return false;

    }

    public static function creating($response, $category, $subcategory, $test = false, $options = [])
    {
        $source = Sources::find()->where(['id' => $category->id_source])->one();
        switch ($category->id_source) {
            case 1: // sportdepo
                $pq = phpQuery::newDocument($response);
                foreach ($pq->find('.main_karta') as $div_product) {
                    $pq_product = phpQuery::pq($div_product);
                    $product = new Products();
                    $product->id_category = $category->id;
                    $product->id_source = $category->id_source;
                    if ($options['color']) $product->color = $options['color'];
                    $product->id_subcategory = $subcategory->id;
                    $product->name = $pq_product->find('a')->attr('title');
                    $product->source_link = $source->url . "" . $pq_product->find('a')->attr('href');
                    preg_match("/product\/(\d+)\//", $product->source_link, $output_array);
                    $product->id_in_source = $output_array[1];
                    $product->status_source = Products::SOURCE_ACTIVE;

                    preg_match("/<meta itemprop=\"price\" content=\"(\d+)\">/", $pq_product->find('div.price')->html(), $output_array);
                    $product->price = $output_array[1];

                    preg_match("/<meta itemprop=\"sku\" content=\"(.+)\">/", $pq_product->html(), $output_array);
                    $product->articul = $output_array[1];

                    preg_match("/<meta itemprop=\"brand\" content=\"(.+)\">/U", $pq_product->html(), $output_array);
                    $product->brand_text = $output_array[1];

                    $existed = $product->isExist();

                    if (!$existed) {
                        D::echor("<br> СОЗДАЕМ ПРОДУКТ");
                        $product->time = time();
                        $product->calculateMaincategory();
                        $product->calculateMainsubcategory();
                        if (!$product->save()) D::dump($existed->getErrors());

                    } else {
                        $existed->price = $product->price;
                        if (!$existed->articul) $existed->articul = $product->articul;

                        D::echor("<br> ПРОДУКТ СУЩЕСТВУЕТ -> ОБНОВЛЯЕМ  ДАТУ ".$product->articul);

                        // если продукт имеет размеры то перепарсиваем его карточку
                        if ($existed->hasSizes()) $existed->parsed = 0;
                        $existed->time = time();
                        // распечатывание свойств
                        $properties = ['name', 'source_link', 'id_in_source', 'price', 'articul'];
                        foreach ($properties as $property) {
                            {
                                D::echor("<br> " . $property . " = <b>" . $existed[$property] . "</b>");

                            }

                        }
                      //  $product->calculateMaincategory();
                       // $product->calculateMainsubcategory();
                        if (!$existed->save()) D::dump($existed->getErrors());
                    }

                    // распечатывание свойств
                    $properties = ['name', 'source_link', 'id_in_source', 'price', 'articul', 'brand', 'color'];
                    foreach ($properties as $property) {
                        {
                            D::echor("<br> " . $property . " = <b>" . $product[$property] . "</b>");

                        }

                    }
                    D::echor("<hr> ");


                }
                break;

            case  2: // premier
                $pq = \phpQuery::newDocument($response);
                foreach ($pq->find('.product__wrapper') as $div_product) {
                    $pq_product = \phpQuery::pq($div_product);
                    $product = new Products();
                    $product->id_category = $category->id;
                    $product->id_source = $category->id_source;
                    //if ($options['color']) $product->color = $options['color'];
                    $product->id_subcategory = $subcategory->id;
                    $product->name = $pq_product->find('a.product__name')->text();
                    $product->source_link = $source->url . "" . $pq_product->find('a.product__name')->attr('href');
                    $product->id_in_source = $pq_product->find('.product-properties')->attr('data-id');

                    $product->status_source = Products::SOURCE_ACTIVE;

                    $product->price_old = preg_replace("/\s/", "", $pq_product->find(".product__price--old")->text());
                    if ($product->price_old) {
                        D::echor(" ЕСТЬ СТАРАЯ ЦЕНА");
                        $product->price = preg_replace("/\s/", "", $pq_product->find(".product__price")->text());
                        $product->price = preg_replace("/" . $product->price_old . "/", "", $product->price);
                    } else {
                        $product->price = preg_replace("/\s/", "", $pq_product->find(".product__price")->text());
                    }
                    $product->articul = $pq_product->find(".product-number")->text();
                    $product->brand_text = $pq_product->find('span.product__manufacturer')->text();;

                    $existed = $product->isExist();

                    if (!$existed) {
                        D::echor("<br> СОЗДАЕМ ПРОДУКТ");
                        $product->time = time();
                        $product->calculateMainsubcategory();
                        $product->calculateMaincategory();
                        if (!$product->save()) D::dump($product->getErrors());

                    } else {
                        if (!$existed->articul) $existed->articul = $product->articul;

                        D::echor("<br> ПРОДУКТ СУЩЕСТВУЕТ -> ОБНОВЛЯЕМ  ДАТУ ".$product->articul);

                        // если продукт имеет размеры то перепарсиваем его карточку
                        if ($existed->hasSizes()) $existed->parsed = 0;
                        $existed->time = time();
                        // распечатывание свойств
                       // D::renderProperties($product, ['name', 'source_link', 'id_in_source', 'price', 'price_old', 'articul', 'brand_text']);

                        if (!$existed->save()) D::dump($existed->getErrors());
                    }


                    // распечатывание свойств
                    D::renderProperties($product, ['name', 'source_link', 'id_in_source', 'price', 'price_old', 'articul', 'brand_text']);


                }

                break;

            case  3: // 2k
                $tree = str_get_html($response);
                foreach ($tree->find('div.product') as $div_product) {
                    $product = new Products();
                    if ($test) $product->test = 1;
                    $product->id_category = $category->id;
                    $product->id_source = $category->id_source;
                    //if ($options['color']) $product->color = $options['color'];
                    $product->id_subcategory = $subcategory->id;
                    $div_product_a_name = $div_product->find('a.name', 0);
                    $product->name = $div_product_a_name->plaintext;
                    $product->source_link = $source->url . "" . $div_product_a_name->href;
                    preg_match("/\/(\d+)\/\z/", $div_product_a_name->href, $output_array);
                    $product->id_in_source = $output_array[1];
                    $product->status_source = Products::SOURCE_ACTIVE;

                    d::alert("strong.old-price ".$div_product->find("strong.old-price", 0)->plaintext);
                    $product->price_old = Parsing::ExtractNumders($div_product->find("strong.old-price", 0)->plaintext);
                    if ($product->price_old) {
                        D::echor(" ЕСТЬ СТАРАЯ ЦЕНА");
                    }

                    $product->price = Parsing::ExtractNumders($div_product->find("strong.price", 0)->plaintext);

                    // $product->articul = $pq_product->find(".product-number")->text();

                    $product->brand_text = "2K Sport";;

                    $existed = $product->isExist();

                    if (!$existed) {
                        D::echor("<br> СОЗДАЕМ ПРОДУКТ");
                        $product->time = time();
                        $product->calculateMaincategory();
                        $product->calculateMainsubcategory();
                        if (!$product->save()) D::dump($product->getErrors());

                    } else {
                        $existed->price = $product->price;


                        D::echor("<br> ПРОДУКТ СУЩЕСТВУЕТ -> ОБНОВЛЯЕМ  ДАТУ");

                        // если продукт имеет размеры то перепарсиваем его карточку
                        if ($existed->hasSizes()) $existed->parsed = 0;
                        $existed->time = time();
                        if (!$existed->save()) D::dump($existed->getErrors());
                    }


                    // распечатывание свойств
                    D::renderProperties($product, ['name', 'source_link', 'id_in_source', 'price', 'price_old', 'articul', 'brand_text']);


                }

                break;
        }


    }

    public function isExist()
    {
        $product = Products::find()
            ->where(['like', 'source_link', $this->source_link])
            ->one();
        return $product;

    }

    // calculate maincategory from global tree of maincategories
    // return instanse of Categogies of false if cannot calculate it

    public function calculateMaincategory()
    {

        $maincategory = $this->category->maincategory;
        if (!$maincategory) {
             D::info(" DID NOT FIND MAINCATEGORY BY ->category->maincategory", 'danger');
            $maincategory = $this->category->mainsubcategory->maincategory;
        } else {
            D::info(" FOUND MAINCATEGORY BY ->category->maincategory", 'success');

        }
        if (!$maincategory) {
               D::info(" DID NOT FIND MAINCATEGORY BY ->category->mainsubcategory->maincategory", 'danger');
            $maincategory = $this->subcategory->mainsubcategory->maincategory;
        }
        if (!$maincategory) {
             D::info(" DID NOT FIND MAINCATEGORY BY ->subcategory->mainsubcategory->maincategory", 'danger');
            if ($this->manual_mainsubcategory) $mainsubcategory = MainSubcategories::findOne($this->manual_mainsubcategory);
            $maincategory = $mainsubcategory->maincategory;

        }
        if (!$maincategory) {
            D::info(" DID NOT FIND MAINCATEGORY BY ->subcategory->mainsubcategory->maincategory", 'danger');
            if ($this->manual_maincategory) $maincategory = MainCategories::findOne($this->manual_maincategory);

        }


        if ($maincategory) {
           // D::echor("ПРИСВАИВАЕМ ГЛАНУЮ КАТЕГОРИЮ ".$maincategory->id);
            $this->id_maincategory = $maincategory->id;
            return $maincategory;
        } else {
            $this->id_maincategory = self::BROKEN_CATEGORY;
            D::info(" DID NOT FIND MAINCATEGORY BY MainCategories::findOne($this->manual_maincategory)", 'danger');
            return false;
        }
    }

    public function getMaincategory()
    {
        return $this->hasOne(MainCategories::className(), ['id' => 'id_maincategory']);
    }

    public function getMainsubcategory()
    {
        return $this->hasOne(MainSubcategories::className(), ['id' => 'id_mainsubcategory']);
    }

    public function calculateMainsubcategory()
    {

        $mainsubcategory = $this->subcategory->mainsubcategory;
        if (!$mainsubcategory) {
            $mainsubcategory = $this->category->mainsubcategory;
        }

        if (!$mainsubcategory) {
            if ($this->manual_mainsubcategory) $mainsubcategory = MainSubcategories::findOne($this->manual_mainsubcategory);

        }

        if ($mainsubcategory) {

            $this->id_mainsubcategory = $mainsubcategory->id;
            return $mainsubcategory;
        } else {
            $this->id_mainsubcategory = self::BROKEN_CATEGORY;;
            return false;
        }
    }


    public function getSource()
    {
        return $this->hasOne(Sources::className(), ['id' => 'id_source']);
    }

    public function getBrand()
    {
        return $this->hasOne(Brands::className(), ['id' => 'id_brand']);
    }

    public function info()
    {
        return R::asTable(
            [
                $this->name,
                Html::a($this->source->name, $this->source_link, ['target' => "_blank"]),
                "АРТИКУЛ = " . $this->articul,
                ColorWidget::widget(['colors' => $this->color->code])
            ], ['class' => 'table table-bordered']);

    }


    public function beforeSave($insert)
    {

        if (preg_match("/XXXS|XXS|XXL|XXXL|XXXXL/", $this->sizes)) {
            $this->sizes = Clothessize::PREG_REPLACE($this->sizes);
            D::echor(" ПРИВОДИМ В РАЗМЕРЫ В ЕДИНЫЙ ВИД " . Products::renderSizes(explode(";", $this->sizes)));
        }
        if (preg_match("/XXXS|XXS|XXL|XXXL|XXXXL/", $this->sizes_rus)) {
            $this->sizes_rus = Clothessize::PREG_REPLACE($this->sizes_rus);
            D::echor(" ПРИВОДИМ В РАЗМЕРЫ В ЕДИНЫЙ ВИД " . Products::renderSizes(explode(";", $this->sizes_rus)));
        }
        $this->sizes_rus = preg_replace("/,/", ".", $this->sizes_rus);
        $this->sizes = preg_replace("/,/", ".", $this->sizes);


        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'id_source' => 'Источник',
            'id_in_source' => 'id в ресурсе',
            'status_source' => 'Наличие',
            'name' => 'Название товара',
            'short_description' => 'Короткое описание товара',
            'id_brand' => 'Brand',
            'description' => 'Полное описание товара',
            'disactive' => 'Скрыт ли товар на сайте?',
            'articul' => 'Артикул',
            'price' => 'Цена',
            'price_old' => 'Старая цена',
            'price_buy' => 'Закупочная цена',
            'lost' => 'Остаток',
            'ei' => 'Ед. измерения',
            'images' => 'Изображения товара',
            'short_seo' => 'Короткое SEO описание товара',
            'full_seo' => 'Полное SEO описание товара',
            'title_page' => 'Заголовок страницы товара',
            'meta_keywords' => 'Мета-тег keywords',
            'meta_description' => 'Мета-тег description',
            'url' => 'URL переменная пути',
            'title_modification' => 'Описание модификации товара',
            'id2' => 'Идентификатор товара в магазине',
            'name_setting' => 'Название х-ки товара №1',
            'value' => 'Значение х-ки товара №1',
            'name_property_modification' => 'Название св-ва для модификации товара №1',
            'value_property_modification' => 'Значение св-ва для модификации товара №1',
        ];
    }

    /**
     * @inheritdoc
     * @return SourcesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SourcesQuery(get_called_class());
    }
}
