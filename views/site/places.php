<style>
    .delete {
        color: red;
        cursor: pointer;
    }

    table#listPlaces a {
        text-decoration: none;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 25.11.2016 г.
 * Time: 19:21
 */
$newPlace = new \app\models\Place();
?>
<div class="row">
    <div class="col-sm-12">
        <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('site/profile') ?>">Назад към профила</a>
        <h2>Списък с Вашите създадени обекти до момента:</h2>
        <table id="listPlaces" style="display: none">
            <thead>
            <tr>
                <th><?= $newPlace->getAttributeLabel('name') ?></th>
                <th><?= $newPlace->getAttributeLabel('city_id') ?></th>
                <th><?= $newPlace->getAttributeLabel('address') ?></th>
                <th><?= $newPlace->getAttributeLabel('phone') ?></th>
                <th><?= $newPlace->getAttributeLabel('work_time') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php /* @var $place \app\models\Place */
            foreach ($places as $place) { ?>
                <tr>
                    <td><?= $place->name ?></td>
                    <td><?= $place->getCity()->name ?></td>
                    <td><?= $place->address ?></td>
                    <td><?= $place->phone ?></td>
                    <td><?= $place->work_time ?></td>
                    <td>
                        <a title="Преглед на обекта"
                           href="<?= Yii::$app->urlManager->createUrl(['site/view-place', 'id' => $place->id]) ?>"
                           class="fa fa-search fa-2x"></a>
                        <i title="Изтриване?" class="fa fa-close fa-2x delete" data-id="<?= $place->id ?>"></i>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <br/>
        <br/>
        <div class="col-sm-4">
            <a href="<?= Yii::$app->urlManager->createUrl('site/add-place') ?>" class="btn btn-primary">Въведете нов
                обект</a>
        </div>
    </div>
</div>
<div id="dialogDelete" style="display: none"></div>
<script>
    function registerTableEvents() {
        setTimeout(function () {
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
                width: 600,
                position: {my: "left top", at: "left+25% top+10%", of: window},
                modal: true,
                buttons: [{
                    text: 'Изтрий!',
                    click: function () {
                        var placeId = $('.ui-dialog #placeId').val();
                        var url = '<?=Yii::$app->urlManager->createUrl('site/delete-place-confirmed')?>';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {placeId: placeId}
                        });
                        $(this).dialog("close");
                    }
                }, {
                    text: 'Отказ',
                    click: function () {
                        $(this).dialog("close");
                    }
                }]
            });

            $('.delete').click(function () {
                var id = $(this).data('id');
                $('#dialogDelete').load('<?=Yii::$app->urlManager->createUrl('site/delete-place')?>' + '?id=' + id, function () {
                    $('#dialogDelete').dialog('open');
                });
            });
        }, 300);
    }

    $(function () {
        $('#listPlaces').dataTable({
            "language": {
                "search": "Търси:",
                "emptyTable": "Няма намерени резултати",
                "info": "Обекти: от _START_ до _END_ , от всички  _TOTAL_ обекти",
                "infoEmpty": "Предмети: от 0 до 0 , от всички  0 обекти",
                "infoFiltered": "(Филтрирани от _MAX_ обекти)",
                "lengthMenu": "Покажи _MENU_ обекти", "loadingRecords": "Зареждане...",
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
        $('#listPlaces').show();
        registerTableEvents();
        $('#listPlaces').on('page.dt', registerTableEvents);
        $('#listPlaces').on('search.dt', registerTableEvents);
    })
</script>

