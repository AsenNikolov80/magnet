<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proformi".
 *
 * @property integer $id
 * @property integer $place_id
 * @property string $date
 * @property integer $paid
 *
 * @property User $user
 */
class Proforma extends \yii\db\ActiveRecord
{

    const FILE_NAME = 'proforma';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proformi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id', 'date'], 'required'],
            [['place_id', 'paid'], 'integer'],
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
            'place_id' => 'Place ID',
        ];
    }

    /**
     * @return User
     */
    public function getUser()
    {
        /* @var $place Place*/
        $place = $this->hasOne(Place::className(), ['id' => 'place_id'])->one();
        return $place->getUser();
    }
}
