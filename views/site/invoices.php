<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 3.12.2016 г.
 * Time: 21:30
 */
/* @var $invoice \app\models\Factura */
?>
<div class="row">
    <div class="col-sm-12">
        <h1>Списък с издадени фактури</h1>
        <table id="listInvoices">
            <thead>
            <tr>
                <th>Дата на издаване</th>
                <th>Анулирана</th>
                <th>За обект</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($invoices as $invoice) { ?>
                <tr>
                    <td><?= $invoice->date ?></td>
                    <td style="color: white;background-color: <?= $invoice->active == 1 ? 'green' : 'red' ?>">
                        <?= $invoice->active == 1 ? 'не' : 'да' ?>
                    </td>
                    <td><?= $invoice->getPlace()->name ?></td>
                    <td><a target="_blank"
                           href="<?= Yii::$app->urlManager->createUrl(['site/preview-invoice', 'id' => $invoice->id]) ?>">Преглед</a>
                    </td>
                </tr>
            <?php }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function () {
        $('#listInvoices').dataTable({
            "language": {
                "search": "Търси:",
                "emptyTable": "Няма намерени резултати",
                "info": "Фактури: от _START_ до _END_ , от всички  _TOTAL_ фактури",
                "infoEmpty": "Предмети: от 0 до 0 , от всички  0 фактури",
                "infoFiltered": "(Филтрирани от _MAX_ фактури)",
                "lengthMenu": "Покажи _MENU_ фактури", "loadingRecords": "Зареждане...",
                "processing": "Зареждане...", "zeroRecords": "Няма открити резултати",
                "paginate": {
                    "first": "Първа",
                    "last": "Последна",
                    "next": "Следваща",
                    "previous": "Предишна"
                },
                "aria": {
                    "sortAscending": ": активирано сортиране",
                    "sortDescending": ": активирано сортиране"
                }
            }
        });
    })
</script>
