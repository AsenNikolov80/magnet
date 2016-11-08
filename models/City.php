<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property string $name
 * @property integer $community_id
 *
 * @property CityRegions[] $cityRegions
 * @property Users[] $users
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_id'], 'integer'],
            [['name'], 'string', 'max' => 600],
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
            'community_id' => 'Community ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityRegions()
    {
        return $this->hasMany(CityRegions::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['city_id' => 'id']);
    }

    public static function getCityName($cityId)
    {
        return (new Query())->select('name')->from(self::tableName())->where(['id' => $cityId])->scalar();
    }
}
