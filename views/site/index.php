<style>
    .item {
        padding: 5px 30px;
        display: table-cell;
        border: 1px solid #aaaaaa;
        box-shadow: 0 0 5px black;
        width: 48%;
        vertical-align: top;
        border-radius: 20px;
    }

    img {
        width: 95%;
        margin: 30px 0;
        box-shadow: 10px 10px 25px black;
    }

    .col-sm-6 {
        display: table-cell;
    }

    /*nav.navbar-inverse {*/
    /*background-color: #494949;*/
    /*background: linear-gradient(#555, #1a1a1a);*/
    /*}*/

    .navbar-inverse .navbar-nav > li {
        /*border: 0;*/
        /*border-color: whitesmoke;*/
        /*border-left-width: 1px;*/
        /*border-right-width: 1px;*/
        /*border-style: solid;*/
    }

    .navbar-inverse .navbar-nav > li > a {
        color: white;
    }

    div.row div {
        font-size: 1.2em;
    }

    @media all and (max-width: 1024px) {
        img {
            width: 85%;
            margin: 30px 0;

        }

        .item {
            display: block;
            width: 100%;
        }
    }

    h1 {
        margin-bottom: 20px;
        margin-top: 5px;
    }

    #cookie {
        display: none;
        font-size: 0.9em;
        background-color: #1a1a1a;
        color: white;
        padding: 8px 12px;
        position: relative;
    }

    /*.navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .active > a:focus {*/
    /*background-color: floralwhite;*/
    /*color: black;*/
    /*}*/
</style>
<?php

/* @var $this yii\web\View */

$this->title = 'БГ ПРОМО';
$this->params['breadcrumbs'][] = '';
?>
<div class="row" style="display: table-row">
    <div class="col-sm-12">
        <div id="cookie">Този сайт
            използва бисквитки, които улесняват ползването на сайта от Ваша страна.
            Чрез навигиране в сайта и/или използването му, вие се съгласявате да събираме информация чрез бисквитки,
            за повече информация <a href="<?= Yii::$app->urlManager->createUrl('site/cookies') ?>">ТУК!</a>
            <i class="fa fa-close" style="color: white;position: absolute;right: 2px;top:2px;cursor: pointer"></i>
        </div>
        <?php
        \app\components\Components::printFlashMessages();
        ?>
        <h1 class="text-center">Добре дошли!</h1>
    </div>
    <div id="first" style="display: table-row;">
        <div class=" text-justify item">
            <h2 class="text-center">Вие, Уважаеми потребители:</h2>
            Ако се интересувате от промоциите на малките търговски обекти/обекти за услуги/ във Вашето или друго
            населено място,
            можете да проверите тук има ли публикувани такива. А ако имате регистрация в promobox-bg.com
            ще получавате своевременно e-mail при публикуване на промо оферта, както и при всяко обновяване на
            информацията в профила на търговеца.
        </div>
        <div class="item text-center" style="box-shadow: none;border: 0">
            <img src="<?= Yii::$app->getHomeUrl() . 'images' . DIRECTORY_SEPARATOR . 'p1.jpg' ?>"/>
        </div>
    </div>
    <div id="second" style="display: table-row">
        <div id="move" class="item text-center" style="margin-top: 20px;box-shadow: none;border: 0">
            <img src="<?= Yii::$app->getHomeUrl() . 'images' . DIRECTORY_SEPARATOR . 'p3.jpg' ?>"/>
        </div>
        <div class=" text-justify item" style="margin-top: 20px">
            <h2 class="text-center">Вие, Уважаеми търговци:</h2>
            За да публикувате промо оферти на сайта, трябва да имате регистрация като търговец на <strong><a
                    href="<?= Yii::$app->urlManager->createUrl(['site/register', 'type' => 1]) ?>">promobox-bg.com</a></strong>.
            Може да качвате неограничен брой промо оферти по всяко време от профила си,
            които след преглед от администратор ще бъдат активирани и рекламирани на сайта, и да обновявате
            информацията,
            при което всички регистрирани потребители, които са заявили
            че се интересуват от промоциите от района на Вашият търговски обект/обект за услуги/, ще получат
            своевременно
            e-mail с информация за Вашата нова обява.
            Така може да сте сигурни, че Вашите обяви ще достигнат до правилните клиенти в точното време!
        </div>
    </div>
</div>
<script>
    function checkPosition() {
        var div = $('#move');
        if ($(window).width() <= 1024) {
            div.appendTo($('#second'));
        } else {
            div.prependTo($('#second'));
        }
    }
    function checkForCookies() {
        var ls = localStorage.getItem('cookiePolicyForPromobox');
        if (!ls) $('#cookie').show();
        else $('#cookie').hide();
    }
    $(function () {
        checkPosition();
        $(window).resize(checkPosition);
        checkForCookies();
        $(document).on('click', '#cookie .fa-close', function () {
            localStorage.setItem('cookiePolicyForPromobox', 'accepted');
            checkForCookies();
        })
    })
</script>