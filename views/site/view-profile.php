<style>
    .ad-container{
        margin: 15px;
        box-shadow: 0 2px 5px black;
        padding: 5px;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 28.10.2016 г.
 * Time: 19:53
 */
/* @var $company \app\models\User */
?>
<div class="col-sm-12">
    <?php
    \app\components\Components::printFlashMessages();
    if ($company) {
        ?>
        <div class="col-sm-5">
            <img src="../profile_images/<?= $company->picture ?>">
        </div>
        <div class="col-sm-7">
            <h2><?= $company->name ?></h2>
            <div>
                Актуални оферти:
                <div class="row" style="margin: 15px; padding: 5px">
                    <div class="col-sm-10 text-center">Продукт/услуга</div>
                    <div class="col-sm-2 text-center">Цена</div>
                </div>
                <?php
                /* @var $ticket \app\models\Ticket */
                foreach ($tickets as $ticket) { ?>
                    <div class="row ad-container">
                        <div class="col-sm-10 text-center"><strong><?= $ticket->text ?></strong></div>
                        <div class="col-sm-2 text-center"><strong><?= $ticket->price ?></strong></div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class=col-sm-12>
            <hr style="border-color: #ccc"/>
        </div>
        <div class="col-sm-12 text-center" id="map-holder">
        </div>
        <?php
    }
    ?>
</div>
<script>
    function renderMap() {
        $('#map-holder').empty();
        var w = parseInt($('#map-holder').width());
        var iframe = $('<iframe>');
        iframe.prop('width', w * 0.7);
        iframe.prop('height', w * 0.4);
        iframe.prop('src', '<?=$company->map_link?>');
        iframe.appendTo($('#map-holder'));
    }
    $(function () {
        renderMap();
        $(window).resize(renderMap);
    });
</script>