<?php

namespace app\models;

use app\utils\D;
use Yii;

/**
 * This is the model class for table "clothessize".
 *
 * @property int $id
 * @property int $dimension
 * @property string $id_bland
 * @property string $mark
 * @property string $size
 * @property string $rus
 * @property string $growth
 * @property string $width
 * @property string $height
 */
class Clothessize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clothessize';
    }

    public static function Similar($size)
    {
        $array = [
            'XXS' => '2XS',
            'XXXS' => '3XS',
            'XXL' => '2XL',
            'XXXL' => '3XL',
            'XXXXL' => '4XL',

        ];
        return $array[$size];

    }

    public static function PREG_REPLACE($sizes)
    {

        $sizes = explode(";", $sizes);
      //  D::dump($sizes);
        $array = [
            'XXS' => '2XS',
            'XXXS' => '3XS',
            'XXL' => '2XL',
            'XXXL' => '3XL',
            'XXXXL' => '4XL',

        ];

        $new_sizes = [];
        foreach ($sizes as $size) {

            foreach ($array as $key => $item) {
                if ($key == $size) $size = $item;
            }
            array_push($new_sizes, $size);


        }


        return implode(";", $new_sizes);


    }

    /**
     * @inheritdoc
     */
    public
    function rules()
    {
        return [
            [['id_brand'], 'integer'],
            [['mark', 'size', 'rus'], 'string', 'max' => 15],
            [['mark', 'size', 'rus'], 'string', 'max' => 15],
            [['growth', 'width', 'height'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public
    function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dimension' => 'Размерность',
            'id_brand' => 'Brand',
            'mark' => 'Маркировка',
            'size' => 'размер',
            'rus' => 'Рос.размер',
            'growth' => 'Рост',
            'width' => 'Ширина',
            'height' => 'Высота',
            'age' => 'Возраст',
        ];
    }

    /**
     * @inheritdoc
     * @return ClothessizeQuery the active query used by this AR class.
     */
    public
    static function find()
    {
        return new ClothessizeQuery(get_called_class());
    }

    public
    function getBrand()
    {
        return $this->hasOne(Brands::className(), ['id' => 'id_brand']);
    }
}
