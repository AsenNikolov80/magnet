<style>
    .item {
        margin: 14px 0;
        display: inline-block;
        border: 1px solid #aaaaaa;
        box-shadow: 2px 2px 10px black;
    }

    .col-sm-4 > a {
        min-width: 100%;
    }

    .col-sm-4 > a .col-xs-6 {
        padding: 5px;
    }
</style>
<?php

/* @var $this yii\web\View */

$this->title = 'БГ ПРОМО';
?>
<div class="col-sm-12">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <h2>Списък промоционални обекти</h2>
    <div id="company-list" class="row">
        <?php
        /* @var $company \app\models\User */
        foreach ($companies as $company) {
            if (strlen($company->picture) > 0) {
                $src = Yii::$app->homeUrl . 'profile_images/' . $company->picture;
            } else {
                // default image
                $src = Yii::$app->homeUrl . 'images/noimage.png';
            }
            ?>
            <div class="col-sm-4">
                <a href="<?= Yii::$app->urlManager->createUrl(['site/view-profile', 'id' => $company->id]) ?>"
                   class="item">
                    <div class="col-xs-6">
                        <img src="<?= $src ?>">
                    </div>
                    <div class="col-xs-6">
                        <div><?= $company->name ?></div>
                        <div><?= $company->email ?></div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    function resetHeight(){
        var links = $('a.item');
        var h = links.first().height();
        var l = links.length;
        for (var i = 0; i < l; i++) {
            var currentH = links[i].offsetHeight;
            if (currentH > h)
                h = links[i].offsetHeight;
        }
        $('a.item').height(h);
    }
    $(function () {
        resetHeight();
        $(window).resize(resetHeight);
    })
</script>