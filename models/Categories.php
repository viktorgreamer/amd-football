<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\utils\D;
use app\models\MainSubcategories;
/**
 * This is the model class for table "categories".
 *
 * @property int $id id
 * @property string $name Название
 * @property int $id_source id_source
 * @property int $id_maincategory id_maincategory
 * @property string $link ссылка
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'link', 'id_source'], 'required'],
            [['id_maincategory','id_mainsubcategory'], 'integer'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'Название',
            'id_source' => 'Ресурс',
            'id_maincategory' => 'Главная Категория',
        ];
    }

    /**
     * @inheritdoc
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesQuery(get_called_class());
    }



    public function getSource() {
        return $this->hasOne(Sources::className(), ['id' => 'id_source']);
    }

    public function isExisted() {
        return Categories::find()
            ->where(['id_source' => $this->id_source])
            ->andWhere(['link' => $this->link])
            ->exists();
    }

    public function getMaincategory() {
        return $this->hasOne(MainCategories::className(), ['id' => 'id_maincategory']);
    }
    public function getMainsubcategory() {
        return $this->hasOne(MainSubcategories::className(), ['id' => 'id_mainsubcategory']);
    }




    public static function getMapCategories($id_source = null) {

        return ArrayHelper::map(Categories::find()->filterWhere(['id_source' => $id_source])->all(), 'id','name');

    }
    public static function getCategories($id_source = null) {

        return Categories::find()->select('id')->filterWhere(['id_source' => $id_source])->column();

    }

    public function getSubcategories() {
        return $this->hasMany(Subcategories::className(), ['id_category' => 'id']);
    }

    public static function creating($response, $source) {

        switch ($source->id) {
            case 1: // если sportdepo.ru
                {
                    $pq = \phpQuery::newDocument($response);
                    $categories = $pq->find(".catalog");
                    foreach ($categories as $category) {
                        $category_html = \phpQuery::pq($category)->find('a');
                        $category_new = new Categories();
                        $category_new->name = $category_html->attr('title');
                        $category_new->id_source = 1;
                        $category_new->link = $source->url ."". $category_html->attr('href');
                        D::echor("<br>link =" . Html::a($category_new->name, $category_new->link, ['target' => '_blank']));
                        if (!$category_new->isExisted()) $category_new->save();
                        else {
                            D::echor("<br> Данная категория уже существует");
                            $sub_categories_html = \phpQuery::pq($category)->find('h3');
                            $category = Categories::find()->where(['link' => $category_new->link])->one();
                            foreach ($sub_categories_html as $sub_category_html) {
                                $sub_category_html = \phpQuery::pq($sub_category_html)->find('a');
                                $sub_category = new Subcategories();
                                $sub_category->id_category = $category->id;
                                $sub_category->link = $source->url . "" . $sub_category_html->attr('href');
                                $sub_category->name = $sub_category_html->attr('title');
                                D::echor("<br>SUBCATEGORY =" . Html::a($sub_category->name, $sub_category->link, ['target' => '_blank']));
                                if (!$sub_category->isExisted())  {
                                    if (!$sub_category->save()) D::dump($sub_category->getErrors());
                                }
                                else {
                                    D::echor("<br> Данная кодкатегория уже существует");
                                }

                            }
                            }



                    }

                }
        }


    }
}
