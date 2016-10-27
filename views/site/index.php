<style>
    .item {
        padding: 0;
        display: inline-block;
        border: 1px solid #aaaaaa;
        box-shadow: 2px 2px 10px black;
    }
</style>
<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="col-sm-12">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <h2>Списък промоционални обекти</h2>
    <div id="company-list">
        <?php
        /* @var $company \app\models\User */
        foreach ($companies as $company) { ?>
            <a href="<?= Yii::$app->urlManager->createUrl(['site/view-profile', 'id' => $company->id]) ?>"
               class="item row col-sm-4">
                <div class="col-xs-6">
                    <img src="../web/profile_images/<?= $company->picture ?>">
                </div>
                <div class="col-xs-6">
                    <div><?= $company->name ?></div>
                    <div><?= $company->email ?></div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>
<script>
    $(function () {
//        $('#adsTable').dataTable({
//            "language": {
//                "search": "Търси:",
//                "order": [[1, 'asc']],
//                "emptyTable": "Няма намерени резултати",
//                "info": "Обяви: от _START_ до _END_ , от всички  _TOTAL_ Обяви",
//                "infoEmpty": "Обяви: от 0 до 0 , от всички  0 Обяви",
//                "infoFiltered": "(Филтрирани от _MAX_ Обяви)",
//                "lengthMenu": "Покажи _MENU_ Обяви", "loadingRecords": "Зареждане...",
//                "processing": "Зареждане...", "zeroRecords": "Няма открити резултати",
//                "paginate": {
//                    "first": "Първа",
//                    "last": "Последна",
//                    "next": "Следваща",
//                    "previous": "Предишна"
//                },
//                "aria": {
//                    "sortAscending": ": активирано сортиране",
//                    "sortDescending": ": активирано сортиране"
//                }
//            }
//        })
    });
</script>