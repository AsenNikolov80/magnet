<?php
/**
 * A autocompletion and type hinting convenience method.
 *
 * Keep the PHPDoc definitions of DoceboWebApplication and DoceboConsoleApplication
 * up to date below, with the virtually defined Yii components (defined in web.php or console.php)
 */
class Yii extends \yii\BaseYii
{
    public function __construct()
    {
        throw new \yii\base\Exception('Invalid application instance');
    }

    /**
     * @var DoceboBaseApplication|\yii\web\Application|\yii\console\Application
     */
    public static $app;
}

/**
 * Add properties for autocompletion convenience below
 *
 * @property \app\components\CUser $user
 * @property \yii\db\Connection $db
 */
abstract class DoceboBaseApplication extends yii\base\Application
{
}