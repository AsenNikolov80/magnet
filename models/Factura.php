<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "facturi".
 *
 * @property string $id
 * @property string $user_id
 * @property string $path
 * @property string $date
 * @property integer $active
 * @property integer $place_id
 *
 * @property User $user
 */
class Factura extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facturi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'path'], 'required'],
            [['user_id', 'active', 'place_id'], 'integer'],
            [['path', 'date'], 'string', 'max' => 500],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'path' => 'Path',
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
     * @return Place
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id'])->one();
    }
}
