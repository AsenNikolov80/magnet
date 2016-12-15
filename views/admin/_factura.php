<style>
    td {
        border: 1px solid black;
    }

    .center {
        text-align: center;
        line-height: 60px;
    }

    .inner td {
        border: none;
    }

    .noBorder {
        border: none;
    }

</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 14.11.2016 г.
 * Time: 20:56
 */
/* @var $model \app\models\InvoiceData */
?>
<table style="line-height: 20px">
    <tr style="font-size: 20px">
        <?php
        if ($type == \app\components\FileComponent::TYPE_PROFORMA) { ?>
            <td class="center" colspan="10">
                <?= $type ?>
            </td>
        <?php } elseif ($type == \app\components\FileComponent::TYPE_FACTURA) { ?>
            <td class="center" colspan="5">
                <?= $type ?>
            </td>
            <td colspan="5" class="center"><?=$origin?></td>
        <?php } ?>

    </tr>
    <tr>
        <td colspan="5">
            <table>
                <tr>
                    <td style="text-align: left;border: none;line-height: 45px">
                        Номер: <?= $model->number > 99999 ? '0000' . $model->number : '00000' . $model->number ?>
                    </td>
                    <td style="text-align: right;border: none;line-height: 45px">Дата: <?= $model->date ?> &nbsp;&nbsp;&nbsp;</td>
                </tr>
            </table>
        </td>
        <td colspan="5">
            <table class="inner" style="line-height: 15px">
                <tr>
                    <td colspan="17"></td>
                </tr>
                <tr>
                    <td style="border: 1px solid black"></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;Кредитно</td>
                    <td style="border: 1px solid black"></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;Дебитно</td>
                    <td colspan="7" style="font-size: 0.75em"> Към ф-ра</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;известие</td>
                    <td></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;известие</td>
                    <td colspan="7">№................</td>
                </tr>
                <tr>
                    <td colspan="17"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="line-height: 11px">
        <td colspan="5" class="noBorder"></td>
        <td colspan="5" class="noBorder"></td>
    </tr>
    <tr>
        <td colspan="2" style="border-right: 1px solid black">
        </td>
        <td colspan="3"></td>
        <td colspan="5" class="center" style="font-size: 1.3em;line-height: 30px">
            <strong><?= $model->senderData['company_name'] ?></strong>
        </td>
    </tr>
    <tr style="font-size: 0.85em;">
        <td colspan="2" style="border-right: 1px solid black">
            <strong>Получател:</strong>
        </td>
        <td colspan="3"> <?= $model->rec_name ?></td>
        <td style="border-right: 1px solid black" colspan="2"><strong> Адрес:</strong></td>
        <td colspan="3">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['address'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="font-size: 0.85em;">
        <td colspan="2" style="border-right: 1px solid black">
            <strong>Адрес:</strong>
        </td>
        <td colspan="3"> <?= $model->rec_address ?></td>
        <td colspan="2" style="border-right: 1px solid black"><strong> Място на сделката:</strong>
        </td>
        <td colspan="3">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['address'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="font-size: 0.85em;">
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px">
            <strong>Държава:</strong>
        </td>
        <td colspan="3" style="line-height: 25px"> <?= $model->rec_country ?></td>
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px"><strong> Държава:</strong></td>
        <td colspan="3" style="line-height: 20px">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['country'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="font-size: 0.85em;">
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px">
            <strong>ЕИК:</strong>
        </td>
        <td colspan="3" style="line-height: 25px"> <?= $model->rec_bulstat ?></td>
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px"><strong> ЕИК:</strong></td>
        <td colspan="3" style="line-height: 20px">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['bulstat'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="font-size: 0.85em;">
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px">
            <strong>ИН по ЗДДС:</strong>
        </td>
        <td colspan="3" style="line-height: 25px"> <?= $model->rec_dds ?></td>
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px"><strong> ИН по ЗДДС:</strong></td>
        <td colspan="3" style="line-height: 20px">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['dds'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="font-size: 0.85em;">
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px">
            <strong>МОЛ:</strong>
        </td>
        <td colspan="3" style="line-height: 25px"> <?= $model->rec_mol ?></td>
        <td colspan="2" style="border-right: 1px solid black;line-height: 25px"><strong> МОЛ:</strong></td>
        <td colspan="3" style="line-height: 20px">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['mol'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="line-height: 11px">
        <td colspan="5" class="noBorder"></td>
        <td colspan="5" class="noBorder"></td>
    </tr>
</table>

<table style="font-size: 0.9em; line-height: 22px">
    <tr style="text-align: center">
        <td colspan="2"> N:</td>
        <td colspan="18"> Описание на стока / услуга</td>
        <td colspan="3"> К-во</td>
        <td colspan="6"> Ед. цена лв.</td>
        <td colspan="6"> Стойност лв.</td>
    </tr>
    <?php $i = 1;
    $sum = 0;
    foreach ($items as $item) {
        $sum += round($item['q'] * $item['price'], 2); ?>
        <tr>
            <td colspan="2"> <?= $i ?></td>
            <td colspan="18"> <?= $item['name'] ?></td>
            <td colspan="3" style="text-align: center"> <?= $item['q'] ?></td>
            <td colspan="6" style="text-align: center">  <?= number_format(round($item['price'], 2), 2) ?></td>
            <td colspan="6" style="text-align: right"> <?= number_format(round($item['q'] * $item['price'], 2), 2) ?>
                &nbsp;&nbsp;</td>
        </tr>
        <?php $i++;
    } ?>
    <tr style="text-align: right">
        <td colspan="29" class="noBorder" style="border-left: 1px solid black;border-bottom: 1px solid black"><strong>Данъчна
                основа:</strong></td>
        <td colspan="6"> <?= number_format($sum, 2) ?> &nbsp;&nbsp;</td>
    </tr>
    <tr style="text-align: right">
        <td colspan="29" class="noBorder" style="border-left: 1px solid black;border-bottom: 1px solid black"><strong>Начислен
                20% ДДС:</strong> &nbsp;&nbsp;</td>
        <td colspan="6"> <?= number_format($sum / 5, 2) ?> &nbsp;&nbsp;</td>
    </tr>
    <tr style="text-align: right">
        <td colspan="29" class="noBorder" style="border-left: 1px solid black;border-bottom: 1px solid black"><strong>Сума
                за плащане:</strong> &nbsp;&nbsp;</td>
        <td colspan="6"> <?= number_format($sum * 1.2, 2) ?> &nbsp;&nbsp;</td>
    </tr>
    <tr style="line-height: 12px">
        <td colspan="35" class="noBorder"></td>
    </tr>
</table>
<table style="font-size: 0.8em;line-height: 22px;">
    <tr>
        <td colspan="40" class="noBorder"><strong>Сума за плащане (словом): </strong> тридесет лева</td>
    </tr>
    <tr style="line-height: 12px">
        <td colspan="11" class="noBorder"><strong>Основание за: </strong></td>
        <td></td>
        <td colspan="12" class="noBorder"> нулева ставка</td>
        <td></td>
        <td class="noBorder" colspan="15"> неначисляване на ДДС</td>
    </tr>
    <tr style="line-height: 30px">
        <td class="noBorder" colspan="13"><strong>Начин на плащане:</strong></td>
        <td class="noBorder" colspan="27">По банков път</td>
    </tr>
    <tr style="line-height: 25px">
        <td colspan="13" class="noBorder"></td>
        <td colspan="5" class="noBorder" style="border-left: 1px solid black"> Банка:</td>
        <td colspan="22" class="noBorder">Уникредит Булбанк АД</td>
    </tr>
    <tr style="line-height: 25px">
        <td colspan="13" class="noBorder"></td>
        <td colspan="5" class="noBorder" style="border-left: 1px solid black"> BIC:</td>
        <td colspan="22" class="noBorder">UNCRBGSF</td>
    </tr>
    <tr style="line-height: 25px">
        <td colspan="13" class="noBorder"></td>
        <td colspan="5" class="noBorder" style="border-left: 1px solid black"> IBAN:</td>
        <td colspan="22" class="noBorder">BG24UNCR70001521008817</td>
    </tr>
    <tr style="line-height: 25px">
        <td colspan="13" class="noBorder"><strong>Дата на данъчно събитие:</strong></td>
        <td colspan="27" class="noBorder"> <?= $model->date ?></td>
    </tr>
    <tr style="line-height: 13px">
        <td class="noBorder" colspan="13" style="text-align: center;">/дата на плащане/</td>
        <td colspan="27" class="noBorder"></td>
    </tr>
    <tr style="line-height: 12px">
        <td colspan="40" class="noBorder"></td>
    </tr>
</table>
<table style="font-size: 0.8em">
    <tr style="line-height: 25px">
        <td class="noBorder"><strong>Получател....................</strong></td>
        <td class="noBorder"></td>
        <td class="noBorder" style="text-align: right"><strong>Съставил: </strong>&nbsp;</td>
        <td class="noBorder"> <?= $model->senderData['mol'] ?></td>
    </tr>
    <tr>
        <td class="noBorder" style="text-align: center">/ подпис /</td>
        <td class="noBorder"></td>
        <td class="noBorder"><strong>Подпис на съставителя:</strong></td>
        <td class="noBorder"></td>
    </tr>
    <tr style="line-height: 28px">
        <td colspan="4" class="noBorder"></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center;font-size: 0.85em" class="noBorder">
            Съгласно чл.6, ал.1 от Закона за счетоводството, чл.114 от ЗДДС и чл.78 от ППЗДДС печатът
            не е задължителен реквизит на фактурата, а подписите са заменени с идентификационни шифри
        </td>
    </tr>
</table>