<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "places".
 *
 * @property string $id
 * @property string $name
 * @property string $user_id
 * @property integer $city_id
 * @property string $address
 * @property string $picture
 * @property string $phone
 * @property string $work_time
 * @property string $description
 *
 * @property User $user
 * @property City $city
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'city_id'], 'required'],
            [['user_id', 'city_id'], 'integer'],
            [['name', 'address', 'phone', 'work_time'], 'string', 'max' => 500],
            [['picture'], 'string', 'max' => 550],
            [['description'], 'string', 'max' => 2000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'user_id' => 'User ID',
            'city_id' => 'Населено място',
            'picture' => 'Снимка',
            'address' => 'Адрес',
            'place_name' => 'Име на обекта',
            'phone' => 'Телефон',
            'work_time' => 'Работно време',
            'description' => 'Описание',
        ];
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->one();
    }
}