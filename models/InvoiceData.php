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
    public $number = 15;
    public $date;
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $settings = Settings::find()->all();
        foreach ($settings as $setting) {
            $this->senderData[$setting->name] = $setting->value;
        }
    }

}