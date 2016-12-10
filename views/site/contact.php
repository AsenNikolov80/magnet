<style>
    #wrapperCustom {
        width: 50%;
        margin: 0 auto;
    }

    @media screen and (max-width: 768px) {
        #wrapperCustom {
            width: 100%;
            margin: 0;
        }
    }
</style>
<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Контакти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div id="wrapperCustom">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-7\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-3 control-label', 'style' => 'color: black !important'],
                ],
            ]);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
