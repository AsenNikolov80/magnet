<style>
    .item {
        padding: 0;
        display: inline-block;
        border: 1px solid #aaaaaa;
        box-shadow: 2px 2px 10px black;
    }

    nav.navbar-inverse {
        background-color: #494949;
        background: linear-gradient(#555, #1a1a1a);
    }

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

    .navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .active > a:focus {
        background-color: floralwhite;
        color: black;
    }
</style>
<?php

/* @var $this yii\web\View */

$this->title = 'БГ ПРОМО';
$this->params['breadcrumbs'][] = '';
?>
<div class="row">

    <div class="col-sm-12">
        <?php
        \app\components\Components::printFlashMessages();
        ?>
        <h1 class="text-center">Добре дошли!</h1>
    </div>
    <div class="col-sm-6 text-justify">
        <h4>ВИЕ УВАЖАЕМИ ПОТРЕБИТЕЛИ:</h4>
        Ако се интересувате от промоциите на малките търговски обекти/обекти за услуги във Вашето или друго населеното място,
        можете да проверите тук има ли публикувани такива. А ако имате регистрация в promobox-bg.com
        ще получавате своевременно e-mail при публикуване на промо оферта, както и при всяко обновяване на информацията
        в профила на търговеца.

    </div>
    <div class="col-sm-6 text-justify">
        <h4>ВИЕ УВАЖАЕМИ ТЪРГОВЦИ:</h4>
        За да публикувате промо оферти на сайта трябва да имате регистрация като търговец на promobox-bg.com .
        Може да качвате неограничен брой промо оферти по всяко време от профила си,
        които след преглед от администратор ще бъдат активирани и рекламирани на сайта.
        В профила си по всяко време може да публикувате промо оферти и да обновявате информацията,
        при което всички регистрирани потребители, които са заявили,
        че се интересуват от промоциите от района на Вашият търговски обект/обект за услуги, ще получат своевременно e-mail.
        Така може да сте сигурни, че Вашите обяви ще достигнат до правилните клиенти в точното време!
    </div>
</div>