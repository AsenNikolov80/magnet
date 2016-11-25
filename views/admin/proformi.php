<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 18.11.2016 г.
 * Time: 22:26
 */
?>
<div class="row">
    <div class="col-sm-12">
        <table id="listProformi">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Фирма</th>
                <th>Обект</th>
                <th>Адрес</th>
                <th>МОЛ</th>
                <th>Потребител</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            /* @var $proforma \app\models\Proforma */
            foreach ($proformi as $proforma) {
                $user = $proforma->getUser();
                ?>
                <tr>
                    <td><?= $proforma->id ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $user->address ?></td>
                    <td><?= $user->mol ?></td>
                    <td><?= $user->username ?></td>
                    <td><a target="_blank" class="btn btn-info"
                           href="<?= Yii::$app->urlManager->createUrl(['admin/preview', 'id' => $proforma->id]) ?>">Преглед
                            проформа-фактура!</a></td>
                    <td>
                        <a onclick="hideTable()"
                           href="<?= Yii::$app->urlManager->createUrl(['admin/create-invoice', 'id' => $proforma->id]) ?>"
                           class="btn btn-warning">Издай фактура!</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function hideTable() {
        $('#listProformi').hide();
    }
    $(function () {
        $('#listProformi').dataTable({
            "language": {
                "search": "Търси:",
                "emptyTable": "Няма намерени резултати",
                "info": "Фирми: от _START_ до _END_ , от всички  _TOTAL_ фирми",
                "infoEmpty": "Предмети: от 0 до 0 , от всички  0 фирми",
                "infoFiltered": "(Филтрирани от _MAX_ фирми)",
                "lengthMenu": "Покажи _MENU_ фирми", "loadingRecords": "Зареждане...",
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