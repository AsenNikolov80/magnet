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

/* @var $contact \app\models\ContactForm */

$this->title = 'Контакти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <h3>Може да използвате формата по-долу, за да се свържете с нас, дадете вашето мнение, оценка,
            препоръка...
        </h3>
        <div id="wrapperCustom">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-7\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-3 control-label', 'style' => 'color: black !important'],
                ],
            ]); ?>
            <?= $form->field($contact, 'name') ?>
            <?= $form->field($contact, 'email')->textInput(['type' => 'email']) ?>
            <?= $form->field($contact, 'subject') ?>
            <?= $form->field($contact, 'body')->textarea(['rows' => 8]) ?>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-4" style="background-color: #00aa00;margin: 20px auto;padding: 5px;text-align: center;font-size: 1.15em">
                    <?= $contact->verifyCode ?>
                </div>
                <div class="col-xs-4"></div>
            </div>
            <?= $form->field($contact, 'verifyCode')->textInput(['value' => '']) ?>
            <div style="text-align: center">
                <input type="submit" value="Изпрати" class="btn btn-primary"/>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
