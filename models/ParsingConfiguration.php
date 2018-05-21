<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ParsingConfiguration".
 *
 * @property int $id
 * @property string $url
 * @property int $time
 * @property int $stop_page
 * @property int $id_source
 * @property int $type
 */
class ParsingConfiguration extends \yii\db\ActiveRecord
{
    const TYPE_PRODUCTS = 1;
    const TYPE_CATEGORIES = 2;
    const TYPE_SUBCATEGORIES = 3;


    public static function getTYPES()
    {

        return [
            ParsingConfiguration::TYPE_PRODUCTS => 'Товары',
            ParsingConfiguration::TYPE_CATEGORIES => 'Категории',
            ParsingConfiguration::TYPE_SUBCATEGORIES => 'Подкатегории'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ParsingConfiguration';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'id_source', 'type'], 'required'],
            [['url', 'name'], 'string'],
            [['time', 'stop_page', 'id_source', 'type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'time' => 'Time',
            'name' => 'Name',
            'stop_page' => 'Stop Page',
            'id_source' => 'Id Source',
            'type' => 'Id type',
        ];
    }

    /**
     * @inheritdoc
     * @return ParsingConfigurationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParsingConfigurationQuery(get_called_class());
    }

    public function getSource()
    {
        return $this->hasOne(Sources::className(), ['id' => 'id_source']);
    }
}
