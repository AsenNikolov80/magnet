<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tickets".
 *
 * @property string $id
 * @property string $text
 * @property string $id_user
 * @property string $price
 *
 * @property User $idUser
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'id_user', 'price'], 'required'],
            [['id_user'], 'integer'],
            [['text'], 'string', 'max' => 500],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст на обявата',
            'id_user' => 'Потребител',
            'price' => 'Цена',
        ];
    }

    /**
     * @return User
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user'])->one();
    }
}
