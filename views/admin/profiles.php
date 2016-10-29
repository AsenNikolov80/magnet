<style>
    .edit, .remove{
        cursor: pointer;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 29.10.2016 г.
 * Time: 18:42
 */
use app\models\User;

$newUser = new User();
?>
Оттук може да администрирате профилите на предлагащите услуги
<div class="row">
    <table id="listCompanies">
        <thead>
        <tr>
            <th><?= $newUser->getAttributeLabel('username') ?></th>
            <th><?= $newUser->getAttributeLabel('email') ?></th>
            <th><?= $newUser->getAttributeLabel('name') ?></th>
            <th><?= $newUser->getAttributeLabel('address') ?></th>
            <th><?= $newUser->getAttributeLabel('active') ?></th>
            <th><?= $newUser->getAttributeLabel('paid_until') ?></th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /* @var $user User */
        foreach ($users as $user) { ?>
            <tr>
                <td><?= $user->username ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->name ?></td>
                <td><?= $user->address ?></td>
                <td><?= $user->active == 1 ? 'да' : 'не' ?></td>
                <td><?= Yii::$app->formatter->asDate($user->paid_until) ?></td>
                <td>
                    <i class="fa fa-edit fa-2x edit" data-id="<?=$user->id?>"></i>
                    <i class="fa fa-close fa-2x remove" data-id="<?=$user->id?>"></i>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div id="dialogEdit"></div>
<div id="dialogDelete"></div>
<script>
    $(function () {
        $('#listCompanies').dataTable({
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
        $('#dialogDelete').dialog({
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
                    var url = '<?=Yii::$app->urlManager->createUrl('admin/delete-user')?>';
                    var userId = $('.ui-dialog input[name="userId"').val();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {userId: userId}
                    });
                    $(this).dialog("close");
                },
                "Отказ": function () {
                    $(this).dialog("close");
                }
            }
        });

        $('.remove').click(function () {
            var id = $(this).data('id');
            $('#dialogDelete').load('<?=Yii::$app->urlManager->createUrl('admin/delete-user')?>' + '?userId=' + id, function () {
                $('#dialogDelete').dialog('open');
            });
        });

        $('#dialogEdit').dialog({
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
                    var url = '<?=Yii::$app->urlManager->createUrl('admin/edit-user')?>';
                    var userId = $('.ui-dialog input[name="userId"').val();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {userId: userId}
                    });
                    $(this).dialog("close");
                },
                "Отказ": function () {
                    $(this).dialog("close");
                }
            }
        });

        $('.edit').click(function () {
            var id = $(this).data('id');
            $('#dialogDelete').load('<?=Yii::$app->urlManager->createUrl('admin/edit-user')?>' + '?userId=' + id, function () {
                $('#dialogDelete').dialog('open');
            });
        });
    })
</script>
