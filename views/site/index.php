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
    .navbar-inverse .navbar-nav > li{
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
<div class="col-sm-12">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <h1>Добре дошли!</h1>
</div>