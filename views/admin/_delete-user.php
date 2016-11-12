<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 29.10.2016 г.
 * Time: 23:42
 */

\app\components\Components::printFlashMessages();
/* @var $user \app\models\User */
if ($user) {
    echo 'Сигурни ли сте, че искате да изтриете потребител - <strong>' . $user->username . '</strong> с фирма <strong>' . $user->name . '</strong>?';
    echo \yii\bootstrap\Html::hiddenInput('userId', $user->id);
}
?>