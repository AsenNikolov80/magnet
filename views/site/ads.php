<style>
    .item {
        margin: 14px 0;
        display: inline-block;
        border: 1px solid #aaaaaa;
        box-shadow: 2px 2px 10px black;
        border-radius: 10px;
        padding: 5px;
        background-color: #ccc;
    }

    .col-sm-4 > a {
        min-width: 100%;
    }

    .col-sm-4 > a .col-xs-6 {
        padding: 5px;
    }
</style>
<?php
use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'БГ ПРОМО';
?>
<div class="row">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <div class="col-xs-12 row">
        <div class="col-sm-4">

            <h3>Покажи обекти по име:</h3>
            <?= Html::beginForm();
            echo Html::textInput('name', '', ['class' => 'form-control', 'style' => 'width:30%', 'required' => true]) . '<br/>';
            echo Html::submitButton('Търси', ['class' => 'btn btn-info']);
            echo Html::endForm(); ?>
        </div>
        <div class="col-sm-8">
            <h3>Покажи обекти по населено място:</h3>
            <?= Html::beginForm(); ?>
            <div class="col-sm-4">
                <?= Html::dropDownList('region', '', $regions, ['id' => 'region', 'class' => 'form-control']) ?>
            </div>
            <div class="col-sm-4">
                <?= Html::dropDownList('community', '', $communities, ['id' => 'community', 'class' => 'form-control']) ?>
            </div>
            <div class="col-sm-4">
                <?= Html::dropDownList('city', '', $cities, ['id' => 'city', 'class' => 'form-control']) ?>
            </div>
            <br/>
            <br/>
            <br/>
            <?= Html::submitButton('Търси', ['class' => 'btn btn-info']); ?>
            <?= Html::endForm(); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <hr/>
    </div>
    <h2>Списък промоционални обекти</h2>
    <?php
    if ($city) {
        echo '<h4>Обекти, намиращи се в населено място: <strong>' . \app\models\City::getCityName($city) . '</strong></h4>';
    }
    if ($postName) {
        echo '<h4>Обекти, съдържащи в името си: <strong>' . $postName . '</strong></h4>';
    }
    ?>
    <div id="company-list" class="row">
        <?php
        /* @var $company \app\models\User */
        if (empty($companies))
            echo '<h3>Няма намерени обекти засега!</h3>';
        foreach ($companies as $company) {
            $profileUrl = Yii::$app->urlManager->createUrl(['site/view-profile', 'id' => $company->id]);
            if (strlen($company->bulstat) > 0) {
                $src = Yii::$app->homeUrl . 'profile_images/' . $company->bulstat;
            } else {
                // default image
                $src = Yii::$app->homeUrl . 'images/noimage.png';
            }
            ?>
            <div class="col-sm-4">
                <div class="item">
                    <div class="col-xs-6">
                        <a href="<?= $profileUrl ?>" title="виж профил"><img src="<?= $src ?>"></a>
                    </div>
                    <div class="col-xs-6">
                        <div>
                            <h3>
                                <a href="<?= $profileUrl ?>" title="виж профил">
                                    Обект име
                                </a>
                            </h3>
                        </div>
                        <div>категория: <?= $company->getCategoryName() ?></div>
                        <div>емайл: <?= $company->email ?></div>
                        <div>телефон: <?= $company->phone ?></div>
                        <div>нас. място: <?= $company->getCityName() ?></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    'use strict';
    var cityRelations = <?=json_encode($cityRelations)?>;
    var cities = <?=json_encode($cities)?>;
    var communities = <?=json_encode($communities)?>;

    function resetHeight() {
        var links = $('.item');
        var h = links.first().height();
        var l = links.length;
        for (var i = 0; i < l; i++) {
            var currentH = links[i].offsetHeight;
            if (currentH > h)
                h = links[i].offsetHeight;
        }
        $('.item').height(h);
    }
    $(function () {
        resetHeight();
        $(window).resize(resetHeight);

        $('#region').trigger('change');
        $('#community').trigger('change');
    })
</script>