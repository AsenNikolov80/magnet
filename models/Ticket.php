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
 * @property string $type
 *
 * @property User $idUser
 */
class Ticket extends \yii\db\ActiveRecord
{
    const TYPE_PRICE = 'price';
    const TYPE_FREE = 'free';

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
            [['text', 'id_user'], 'required'],
            [['id_user'], 'integer'],
            [['text'], 'string', 'max' => 500],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['price', 'type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Промоция',
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
