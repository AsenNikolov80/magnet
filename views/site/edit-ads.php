<style>
    label {
        margin: 4px;
    }

    .addRow, .addRowFree {
        background-color: green;
        color: white;
        border-radius: 50%;
        padding: 5px 6px;
        cursor: pointer;
    }

    .removeRow, .removeRowFree {
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 5px 6px;
        cursor: pointer;
    }

    textarea {
        vertical-align: middle;
        height: 70px;
        margin: 5px;
    }

    input[type="text"] {
        max-width: 60px;
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

$this->params['breadcrumbs'][] = ['label' => 'Преглед профил', 'url' => Yii::$app->urlManager->createUrl('site/profile')];
$this->params['breadcrumbs'][] = 'Обяви';
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

<div class="newAdsFree" style="display: none">
    <label><?= $newTicket->getAttributeLabel('text') ?>
        <?= Html::textarea('text[free][]') ?>
    </label>
    <label>
        <i class="fa fa-plus addRowFree"></i>
        <i class="fa fa-minus removeRowFree"></i>
    </label>
</div>
<div class="row">
    <?php
    if(!empty($places)) {
        $form = ActiveForm::begin([
            'id' => 'ads-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-3 control-label', 'style' => 'color: black !important'],
            ],
        ]); ?>
        <div class="col-sm-12">
            <div>
                <?= Html::dropDownList('placeId', $selectedPlace->id, $places) ?>
                <h3>Списък промоции за обект: <?= $selectedPlace->name ?></h3>
            </div>

            <div id="content" class="row">
                <div class="col-sm-6">
                    <h4>Ценови промоции от вида продукт/услуга - цена</h4>
                    <?php
                    if (!empty($tickets)) {
                        /* @var $ticket \app\models\Ticket */
                        foreach ($tickets as $ticket) { ?>
                            <div class="adsList">
                                <label><?= $newTicket->getAttributeLabel('text') ?>
                                    <?= Html::textarea('text[' . $ticket->id . ']', $ticket->text) ?>
                                </label>
                                <label><?= $newTicket->getAttributeLabel('price') ?>
                                    <?= Html::textInput('price[' . $ticket->id . ']', $ticket->price, ['required' => true]) ?>
                                </label>
                                <label><i class="fa fa-minus removeRow"></i></label>
                            </div>
                            <?php
                        }
                    } else { ?>
                        <div>Нямате обяви до момента!</div>
                    <?php } ?>
                </div>
                <div class="col-sm-6" style="border-left: 1px solid #ccc">
                    <h4>Друг вид промоции (обяви в свободен текст)</h4>
                    <?php
                    foreach ($freeTextTickets as $freeTextTicket) { ?>
                        <div class="adsListFree">
                            <label><?= $newTicket->getAttributeLabel('text') ?>
                                <?= Html::textarea('text[free][' . $freeTextTicket->id . ']', $freeTextTicket->text) ?>
                            </label>
                            <label><i class="fa fa-minus removeRowFree"></i></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-12">
                <hr/>
                <button class="btn btn-primary">Запази</button>
            </div>
        </div>
        <?php ActiveForm::end();
    }else{
        echo '<h3>Нямате въведени обекти, трябва първо да създадете поне един обект, преди да обявите промоции за него!</h3>';
    }?>
</div>
<script>
    'use strict';
    var newAdDiv = $('.newAds').first().clone();
    var newAdDivFree = $('.newAdsFree').first().clone();
    function addRow() {
        $('.addRow').hide();
        $('.removeRow').show();
        newAdDiv.show();
        newAdDiv.find('.addRow').show();
        newAdDiv.find('.removeRow').hide();
        $('#ads-form #content>div.col-sm-6:first-child').append(newAdDiv[0].outerHTML);
    }
    function addRowFree() {
        $('.addRowFree').hide();
        $('.removeRowFree').show();
        newAdDivFree.show();
        newAdDivFree.find('.addRowFree').show();
        newAdDivFree.find('.removeRowFree').hide();
        $('#ads-form #content>div.col-sm-6:nth-child(2)').append(newAdDivFree[0].outerHTML);
    }

    function removeRow() {
        $(this).parent().parent().remove();
    }
    function removeRowFree() {
        $(this).parent().parent().remove();
    }

    $(function () {
        addRow();
        addRowFree();
        $(document).on('click', '.addRow', addRow);
        $(document).on('click', '.removeRow', removeRow);
        $(document).on('click', '.addRowFree', addRowFree);
        $(document).on('click', '.removeRowFree', removeRowFree);
        $('select[name="placeId"]').change(function () {
            window.location.replace('<?=Yii::$app->urlManager->createUrl('site/edit-ads')?>' + '?selectedPlace=' + $(this).val());
        });
    });
</script>