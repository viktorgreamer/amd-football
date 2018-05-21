<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MainSubcategories]].
 *
 * @see MainSubcategories
 */
class MainSubcategoriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MainSubcategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MainSubcategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
