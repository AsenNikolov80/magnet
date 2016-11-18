<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 18.11.2016 г.
 * Time: 21:14
 */

namespace app\components;

use app\models\User;
use Yii;

class FileComponent
{
    public $filePathProforma;
    public $filePathFactura;

    public function __construct()
    {
        $currentUser = $this->getCurrentUser();
        $this->filePathProforma = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web'
            . DIRECTORY_SEPARATOR . 'proforma'
            . DIRECTORY_SEPARATOR . $currentUser->username . DIRECTORY_SEPARATOR;
        $this->filePathFactura = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web'
            . DIRECTORY_SEPARATOR . 'facturi'
            . DIRECTORY_SEPARATOR . $currentUser->username . DIRECTORY_SEPARATOR;
    }

    /**
     * @return bool|User
     */
    private function getCurrentUser()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Няма такъв потребител!');
            return false;
        }
        return $user;
    }
}