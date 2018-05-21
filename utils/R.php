<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.05.2018
 * Time: 7:03
 */

namespace app\utils;


class R
{
    public static function asTable(array $array, $options = [])
    {

        if ($options['class']) $body = "<table class = \"".$options['class']."\">";
        else $body = "<table>";

        $body .= "<tr>";

        foreach ($array as $item) {
            $body .= "<td>" . $item . "</td>";
        }
        $body .= "</tr>";
        $body .= "</table>";

        return $body;
    }

    public static function renderAlertCount($text, $count ) {
        if ($count) {
          //  return "<div style='color:red'>".$text." (".$count.")</div>";
            return $text." (".$count.")";
        } else {
            return $text;
        }
    }

}