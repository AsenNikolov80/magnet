<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 14.11.2016 г.
 * Time: 19:48
 */

namespace app\models;

use yii\base\Model;

class InvoiceData extends Model
{
    public $senderData = [];
    public $number;
    public $date;
    public $rec_name;
    public $rec_address;
    public $rec_country;
    public $rec_bulstat;
    public $rec_dds;
    public $rec_mol;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $settings = Settings::find()->all();
        foreach ($settings as $setting) {
            $this->senderData[$setting->name] = $setting->value;
        }
    }

    public function getRecipientData($userId = false)
    {
        if (!$userId)
            $user = User::findOne(\Yii::$app->user->id);
        else $user = User::findOne($userId);
        $this->rec_name = $user->name;
        $this->rec_address = $user->address;
        $this->rec_bulstat = $user->bulstat;
        $this->rec_country = "България";
        $this->rec_dds = $user->dds;
        $this->rec_mol = $user->mol;
    }
}