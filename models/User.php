<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;
use yii\helpers\Html;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $address
 * @property integer $city_id
 * @property string $picture
 * @property integer $type
 * @property integer $active
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $paid_until
 * @property string $name
 * @property string $map_link
 * @property integer $subscribed
 * @property string $last_updated
 * @property string $place_name
 * @property string $phone
 * @property string $work_time
 * @property string $description
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER_USER = 'register_user';
    const SCENARIO_REGISTER_COMPANY = 'register_company';

    const TYPE_USER = 0;
    const TYPE_COMPANY = 1;
    const TYPE_ADMIN = 2;

    public $cityName;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->cityName = $this->getCityName();
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'picture', 'email', 'first_name', 'last_name', 'paid_until', 'name'], 'string', 'max' => 250, 'on' => self::SCENARIO_DEFAULT],
            [['username', 'password'], 'required', 'on' => self::SCENARIO_LOGIN],
            [['username', 'email', 'password', 'city_id', 'address', 'type', 'name', 'first_name', 'last_name', 'place_name'], 'required', 'on' => self::SCENARIO_REGISTER_COMPANY],
            [['username', 'email', 'password', 'city_id', 'type', 'first_name', 'last_name'], 'required', 'on' => self::SCENARIO_REGISTER_USER],
            [['email', 'password', 'city_id', 'picture', 'type', 'active', 'username', 'address', 'first_name', 'last_name', 'paid_until', 'map_link', 'subscribed', 'last_updated', 'place_name', 'phone', 'work_time', 'description'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Имейл',
            'password' => 'Парола',
            'city_id' => 'Населено място',
            'paid_until' => 'Платено до',
            'name' => 'Наименование на фирмата',
            'active' => 'Активен',
            'address' => 'Адрес',
            'username' => 'Потребителско име',
            'first_name' => 'Първо име',
            'last_name' => 'Фамилия',
            'picture' => 'Снимка',
            'subscribed' => '',
            'last_updated' => 'Последна промяна',
            'map_link' => 'Линк към карта',
            'place_name' => 'Име на обекта',
            'phone' => 'Телефон',
            'work_time' => 'Работно време',
            'description' => 'Описание'
        ];
    }

    public function login()
    {
        $this->scenario = self::SCENARIO_LOGIN;
        $this->username = $_POST['User']['username'];
        $this->password = $_POST['User']['password'];
        if ($this->validate() && $this->checkUser()) {
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
        }
        return false;
    }

    public function checkUser()
    {
        $user = self::findOne(array('username' => $this->username));
        if ($user) {
            if ($user->active == 1 || ($user->active == 0 && $user->type == 0)) {
                return true;
            } else {
                $this->addError($this->username, 'Профилът Ви още не е активиран!');
                return false;
            }
        } else {
            $this->addError($this->username, 'Не сте регистриран в системата!');
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function getCityName()
    {
        return (new Query())->select('name')
            ->from(City::tableName())
            ->where(['id' => $this->city_id])->scalar();
    }

    public function getTickets()
    {
        if (Yii::$app->user->isUserCompany()) {
            $tickets = Ticket::find()->where(['id_user' => $this->id])->andWhere(['type' => Ticket::TYPE_PRICE])->all();
            $freeTextTickets = Ticket::find()->where(['id_user' => $this->id])->andWhere(['type' => Ticket::TYPE_FREE])->all();
            return [$tickets, $freeTextTickets];
        }
        return [];
    }

    public static function getUser($userId)
    {
        $user = User::findOne($userId);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Няма такъв потребител!');
        }
        return $user;
    }

    /**
     * @param $cityId integer
     * @param $company User
     */
    public static function sendEmailToUsersByCityId($cityId, $company)
    {
        $targetUsers = User::find()->where(['city_id' => $cityId])
            ->andWhere(['type' => User::TYPE_USER])
            ->andWhere(['subscribed' => 1])->all();
        foreach ($targetUsers as $targetUser) {
            /* @var $targetUser User */
            $to = $targetUser->email;
            $subject = 'Уведомление за новорегистрирана и интересна за Вас компания';
            $msg = 'Нова компания "' . $company->name . '" от предпочитаното от Вас населено място <strong>'
                . $company->getCityName() . '</strong> беше регистрирана при нас! Може да разгледате профила
                <a href="' . Yii::$app->urlManager->createAbsoluteUrl(['site/view-profile', 'id' => $company->id]) . '">оттук!</a>';
            $headers = "Content-Type: text/html;\r\n charset=utf-8";
            mail($to, $subject, $msg, $headers);
        }
    }
}
