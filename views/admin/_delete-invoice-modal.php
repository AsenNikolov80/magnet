<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 22.11.2016 г.
 * Time: 22:19
 */
/* @var $factura \app\models\Factura */
?>
Сигурен ли сте, че искате да анулирате фактура <strong>№00000<?= $factura->id ?></strong> от дата:
<strong><?= $factura->date ?></strong>
на фирма: <strong><?= $factura->getUser()->name ?></strong>?

<input type="hidden" name="invoiceId" value="<?= $factura->id ?>">