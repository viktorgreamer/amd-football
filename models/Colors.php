<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "colors".
 *
 * @property integer $id
 * @property string $code
 * @property string $articul_mode
 * @property string $rus
 * @property string $eng
 */
class Colors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'colors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'articul_mod'], 'string', 'max' => 10],
            [['rus', 'eng'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'rus' => 'Rus',
            'eng' => 'Eng',
            'articul_mod' => 'Модификация артикула',
        ];
    }
}
