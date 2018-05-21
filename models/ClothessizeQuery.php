<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Clothessize]].
 *
 * @see Clothessize
 */
class ClothessizeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Clothessize[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Clothessize|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
