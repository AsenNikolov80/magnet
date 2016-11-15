<style>
    td {
        border: 1px solid black;
    }

    .center {
        text-align: center;
        line-height: 80px;
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
<table>
    <tr style="font-size: 20px">
        <td class="center" colspan="5">
            ФАКТУРА
        </td>
        <td colspan="5" class="center">О Р И Г И Н А Л</td>
    </tr>
    <tr>
        <td colspan="5">
            <table>
                <tr>
                    <td style="text-align: left;border: none;line-height: 50px">
                        Номер: <?= $model->number ?>
                    </td>
                    <td style="text-align: right;border: none;line-height: 50px">Дата: <?= $model->date ?> &nbsp;&nbsp;&nbsp;</td>
                </tr>
            </table>
        </td>
        <td colspan="5">
            <table class="inner">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="border: 1px solid black"></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;Кредитно</td>
                    <td style="border: 1px solid black"></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;Дебитно</td>
                    <td colspan="7" style="font-size: 0.75em"> Към ф-ра №................</td>

                </tr>
                <tr>
                    <td></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;известие</td>
                    <td></td>
                    <td colspan="4" style="font-size: 0.75em">&nbsp;известие</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td colspan="5" class="noBorder"></td>
        <td colspan="5" class="noBorder"></td>
    </tr>
    <tr>
        <td style="border-right: 1px solid black">
        </td>
        <td colspan="4"></td>
        <td colspan="5" class="center" style="font-size: 1.5em;line-height: 35px">
            <strong><?= $model->senderData['company_name'] ?></strong>
        </td>
    </tr>
    <tr style="font-size: 0.9em;">
        <td style="border-right: 1px solid black">
        </td>
        <td colspan="4"></td>
        <td style="border-right: 1px solid black;line-height: 25px"> <strong>Адрес:</strong></td>
        <td colspan="4" style="line-height: 20px">
            <table>
                <tr class="inner">
                    <td></td>
                    <td colspan="25"><?= $model->senderData['address'] ?></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<? //= $model->senderData['dds'] ?>
<? //= $model->senderData['mol'] ?>
<? //= $model->senderData['address'] ?>
