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
            <?= $company->name ?>
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