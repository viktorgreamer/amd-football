<?php

namespace app\models;

use Yii;
use app\utils\D;

/**
 * This is the model class for table "products_render".
 *
 * @property int $id id
 * @property string $category Категория
 * @property string $subcategory Подкатегория
 * @property string $name Название товара
 * @property string $short_description Короткое описание товара
 * @property string $description Полное описание товара
 * @property int $active Скрыт ли товар на сайте?
 * @property string $articul Артикул
 * @property string $price Цена продажи, без учёта скидок
 * @property string $price_old Старая цена
 * @property string $price_buy Закупочная цена
 * @property string $lost Остаток
 * @property string $ei Ед. измерения
 * @property string $images Изображения товара
 * @property string $short_seo Короткое SEO описание товара
 * @property string $full_seo Полное SEO описание товара
 * @property string $title_page Заголовок страницы товара
 * @property string $meta_keywords Мета-тег keywords
 * @property string $meta_description Мета-тег description
 * @property string $url URL переменная пути
 * @property string $brand Бренд
 * @property string $sizes Размеры
 * @property string $sizes_rus Русские Размеры
 * @property string $color Цвет
 */
class ProductsRender extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_render';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_description', 'description', 'images', 'short_seo', 'url'], 'string'],
            [['active'], 'integer'],
            [['ei', 'title_page', 'url'], 'required'],
            [['category', 'subcategory', 'name', 'articul', 'title_page', 'brand', 'color'], 'string', 'max' => 256],
            [['price', 'price_old', 'lost'], 'string', 'max' => 10],
            [['price_buy'], 'string', 'max' => 20],
            [['ei'], 'string', 'max' => 100],
            [['full_seo', 'sizes', 'sizes_rus', 'meta_keywords', 'meta_description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id2' => 'id в магазине',
            'category' => 'Категория',
            'subcategory' => 'Подкатегория',
            'name' => 'Название товара',
            'short_description' => 'Короткое описание товара',
            'description' => 'Полное описание товара',
            'disactive' => 'Скрыт ли товар на сайте?',
            'articul' => 'Артикул',
            'price' => 'Цена',
            'price_old' => 'Цена2',
            'price_buy' => 'Закупочная цена',
            'lost' => 'Остаток',
            'ei' => 'Ед.из.',
            'images' => 'Изображения',
            'short_seo' => 'Короткое SEO описание товара',
            'full_seo' => 'Полное SEO описание товара',
            'title_page' => 'Заголовок страницы товара',
            'meta_keywords' => 'Мета-тег keywords',
            'meta_description' => 'Мета-тег description',
            'url' => 'URL переменная пути',
            'brand' => 'Бренд',
            'sizes' => 'Размеры',
            'sizes_rus' => 'Русские Размеры',
            'color' => 'Цвет',
        ];
    }

    /**
     * @inheritdoc
     * @return ProductsRenderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsRenderQuery(get_called_class());
    }

    /* @var $product \app\models\Products */

    public function loadProduct($product, $maincategory, $mainsubcategory, $active, $size)
    {
        $this->id2 = $product->id;
        $this->category = $maincategory->name;
        $this->subcategory = $mainsubcategory->name;
        if ($product->brand) $this->brand = $product->brand->name; else $this->brand = $product->brand_text;
        $name = mb_strtolower($product->name, 'UTF-8');
       //   D::echor(" NAME =" . mb_ucfirst($name));
        $this->name = mb_ucfirst($name);
        $this->title_page = ucfirst($name);
        $this->description = trim($product->description);
        //  $this->price = $product->price;
        if ($product->price_old) $this->price_old = $product->price_old;
        else  $this->price_old = $product->price;
        $this->images = $product->images;
        $this->short_description = $product->short_description;
        //  $this->meta_keywords = $product->meta_keywords;
        //  $this->meta_description = $product->meta_description;
        //  $this->short_seo = $product->short_seo;
        //  $this->full_seo = $product->full_seo;
        $this->url = str2url($name);
        $this->lost = '1';
        $this->ei = 'шт.';
        $this->active = $active;
        if (($product->id_color) AND ($product->id_color != 99)) {
            $this->color = $product->color->rus;
            $this->articul = $product->articul;
            if ($product->id_source == 3) $this->articul .= " " . $product->color->articul_mod;
        } else {
            $this->articul = $product->articul;

        }

        // $this->sizes = $size;
        if ($product->hasSizes()) D::echor(" ДЕЛАЕМ ЗАПИСЬ С РАЗМЕРОМ ='" . Products::renderSize($size) . "'");
        else D::echor(" ДЕЛАЕМ ЗАПИСЬ БЕЗ РАЗМЕРА");
        $this->sizes_rus = $size;
    }
}
