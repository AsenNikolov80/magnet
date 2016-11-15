<style>
    label {
        display: block;
        margin: 15px 0;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 14.11.2016 г.
 * Time: 20:22
 */
use yii\helpers\Html;
use app\models\Settings;

?>
<div class="row">
    <div class="col-sm-8">
        <?= Html::beginForm() ?>
        <label>Име на фирмата:
            <?= Html::textInput('Company[company_name]', Settings::get('company_name'), ['class' => 'form-control']) ?>
        </label>
        <label>ЕИК:
            <?= Html::textInput('Company[bulstat]', Settings::get('bulstat'), ['class' => 'form-control']) ?>
        </label>
        <label>ИН по ЗДДС:
            <?= Html::textInput('Company[dds]', Settings::get('dds'), ['class' => 'form-control']) ?>
        </label>
        <label>МОЛ:
            <?= Html::textInput('Company[mol]', Settings::get('mol'), ['class' => 'form-control']) ?>
        </label>
        <label>Държава:
            <?= Html::textInput('Company[country]', Settings::get('country'), ['class' => 'form-control']) ?>
        </label>
        <label>Адрес:
            <?= Html::textInput('Company[address]', Settings::get('address'), ['class' => 'form-control']) ?>
        </label>
        <?= Html::submitButton('Запази', ['name' => 'updateCompany', 'class' => 'btn btn-info']) ?>
        <?= Html::endForm() ?>
    </div>
</div>