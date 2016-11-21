<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 21.11.2016 г.
 * Time: 21:06
 */
/* @var $category \app\models\Category */
$cat = new \app\models\Category();
?>
<div class="row">
    <div class="col-sm-5">
        <ul>
            <?php
            foreach ($categories as $category) { ?>
                <li><?= $category->name ?></li>
            <?php } ?>
        </ul>
        <?php
        $form = \yii\bootstrap\ActiveForm::begin();
        echo $form->field($cat, 'name');?>
        <button class="btn btn-success">Запиши</button>

        <?php
        \yii\bootstrap\ActiveForm::end();
        ?>
    </div>
</div>
