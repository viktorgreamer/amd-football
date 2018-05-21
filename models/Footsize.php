<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "footsize".
 *
 * @property int $id
 * @property int $id_brand
 * @property int $dimension
 * @property string $uk
 * @property string $rus
 * @property string $eur
 * @property string $us
 * @property string $cm
 * @property string $cm2
 * @property string $jp
 */
class Footsize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'footsize';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_brand'], 'required'],
            [['id_brand','dimension'], 'integer'],
            [['uk', 'rus', 'eur', 'us', 'cm', 'cm2','jp'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_brand' => 'Id Brand',
            'uk' => 'Uk',
            'rus' => 'Rus',
            'eur' => 'Eur',
            'us' => 'Us',
            'cm' => 'Cm',
            'cm2' => 'Cm2',
        ];
    }

    /**
     * @inheritdoc
     * @return FootsizeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FootsizeQuery(get_called_class());
    }

    public function getBrand() {
        return $this->hasOne(Brands::className(), ['id' => 'id_brand']);
    }
}
