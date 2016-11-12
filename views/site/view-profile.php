<style>
    .ad-container {
        box-shadow: 0 2px 5px black;
        padding: 5px;
    }
    .priceHolder{
        background-color: #ff5653;
        color: white;
        font-size: 1.3em;
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
        if (strlen($company->picture) > 0) {
            $src = Yii::$app->homeUrl . 'profile_images/' . $company->picture;
        } else {
            // default image
            $src = Yii::$app->homeUrl . 'images/noimage.png';
        }
        ?>
        <div class="col-sm-5">
            <a href="<?= $src ?>" target="_blank"><img src="<?= $src ?>"></a>
            <div>
                <h4>
                    <?= $company->getCityName() . ', ' . $company->address ?>
                </h4>
            </div>
        </div>
        <div class="col-sm-7">
            <h2><?= $company->name ?></h2>
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-sm-9 text-center">Продукт / услуга</div>
                <div class="col-sm-1"></div>
                <div class="col-sm-2 text-center">Цена</div>
            </div>
            <?php
            /* @var $ticket \app\models\Ticket */
            foreach ($tickets as $ticket) { ?>
                <div class="row" style="margin: 10px 0">
                    <div class="ad-container col-sm-9 text-center"><strong><?= $ticket->text ?></strong></div>
                    <div class="col-sm-1"></div>
                    <div class="ad-container col-sm-2 text-center priceHolder"><strong style="text-shadow: 0 2px 5px black"><?= $ticket->price ?></strong></div>
                </div>
            <?php } ?>
            <div class="text-center">
                <hr/>
                Друг вид промоции
            </div>
            <?php
            foreach ($freeTextTickets as $ticket) { ?>
                <div class="row ad-container" style="text-align: justify">
                    <div class="col-sm-12"><strong><?= $ticket->text ?></strong></div>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-12">
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