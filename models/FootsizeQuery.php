<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Footsize]].
 *
 * @see Footsize
 */
class FootsizeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Footsize[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Footsize|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
