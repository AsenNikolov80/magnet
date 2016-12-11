<style>
    .ad-container {
        box-shadow: 0 2px 5px black;
        padding: 5px;
    }

    .priceHolder {
        background-color: #ff5653;
        color: white;
        font-size: 1.3em;
    }

    dl > dt {
        width: 40% !important;
    }

    dl > dd {
        margin-left: 43% !important;
    }

    @media all and (max-width: 768px) {
        dl > dd {
            margin-left: 10% !important;
        }

        dl > dt {
            text-align: right;
        }
        dl * {
            display: inline-block;
        }
    }


</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 28.10.2016 г.
 * Time: 19:53
 */
/* @var $place \app\models\Place */
$file = new \app\components\FileComponent($place->getUser());
$this->params['breadcrumbs'][] = ['label' => 'Обекти', 'url' => Yii::$app->urlManager->createUrl('site/ads')];
$this->params['breadcrumbs'][] = 'Преглед на обект';
?>
<div class="col-sm-12">
    <?php
    \app\components\Components::printFlashMessages();
    if ($place) {
        $filename = $file->imagesPathForPictures . $place->picture;
        if (strlen($place->picture) > 0) {
            $src = $filename;
        } else {
            // default image
//            var_dump(explode(DIRECTORY_SEPARATOR,$file->imagesPathForPictures));
            $src = $file->imagesPathForPictures . '../../images/noimage.png';
        }
        ?>
        <div class="col-sm-5">
            <a href="<?= $src ?>" target="_blank"><img src="<?= $src ?>"></a>
            <div style="font-size: 1.1em;margin-top: 10px">
                <em><?= $place->description ?></em>
            </div>
        </div>
        <div class="col-sm-7">
            <h2><?= $place->name ?></h2>
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
                    <div class="ad-container col-sm-2 text-center priceHolder"><strong
                            style="text-shadow: 0 2px 5px black"><?= $ticket->price ?> лв.</strong></div>
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
        <div class="col-sm-4">
            <h3 class="text-center">Контактна информация:</h3>
            <dl class="dl-horizontal">
                <dt>Нас. място:</dt>
                <dd><?= $place->getCity()->name ?></dd>
                <dt>Адрес:</dt>
                <dd><?= $place->address ?></dd>
                <dt>Раб. време:</dt>
                <dd><?= $place->work_time ?></dd>
                <dt>Телефон:</dt>
                <dd><?= $place->phone ?></dd>
                <dt>Имейл:</dt>
                <dd><?= $place->getUser()->email ?></dd>
                <dt>Търговец:</dt>
                <dd><?= $place->getUser()->name ?></dd>
            </dl>
        </div>
        <div class="col-sm-8 text-center" id="map-holder" style="margin: 0 auto">
        </div>
        <?php
    }
    ?>
</div>
<script>
    function renderMap() {
        var mapLink = '<?=$place->map_link?>';
        if (mapLink.length > 0) {
            $('#map-holder').empty();
            var wIndex = 0.75;
            var hIndex = 0.5;
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