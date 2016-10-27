<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 22.10.2016 г.
 * Time: 20:04
 */
/* @var $user \app\models\User */
?>
<div class="row-fluid">
    <div><h3>Добре дошли отново, <?= $user->first_name . ' ' . $user->last_name ?>!</h3></div>
    <?php
    if ($user->active == 0) { ?>
        <div class="alert alert-warning text-center" style="font-size: 1.5em">
            Профилът Ви още не е одобрен от администратор!
        </div>
    <?php } ?>
</div>