<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MainCategories]].
 *
 * @see MainCategories
 */
class MainCategoriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MainCategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MainCategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
