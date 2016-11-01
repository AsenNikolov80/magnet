<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 22.10.2016 г.
 * Time: 19:52
 */

namespace app\components;


use yii\db\Query;
use app\models\User;

class CUser extends \yii\web\User
{
    public function isUserAdmin()
    {
        return $this->getUserType() == 2;
    }

    public function isUserCompany()
    {
        return $this->getUserType() == 1;
    }

    public function isUser()
    {
        return $this->getUserType() == 0;
    }

    public function isUserActive()
    {
        if (!$this->isUserCompany()) {
            return true;
        } else {
            $data = (new Query())->select('active, paid_until')
                ->from(User::tableName())
                ->where(['id' => \Yii::$app->user->id])->one();
            return $data['active'] == 1 && $data['paid_until'] >= date('Y-m-d');
        }
    }

    private function getUserType()
    {
        $type = (new Query())->select('type')
            ->from(User::tableName())
            ->where(['id' => \Yii::$app->user->id])->scalar();
        return $type;
    }
}