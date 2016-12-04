<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recover_passwords".
 *
 * @property string $id
 * @property string $email
 * @property string $valid
 * @property string $hash
 *
 * @property User $user
 */
class RecoverPassword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recover_passwords';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'valid'], 'required'],
            [['valid'], 'safe'],
            [['email'], 'string', 'max' => 200],
            [['hash'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'valid' => 'Valid',
            'hash' => 'Hash',
        ];
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['email' => 'email'])->one();
    }
}
