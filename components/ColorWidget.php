<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class ColorWidget extends Widget
{
    public $colors;
    public $select;
    public $id;

    public function init()
    {
      if (!$this->select) if (!is_array($this->colors)) $this->colors = [$this->colors];
    }

    public function run()
    {
if ($this->select) return $this->render('select-color', ['id' => $this->id]);
       else  return $this->render('color', ['colors' => $this->colors]);
    }
}