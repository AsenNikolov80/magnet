<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    public static function set($name, $value)
    {
        $model = self::findOne(['name' => $name]);
        if (!$model) {
            $model = new self();
            $model->name = $name;
        }
        $model->value = $value;
        $model->save();
    }

    public static function get($name)
    {
        $model = self::findOne(['name' => $name]);
        if (!$model) return null;
        return $model->value;
    }
}
