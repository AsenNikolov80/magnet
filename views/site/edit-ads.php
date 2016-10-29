<style>
    label {
        margin: 10px;
    }

    .addRow {
        background-color: green;
        color: white;
        border-radius: 50%;
        padding: 5px 6px;
        cursor: pointer;
    }

    .removeRow {
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 5px 6px;
        cursor: pointer;
    }

    textarea {
        vertical-align: middle;
        resize: vertical;
        height: 70px;
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

<div class="newAds" style="display: none">
    <label><?= $newTicket->getAttributeLabel('text') ?>
        <?= Html::textarea('text[]') ?>
    </label>
    <label><?= $newTicket->getAttributeLabel('price') ?>
        <?= Html::textInput('price[]') ?>
    </label>
    <label>
        <i class="fa fa-plus addRow"></i>
        <i class="fa fa-minus removeRow"></i>
    </label>
</div>
<div class="col-sm-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'ads-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-3 control-label', 'style' => 'color: black !important'],
        ],
    ]); ?>
    <div id="content">
        <?php
        if (!empty($tickets)) {
            /* @var $ticket \app\models\Ticket */
            foreach ($tickets as $ticket) { ?>
                <div class="adsList">
                    <label><?= $newTicket->getAttributeLabel('text') ?>
                        <?= Html::textarea('text[' . $ticket->id . ']', $ticket->text) ?>
                    </label>
                    <label><?= $newTicket->getAttributeLabel('price') ?>
                        <?= Html::textInput('price[' . $ticket->id . ']', $ticket->price) ?>
                    </label>
                    <label><i class="fa fa-minus removeRow"></i></label>
                </div>
                <?php
            }
        } else { ?>
            <div>Нямате обяви до момента!</div>
        <?php } ?>
    </div>
    <div class="col-sm-12">
        <hr/>
        <button class="btn btn-primary">Запази</button>
    </div>
    <?php ActiveForm::end();
    ?>
</div>
<script>
    'use strict';
    var newAdDiv = $('.newAds').first().clone();
    function addRow() {
        $('.addRow').hide();
        $('.removeRow').show();
        newAdDiv.show();
        newAdDiv.find('.addRow').show();
        newAdDiv.find('.removeRow').hide();
        $('#ads-form #content').append(newAdDiv[0].outerHTML);
    }

    function removeRow() {
        $(this).parent().parent().remove();
    }

    $(function () {
        addRow();
        $(document).on('click', '.addRow', addRow);
        $(document).on('click', '.removeRow', removeRow);
    });
</script>