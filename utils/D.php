<?

namespace app\utils;

class D
{
    private static $dumpVars;

    public static function dump($var)
    {
        ob_start();
        echo "<pre>";
        var_dump($var);
        echo "</pre>";

        static::$dumpVars[] = ob_get_clean();
    }
    public static function echor($var, $br = true)
    {
        ob_start();
       if ($br) echo "<br>".$var; else echo $var;
        static::$dumpVars[] = ob_get_clean();
    }
    public static function info($var, $type = "primary")
    {
        ob_start();
        echo "<br>"."<span class=\"label label-".$type."\">".$var."</span>";
        static::$dumpVars[] = ob_get_clean();
    }
    public static function alert($var, $type = "primary")
    {
        ob_start();
        echo "<div class=\"alert alert-".$type."\">".$var."</div>";
        static::$dumpVars[] = ob_get_clean();
    }

    public static function printr()
    {
        if (YII_DEBUG && isset(static::$dumpVars)) {
            foreach (static::$dumpVars as $var) {
                echo $var;
            }
        }
    }
    public static function renderProperties($model, $properties) {

        // распечатывание свойств
        foreach ($properties as $property) {
            {
                D::echor($property . " = <b>" . $model[$property] . "</b>");

            }

        }
        D::echor("<hr> ");

    }
}