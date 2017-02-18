<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tickets".
 *
 * @property string $id
 * @property string $text
 * @property string $id_place
 * @property string $price
 * @property string $type
 *
 * @property Place $idPlace
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
            [['text', 'id_place'], 'required'],
            [['id_place'], 'integer'],
            [['text'], 'string', 'max' => 500],
            [['id_place'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['id_place' => 'id']],
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
            'id_place' => 'Обект',
            'price' => 'Цена с ДДС',
        ];
    }

    /**
     * @return Place
     */
    public function getIdPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'id_place'])->one();
    }
}
