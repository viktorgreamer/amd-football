<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Products;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Processing;
use app\utils\D;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionCron()
    {
        if (!Processing::parsingCategories()) {
            if (!Processing::DetailedParsing(20)) {
                if (!Processing::Branding()) {
                    if (!Processing::ConvertSizes(100)) {
                        if (!Processing::Render()) {
                            D::info(" ОБРАБОТКА ЗАКОНЧЕНА");
                        }


                    };
                }
            };
        };
    }
}
