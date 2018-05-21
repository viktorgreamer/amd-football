<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ParsingConfiguration]].
 *
 * @see ParsingConfiguration
 */
class ParsingConfigurationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ParsingConfiguration[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ParsingConfiguration|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
