<?php

namespace app\controllers;

use app\components\FileComponent;
use app\models\City;
use app\models\InvoiceData;
use app\models\Proforma;
use app\models\Settings;
use app\models\Ticket;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\log\Logger;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class AdminController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'profiles',
                            'delete-user',
                            'edit-user',
                            'invoice-data',
                            'proformi',
                            'preview',
                            'create-invoice',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isUserAdmin())
                                return $this->redirect(yii::$app->urlManager->createAbsoluteUrl(['site/index']));
                            return true;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionProfiles()
    {
        $users = User::findAll(['type' => User::TYPE_COMPANY]);
        return $this->render('profiles', ['users' => $users]);
    }

    public function actionDeleteUser()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $userId = $request->get('userId');
            $user = User::getUser($userId);
            return $this->renderPartial('_delete-user', ['user' => $user]);
        } elseif ($request->isAjax) {
            $userId = $request->post('userId');
            $user = User::getUser($userId);
            $user->delete();
            return $this->redirect(Yii::$app->urlManager->createUrl('admin/profiles'));
        }
        return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
    }

    public function actionEditUser()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $userId = $request->get('userId');
            $user = User::getUser($userId);
            return $this->renderPartial('_edit-user', ['user' => $user]);
        } elseif ($request->isPost) {
            $userId = (int)$_POST['User']['id'];
            $user = User::findOne($userId);
            $oldStatus = $user->active;
            /* @var $user User */
            $user->setAttributes($request->post('User'));
            if ($user->active == 1 && $user->active != $oldStatus) {
                User::sendEmailToUsersByCompany($user);
            }
            $user->save();
            return $this->redirect(Yii::$app->urlManager->createUrl('admin/profiles'));
        }
        return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
    }

    public function actionInvoiceData()
    {
        if (isset($_POST['updateCompany']) && !empty($_POST['Company'])) {
            $companyData = $_POST['Company'];
            foreach ($companyData as $name => $value) {
                Settings::set($name, $value);
            }
        }
        return $this->render('invoice-data');
    }

    public function actionProformi()
    {
        $proformi = Proforma::findAll(['paid' => 0]);
        return $this->render('proformi', ['proformi' => $proformi]);
    }

    public function actionPreview()
    {
        $file = new FileComponent();
        $proforma = Proforma::findOne(intval($_GET['id']));
        if ($proforma) {
            $user = $proforma->getUser();
            $path = $file->filePathProforma . $user->username . DIRECTORY_SEPARATOR . Proforma::FILE_NAME;
            $pdf = file_get_contents($path);
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . Proforma::FILE_NAME . '"');
            echo $pdf;
        }
    }

    public function actionCreateInvoice()
    {
        $proforma = Proforma::findOne(intval($_GET['id']));
        if ($proforma) {
            $company = $proforma->getUser();
            $file = new FileComponent();
            $pdf = $file->preparePdfData();
            $model = new InvoiceData();
            $model->getRecipientData($company->id);
            $timestamp = strtotime($proforma->date);
            $model->date = $timestamp;
            $model->date = date('d.m.Y', $model->date);
            $model->number = $proforma->id;
            $items = [];
            $items[0]['name'] = 'Абонамент за ползване на сайт до ' . date('d.m.Y', strtotime('+1 years,+7 days', $timestamp));
            $items[0]['price'] = number_format($company->paid_amount / 1.2, 2);
            $items[0]['q'] = 1;

            $pdf->writeHTML($this->renderPartial('_factura',
                ['model' => $model, 'items' => $items, 'type' => FileComponent::TYPE_FACTURA, 'origin' => FileComponent::TYPE_ORIGINAL]));
            $pdf->AddPage();
            $origin = FileComponent::TYPE_ORIGINAL;
            $pdf->writeHTML($this->renderPartial('_factura',
                ['model' => $model, 'items' => $items, 'type' => FileComponent::TYPE_FACTURA, 'origin' => FileComponent::TYPE_DUBLICATE]));
            $path = $file->filePathFactura . $company->username . DIRECTORY_SEPARATOR;
            $fileName = 'factura_' . date('Y-m-d') . '.pdf';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (!file_exists($path . $fileName)) {
                $file = fopen($path . $fileName, 'w');
                fclose($file);
            }
            $pdf->Output($path . $fileName, 'F');
            $pdf->get();
            $newPaidDate = date('Y-m-d', strtotime('+1 years,+7 days', $timestamp));
            $company->paid_until = $newPaidDate;
            $company->active = 1;
            $company->save();
        }
    }

    private function getListOfRegionsCities()
    {
        $regions = [];
        $communities = [];
        $cities = [];
        $cityRelations = [];
        $citiesArray = (new Query())->select('c.id AS c_id, c.name as c_name, com.id AS com_id, com.name as com_name, r.id AS reg_id, r.name as reg_name')
            ->from(City::tableName() . ' c')
            ->innerJoin('communities com', 'c.community_id=com.id')
            ->innerJoin('regions r', 'com.region_id=r.id')->all();
        foreach ($citiesArray as $item) {
            $cityRelations[$item['reg_id']][$item['com_id']][] = $item['c_id'];
            $cities[$item['c_id']] = $item['c_name'];
            $communities[$item['com_id']] = $item['com_name'];
            $regions[$item['reg_id']] = $item['reg_name'];
        }

        return [$regions, $cities, $communities, $cityRelations];
    }

    /**
     * @return null|\yii\web\Response|static|User
     */
    private function getCurrentUser()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Няма такъв потребител!');
            return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
        }
        return $user;
    }

    /**
     * @param $cityId
     * @param bool $asIndexedArray
     * @return array|bool - community and region
     */
    private function getRegionCommunityByCityId($cityId, $asIndexedArray = false)
    {
        $result = (new Query())
            ->select('c.community_id as selectedCommunityId, com.region_id as selectedRegionId')
            ->from(City::tableName() . ' c')
            ->innerJoin('communities com', 'c.community_id=com.id')
            ->where(['c.id' => $cityId])->one();
        if ($asIndexedArray) {
            $result = array_values($result);
        }
        return $result;
    }
}
