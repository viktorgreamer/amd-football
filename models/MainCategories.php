<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "main_categories".
 *
 * @property int $id id
 * @property string $name Название
 * @property int $time
 * @property int $status
 */
class MainCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['time', 'status'], 'integer'],
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
            'name' => 'AMD_SPORT Категория',
            'time' => 'Time',
            'status' => 'Status',
        ];
    }

    /**
     * @inheritdoc
     * @return MainCategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MainCategoriesQuery(get_called_class());
    }

    public function getCategories() {
        return $this->hasMany(Categories::className(), ['id_maincategory' => 'id']);

    }
    public function getMainsubcategories() {
        return $this->hasMany(MainSubcategories::className(), ['id_maincategory' => 'id']);

    }

    public static function getMap() {
        return ArrayHelper::map(MainCategories::find()->all(), 'id', 'name');
    }

}
