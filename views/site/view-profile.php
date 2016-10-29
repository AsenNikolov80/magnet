<style>
    .ad-container {
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
        var mapLink = '<?=$company->map_link?>';
        if (mapLink.length > 0) {
            $('#map-holder').empty();
            var wIndex = 0.75;
            var hIndex = 0.45;
            if ($(window).width() < 769) {
                wIndex = 1;
                hIndex = 0.7;
            }
            var w = parseInt($('#map-holder').width());
            var iframe = $('<iframe>');
            iframe.prop('width', w * wIndex);
            iframe.prop('height', w * hIndex);
            iframe.prop('src', mapLink);
            iframe.appendTo($('#map-holder'));
        } else {
            $('#map-holder').text('Не е намерен валиден адрес за обекта!').css({
                'background-color': 'orange',
                'padding': '5px'
            });
        }
    }
    $(function () {
        renderMap();
        $(window).resize(renderMap);
    });
</script>