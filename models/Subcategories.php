<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "subcategories".
 *
 * @property int $id id
 * @property int $id_category Id  Категории
 * @property string $name Название подкатеоргии
 * @property int $link
 * @property int $size_type типо размер
 */
class Subcategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const SHOPS_URL_MODIFICATION = "?shops=%5B1%5D%5B2%5D%5B4%5D%5B11%5D%5B3%5D%5B6%5D%5B14%5D%5B16%5D%5B18%5D%5B12%5D%5B28%5D";



    public static function tableName()
    {
        return 'subcategories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_category', 'name', 'link'], 'required'],
            [['id_category', 'id_mainsubcategory'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'id_category' => 'Id  Категории',
            'name' => 'Название подкатеоргии',
            'link' => 'Link',
        ];
    }

    /**
     * @inheritdoc
     * @return SubcategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubcategoriesQuery(get_called_class());
    }

    public function isExisted() {
        return Subcategories::find()
            ->where(['id_category' => $this->id_category])
            ->andWhere(['link' => $this->link])
            ->exists();
    }

    public function getCategory() {
        return $this->hasOne(Categories::className(), ['id' => 'id_category']);
    }


    public function getMapSubcategories($id_category = 0) {
        return ArrayHelper::map(Subcategories::find()->filterwhere(['id_category' => $id_category])->all(), 'id', 'name');
    }

    public function getMainsubcategory() {
        return $this->hasOne(MainSubcategories::className(), ['id' => 'id_mainsubcategory']);
    }


 }
