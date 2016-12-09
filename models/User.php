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
 * @property integer $type
 * @property integer $active
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $paid_until
 * @property string $name
 * @property integer $subscribed
 * @property string $last_updated
 * @property integer $cat_id
 * @property string $bulstat
 * @property string $dds
 * @property string $mol
 * @property string $paid_amount
 * @property integer $conditions
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER_USER = 'register_user';
    const SCENARIO_REGISTER_COMPANY = 'register_company';
    const SCENARIO_PASSWORD_CHANGE = 'change_password';

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
            [['address', 'email', 'first_name', 'last_name', 'paid_until', 'name'], 'string', 'max' => 250, 'on' => self::SCENARIO_DEFAULT],
            [['username', 'password'], 'required', 'on' => self::SCENARIO_LOGIN],
            [['username', 'email', 'password', 'id'], 'required', 'on' => self::SCENARIO_PASSWORD_CHANGE],
            [['username', 'email', 'password', 'city_id', 'address', 'type', 'name', 'first_name', 'last_name', 'cat_id', 'bulstat', 'dds', 'mol', 'conditions'], 'required', 'on' => self::SCENARIO_REGISTER_COMPANY],
            [['username', 'email', 'password', 'city_id', 'type', 'first_name', 'last_name', 'conditions'], 'required', 'on' => self::SCENARIO_REGISTER_USER],
            [
                [
                    'email',
                    'password',
                    'city_id',
                    'type',
                    'active',
                    'username',
                    'address',
                    'first_name',
                    'last_name',
                    'paid_until',
                    'subscribed',
                    'last_updated',
                    'cat_id',
                    'bulstat',
                    'dds',
                    'mol',
                    'paid_amount',
                    'conditions',
                ], 'safe'
            ]
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
            'subscribed' => '',
            'last_updated' => 'Последна промяна',
            'bulstat' => 'Булстат',
            'dds' => 'ИН по ЗДДС',
            'mol' => 'МОЛ',
            'paid_amount' => 'Сума за плащане',
            'conditions' => 'Общи условия',
            'cat_id' => 'Категория'
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
        return (new Query())->select('name')->from(City::tableName())->where(['id' => $this->city_id])->scalar();
    }

    public function getCategoryName()
    {
        return (new Query())->select('name')->from('categories')->where(['id' => $this->cat_id])->scalar();
    }

    public function getTickets($selectedPlaceId = null)
    {
        if ($this->type == User::TYPE_COMPANY) {
            if (!$selectedPlaceId) {
                $placedIds = (new Query())->select('id')->from(Place::tableName())
                    ->where(['user_id' => $this->id])->column();
                $tickets = Ticket::find()->where(['in', 'id_place', $placedIds])->andWhere(['type' => Ticket::TYPE_PRICE])->all();
                $freeTextTickets = Ticket::find()->where(['in', 'id_place', $placedIds])->andWhere(['type' => Ticket::TYPE_FREE])->all();
                return [$tickets, $freeTextTickets];
            } else {
                $tickets = Ticket::find()->where(['id_place' => $selectedPlaceId])->andWhere(['type' => Ticket::TYPE_PRICE])->all();
                $freeTextTickets = Ticket::find()->where(['id_place' => $selectedPlaceId])->andWhere(['type' => Ticket::TYPE_FREE])->all();
                return [$tickets, $freeTextTickets];
            }
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
     * @param $company User
     */
    public static function sendEmailToUsersByCompany($company, $register = true)
    {
        if ($company->active == 1) {
            $targetUsers = User::find()->where(['city_id' => $company->city_id])
                ->andWhere(['type' => User::TYPE_USER])
                ->andWhere(['subscribed' => 1])->all();
            $to = '';
            $subject = '';
            $msg = '';
            foreach ($targetUsers as $targetUser) {
                /* @var $targetUser User */
                $link = ' <a href = "' . Yii::$app->urlManager->createAbsoluteUrl(['site/view-profile', 'id' => $company->id]) . '" > от тук!</a> ';
                if ($register === true) {
                    $subject = 'Уведомление за новорегистрирана и интересна за Вас компания';
                    $msg = 'Уважаеми/а г-н / г-жа ' . $targetUser->first_name . ' ' . $targetUser->last_name . ',<br /> Нова компания "' . $company->name . '" от предпочитаното от Вас населено място < strong>'
                        . $company->getCityName() . ' </strong > беше регистрирана при нас!Може да разгледате профила ' . $link;
                } else {
                    $subject = 'Уведомление за промяна в списък на промоционални оферти на интересна за Вас компания';
                    $msg = 'Уважаеми/а г-н / г-жа ' . $targetUser->first_name . ' ' . $targetUser->last_name . ',<br />  от населено място: ' . $company->getCityName() . ' обнови промоциите, които предлага, може да разгледате профила ' . $link;
                }
                $to = $targetUser->email;
                $headers = "Content-Type: text/html;\r\n charset=utf-8";
                mail($to, $subject, $msg, $headers);
            }
        }
    }

    /**
     * @param $company User
     */
    public static function sendEmailToAdminByCompany(User $company)
    {
        $subject = 'Нова компания се регистрира в сайта';
        $admins = User::findAll(['type' => self::TYPE_ADMIN]);
        /* @var $admin User */
        foreach ($admins as $admin) {
            $to = $admin->email;
            if ($to) {
                $msg = 'Нова компания <strong>' . $company->name . ' </strong> от населено място <strong>' . $company->getCityName()
                    . ' </strong> току - що се регистрира в системата! Може да разгледате профила от
    <a href = "' . Yii::$app->urlManager->createUrl(['admin/profiles']) . '" ><strong> тук</strong></a>';
                $headers = "Content-Type: text/html;\r\n charset=utf-8";
                mail($to, $subject, $msg, $headers);
            }
        }
    }

    /**
     * @param Place $place
     */
    public static function sendEmailToAdminByPlace(Place $place)
    {
        $company = $place->getUser();
        $subject = 'Нов обект се регистрира в сайта на фирма: ' . $company->name;
        $admins = User::findAll(['type' => self::TYPE_ADMIN]);
        /* @var $admin User */
        foreach ($admins as $admin) {
            $to = $admin->email;
            if ($to) {
                $msg = 'Нов обект с име: ' . $place->name . ' на компания <strong>' . $company->name . ' </strong> от населено място <strong>' . $company->getCityName()
                    . ' </strong> току - що се регистрира в системата! Може да разгледате профила от
    <a href = "' . Yii::$app->urlManager->createUrl(['admin/places', 'companyId' => $company->id]) . '" ><strong> тук</strong></a>';
                $headers = "Content-Type: text/html;\r\n charset=utf-8";
                mail($to, $subject, $msg, $headers);
            }
        }
    }

    public static function sendEmailToUsersByPlace(Place $place)
    {
        $company = $place->getUser();
        $subject = 'Нов обект се регистрира в сайта на фирма: ' . $company->name;
        $users = User::find()->where(['city_id' => $place->city_id])
            ->andWhere(['type' => User::TYPE_USER])
            ->andWhere(['subscribed' => 1])->all();
        /* @var $user User */
        foreach ($users as $user) {
            $to = $user->email;
            if ($to) {
                $msg = 'Нов обект с име: ' . $place->name . ' на компания <strong>' . $company->name . ' </strong> от населено място <strong>' . $place->getCity()->name
                    . ' </strong> току - що се регистрира в системата! Може да разгледате профила от
    <a href = "' . Yii::$app->urlManager->createUrl(['site/view-profile', 'id' => $place->id]) . '" ><strong>ТУК</strong></a>';
                $headers = "Content-Type: text/html;\r\n charset=utf-8";
                mail($to, $subject, $msg, $headers);
            }
        }
    }

    public function getPlaces()
    {
        return Place::findAll(['user_id' => $this->id]);
    }

    public function getPlacesIds()
    {
        return (new Query())->select('id')->from(Place::tableName())->where(['user_id' => $this->id])->column();
    }

    public function getInvoices()
    {
        return Factura::findAll(['user_id' => $this->id]);
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return Category::findOne($this->cat_id);
    }
}
