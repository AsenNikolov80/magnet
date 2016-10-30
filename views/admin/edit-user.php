<style>
    #user-active label {
        margin: 5px 10px;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 30.10.2016 г.
 * Time: 12:20
 */
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;

/* @var $user \app\models\User */
?>

<div class="row-fluid">
    <?php
    $form = ActiveForm::begin([
        'id' => 'edit-user',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-4 control-label', 'style' => 'color: black !important'],
        ],
    ]);
    ?>
    <?= $form->field($user, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($user, 'username') ?>
    <?= $form->field($user, 'email') ?>
    <?= $form->field($user, 'address') ?>
    <?= $form->field($user, 'paid_until')->input('text', ['value' => Yii::$app->formatter->asDate($user->paid_until)]) ?>
    <?= $form->field($user, 'active')->radioList([0 => 'Не', 1 => 'Да']) ?>
    <?php
    ActiveForm::end();
    ?>
</div>
<script>
    $(function () {
        var dateEl = $('#user-paid_until');
        dateEl.datepicker();
        dateEl.datepicker('option', 'dateFormat', 'yy-mm-dd');
        dateEl.val('<?=$user->paid_until?>');
    })
</script>