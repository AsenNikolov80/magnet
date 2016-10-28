<style>
    label{
        margin: 10px;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 28.10.2016 г.
 * Time: 22:23
 */
use yii\widgets\ActiveForm;
use app\models\Ticket;
use yii\helpers\Html;

$newTicket = new Ticket();
?>
<div class="col-sm-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'ads-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-3 control-label', 'style' => 'color: black !important'],
        ],
    ]);
    if (!empty($tickets)) {
        /* @var $ticket \app\models\Ticket */
        foreach ($tickets as $ticket) { ?>
            <div class="adsList">
                <label><?=$newTicket->getAttributeLabel('text')?>
                    <?= Html::textInput('text[]', $ticket->text) ?>
                </label>
                <label><?=$newTicket->getAttributeLabel('price')?>
                    <?= Html::textInput('price[]', $ticket->price) ?>
                </label>
            </div>
            <?php
        }
    } else { ?>
        <div>Нямате обяви до момента!</div>
        <label><?=$newTicket->getAttributeLabel('text')?>
            <?= Html::textInput('text[]') ?>
        </label>
        <label><?=$newTicket->getAttributeLabel('price')?>
            <?= Html::textInput('price[]') ?>
        </label>
    <?php }
    ActiveForm::end();
    ?>
</div>
