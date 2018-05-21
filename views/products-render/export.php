<?php
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.03.2018
 * Time: 5:30
 */

if ($files) {
    echo "<br>".Html::a("<span class=\"glyphicon glyphicon-remove-circle\" aria-hidden=\"true\"> удалить все</span>", ["/products-render/delete-file", 'filename' => 'all']);

    foreach ($files as $file) {
        echo "<br>".Html::a($file, "/web/export/".$file)."  ".Html::a("<span class=\"glyphicon glyphicon-remove-circle\" aria-hidden=\"true\"> удалить</span>", ["/products-render/delete-file", 'filename' => $file]);
    }

}