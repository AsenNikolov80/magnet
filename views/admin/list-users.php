<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 5.2.2017 г.
 * Time: 12:41
 */
$user = new \app\models\User();
?>
<div class="row">
    <div class="col-sm-12">
        <table id="user-list">
            <thead>
            <tr>
                <th class="text-center"><?= $user->getAttributeLabel('username') ?></th>
                <th class="text-center"><?= $user->getAttributeLabel('first_name') ?></th>
                <th class="text-center"><?= $user->getAttributeLabel('last_name') ?></th>
                <th class="text-center"><?= $user->getAttributeLabel('email') ?></th>
                <th class="text-center"><?= $user->getAttributeLabel('address') ?></th>
                <th class="text-center"><?= $user->getAttributeLabel('city_id') ?></th>
                <th class="text-center"><?= $user->getAttributeLabel('subscribed') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            /* @var $user \app\models\User */
            foreach ($users as $user) { ?>
                <tr class="text-center">
                    <td class="text-center"><?= $user->username ?></td>
                    <td class="text-center"><?= $user->first_name ?></td>
                    <td class="text-center"><?= $user->last_name ?></td>
                    <td class="text-center"><?= $user->email ?></td>
                    <td class="text-center"><?= $user->address ?></td>
                    <td class="text-center"><?= $user->getCityName() ?></td>
                    <td class="text-center"><?= $user->subscribed ? 'да' : 'не' ?></td>
                    <td><i class="fa fa-close fa-2x" style="color: red"></i></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function () {
        $('#user-list').dataTable({
            "language": {
                "search": "Търси:",
                "emptyTable": "Няма намерени резултати",
                "info": "Потребители: от _START_ до _END_ , от всички  _TOTAL_ потребители",
                "infoEmpty": "Предмети: от 0 до 0 , от всички  0 потребители",
                "infoFiltered": "(Филтрирани от _MAX_ потребители)",
                "lengthMenu": "Покажи _MENU_ потребители", "loadingRecords": "Зареждане...",
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
        })
    });
</script>
