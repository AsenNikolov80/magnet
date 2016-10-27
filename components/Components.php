<?php


namespace app\components;

use Yii;

class Components
{
    public static function printFlashMessages($errorKey = null)
    {
        if ($errorKey === null) {
            $messages = Yii::$app->session->getAllFlashes(true);
        } else {
            $messages = [Yii::$app->session->getFlash($errorKey, null, true)];
        }
        static::renderFlashMessage($messages);
    }

    private static function renderFlashMessage(array $messages)
    {
        foreach ($messages as $errorKey => $message) {
            // TODO add more cases!
            switch ($errorKey) {
                case 'error':
                case 'errorAttribute':
                    echo '<div class="alert alert-warning" style="text-align: center">' . $message . '</div>';
                    break;
                case 'success':
                    echo '<div class="alert alert-success" style="text-align: center">' . $message . '</div>';
                    break;
            }
        }
    }

}