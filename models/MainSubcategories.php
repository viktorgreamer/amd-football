<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\utils\D;
/**
 * This is the model class for table "main_subcategories".
 *
 * @property int $id id
 * @property int $id_maincategory Id  Категории
 * @property string $name Название подкатеоргии
 * @property int $time
 * @property int $status
 * @property int $size_type типо размер
 */
class MainSubcategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'main_subcategories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_maincategory', 'name'], 'required'],
            [['id_maincategory', 'time', 'status'], 'integer'],
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
            'id_maincategory' => 'Id  Категории',
            'name' => 'Название подкатеоргии',
            'time' => 'Time',
            'status' => 'Status',
        ];
    }

    /**
     * @inheritdoc
     * @return MainSubcategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MainSubcategoriesQuery(get_called_class());
    }

    public function getSubcategories() {
        return $this->hasMany(Subcategories::className(), ['id_mainsubcategory' => 'id']);

    }

    public function getCategories() {
       // D::echor(" ИСпользуем категории");
        return $this->hasMany(Categories::className(), ['id_mainsubcategory' => 'id']);

    }

    public function getMaincategory() {
        return $this->hasOne(MainCategories::className(), ['id' => 'id_maincategory']);

    }

    public static function getMap($id_maincategory = null) {

        $items = MainSubcategories::find()
            ->filterWhere(['id_maincategory' => $id_maincategory])
            ->orderBy('id_maincategory')
            ->all();

        $items_map = [];
        if ($items) {
            foreach ($items as $item) {
               if (!$id_maincategory) $items_map[$item->id] = $item->maincategory->name ." -->>".$item->name;
               else $items_map[$item->id] = $item->name;
            }
        }

        return $items_map;
    }
}
