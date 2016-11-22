<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 22.11.2016 г.
 * Time: 21:53
 */
/* @var $factura \app\models\Factura */
$file = new \app\components\FileComponent();
?>
<div class="row">
    <div class="col-sm-12">
        <table id="invoiceList">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Фирма</th>
                <th>Дата</th>
                <th>Населено място</th>
                <th>Преглед</th>
                <th>АНУЛИРАНЕ</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($facturi as $factura) {
                $user = $factura->getUser();
                ?>
                <tr style="color:<?= $factura->active == 1 ? 'black' : 'red' ?>">
                    <td><?= '00000' . $factura->id ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $factura->date ?></td>
                    <td><?= $user->getCityName() ?></td>
                    <td>
                        <a target="_blank"
                           href="<?= Yii::$app->urlManager->createUrl(['admin/preview-invoice', 'id' => $factura->id]) ?>">преглед</a>
                    </td>
                    <td>
                        <?php
                        if ($factura->active == 1) { ?>
                            <i data-id="<?= $factura->id ?>" class="fa fa-close fa-2x delete"
                               style="color: red;cursor: pointer"></i>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div id="deleteModal" style="display: none"></div>
<script>
    function registerTableEvents() {
        setTimeout(function () {
            $('#deleteModal').dialog({
                autoOpen: false,
                resizable: false,
                show: {
                    effect: "explode",
                    duration: 1000
                },
                hide: {
                    effect: "explode",
                    duration: 1000
                },
                width: "auto",
                position: {my: "left top", at: "left+25% top+10%", of: window},
                modal: true,
                buttons: {
                    "Да, изтрий!": function () {
                        var url = '<?=Yii::$app->urlManager->createUrl('admin/delete-invoice')?>';
                        var invoiceId = $('.ui-dialog input[name="invoiceId"').val();
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {invoiceId: invoiceId}
                        });
                        $(this).dialog("close");
                    },
                    "Отказ": function () {
                        $(this).dialog("close");
                    }
                }
            });
        }, 400);
    }

    $(function () {
        registerTableEvents();
        $('#invoiceList').dataTable({
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
        $(document).on('click', 'i.delete', function () {
            $('#deleteModal').load('<?=Yii::$app->urlManager->createUrl(['admin/delete-invoice-modal'])?>?id=' + $(this).data('id'), function () {
                $('#deleteModal').dialog('open');
            });
        });
        $('#invoiceList').on('page.dt', registerTableEvents);
        $('#invoiceList').on('search.dt', registerTableEvents);
    })
</script>
