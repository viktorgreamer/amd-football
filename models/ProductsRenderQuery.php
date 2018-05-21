<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductsRender]].
 *
 * @see ProductsRender
 */
class ProductsRenderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProductsRender[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductsRender|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
