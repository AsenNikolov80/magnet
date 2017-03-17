<?php

namespace app\controllers;

use app\components\FileComponent;
use app\models\Category;
use app\models\City;
use app\models\Factura;
use app\models\InvoiceData;
use app\models\Place;
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
                            'categories',
                            'create-invoice',
                            'invoices',
                            'preview-invoice',
                            'delete-invoice-modal',
                            'delete-invoice',
                            'edit-place',
                            'places',
                            'profiles-users'
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
        $proforma = Proforma::findOne(intval($_GET['id']));
        if ($proforma) {

            $user = $proforma->getUser();
            $file = new FileComponent($user);
            $path = $file->filePathProforma . Proforma::FILE_NAME . '_' . $proforma->place_id . '.pdf';
            $pdf = file_get_contents($path);
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . Proforma::FILE_NAME . '"');
            echo $pdf;
        }
    }

    public function actionPreviewInvoice()
    {
        $factura = Factura::findOne(intval($_GET['id']));
        if ($factura) {
            $user = $factura->getUser();
            $file = new FileComponent($user);
            $path = $file->filePathFactura . $factura->path;
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
            $file = new FileComponent($company);
            $pdf = $file->preparePdfData();
            $model = new InvoiceData();
            $model->getRecipientData($company->id);
            $timestamp = strtotime($proforma->date);
            $model->date = $timestamp;
            $model->date = date('d.m.Y', $model->date);

            $place = Place::findOne($proforma->place_id);

            $items = [];
            $items[0]['name'] = 'Абонамент за ползване на сайт до ' . date('d.m.Y', strtotime('+1 years,+7 days', $timestamp));
            $items[0]['price'] = number_format($place->price / 1.2, 2);
            $items[0]['q'] = 1;

            $path = $file->filePathFactura;
            $fileName = 'factura-' . $place->id . '_' . date('Y-m-d') . '.pdf';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (!file_exists($path . $fileName)) {
                $file = fopen($path . $fileName, 'w');
                fclose($file);
            }

            $factura = new Factura();
            $factura->user_id = $company->id;
            $factura->date = date('Y-m-d');
            $factura->path = $fileName;
            $factura->place_id = $place->id;
            $factura->save();

            $model->number = $factura->id;

            $sum = round($items[0]['q'] * $items[0]['price'], 2);
            $sumWord = $this->getSumWord(round($sum * 1.2, 2));

            $pdf->writeHTML($this->renderPartial('_factura',
                ['model' => $model, 'items' => $items, 'type' => FileComponent::TYPE_FACTURA, 'origin' => FileComponent::TYPE_ORIGINAL, 'sumWord' => $sumWord, 'sum' => $sum]));
            $pdf->AddPage();
            $pdf->writeHTML($this->renderPartial('_factura',
                ['model' => $model, 'items' => $items, 'type' => FileComponent::TYPE_FACTURA, 'origin' => FileComponent::TYPE_DUBLICATE, 'sumWord' => $sumWord, 'sum' => $sum]));

            $pdf->Output($path . $fileName, 'F');
            $pdf->get();

            $newPaidDate = date('Y-m-d', strtotime('+1 years,+7 days', $timestamp));
            $company->paid_until = $newPaidDate;
            $company->active = 1;
            $company->save();
            $proforma->paid = 1;
            $proforma->save();

            $oldActive = $place->active;
            $place->active = 1;
            if ($place->active != $oldActive)
                User::sendEmailToUsersByPlace($place);
            $place->paid_until = $newPaidDate;
            $place->save();
        }
    }

    private function getSumWord($sum)
    {
        $sum *= 100;
        $sum = intval(round($sum));
        $lastDigitStot = $sum % 10;
        $sum /= 10;
        $firstDigitStot = $sum % 10;
        $sum /= 10;
        $lastDigitLev = $sum % 10;
        $sum /= 10;
        $firstDigitLev = $sum % 10;
        $stotString = '';
        $levString = '';
        switch ($firstDigitStot) {
            case 1:
                switch ($lastDigitStot) {
                    case 1:
                        $stotString = 'единадесет';
                        break;
                    case 2:
                        $stotString = 'дванадесет';
                        break;
                    case 3:
                        $stotString = 'тринадесет';
                        break;
                    case 4:
                        $stotString = 'четиринадесет';
                        break;
                    case 5:
                        $stotString = 'петнадесет';
                        break;
                    case 6:
                        $stotString = 'шестнадесет';
                        break;
                    case 7:
                        $stotString = 'седемнадесет';
                        break;
                    case 8:
                        $stotString = 'осемнадесет';
                        break;
                    case 9:
                        $stotString = 'деветнадесет';
                        break;
                    case 0:
                        $stotString = 'десет';
                        break;
                }
                break;
            case 2:
                $stotString = 'двадесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 3:
                $stotString = 'тридесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 4:
                $stotString = 'четиридесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 5:
                $stotString = 'петдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 6:
                $stotString = 'шестдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 7:
                $stotString = 'седемдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 8:
                $stotString = 'осемдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 9:
                $stotString = 'деветдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 0:
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
        }
        switch ($firstDigitLev) {
            case 1:
                switch ($lastDigitLev) {
                    case 1:
                        $levString = 'единадесет';
                        break;
                    case 2:
                        $levString = 'дванадесет';
                        break;
                    case 3:
                        $levString = 'тринадесет';
                        break;
                    case 4:
                        $levString = 'четиринадесет';
                        break;
                    case 5:
                        $levString = 'петнадесет';
                        break;
                    case 6:
                        $levString = 'шестнадесет';
                        break;
                    case 7:
                        $levString = 'седемнадесет';
                        break;
                    case 8:
                        $levString = 'осемнадесет';
                        break;
                    case 9:
                        $levString = 'деветнадесет';
                        break;
                    case 0:
                        $levString = 'десет';
                        break;
                }
                break;
            case 2:
                $levString = 'двадесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 3:
                $levString = 'тридесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 4:
                $levString = 'четиридесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 5:
                $levString = 'петдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 6:
                $levString = 'шестдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 7:
                $levString = 'седемдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 8:
                $levString = 'осемдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 9:
                $levString = 'деветдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 0:
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
        }
        $stotString .= ' стотинки';
        $levString .= ' лева';
        return $levString . ', ' . $stotString;
    }

    private function handleMoney($stotString, $digit, $type = 'stot')
    {
        switch ($digit) {
            case 1:
                if ($type == 'lev') {
                    $stotString .= ' и един';
                } else {
                    $stotString .= ' и една';
                }
                break;
            case 2:
                if ($type == 'lev') {
                    $stotString .= ' и два';
                } else {
                    $stotString .= ' и две';
                }
                break;
            case 3:
                $stotString .= ' и три';
                break;
            case 4:
                $stotString .= ' и четири';
                break;
            case 5:
                $stotString .= ' и пет';
                break;
            case 6:
                $stotString .= ' и шест';
                break;
            case 7:
                $stotString .= ' и седем';
                break;
            case 8:
                $stotString .= ' и осем';
                break;
            case 9:
                $stotString .= ' и девет';
                break;
            case 0:
                if ($stotString == '') {
                    if ($type == 'lev') {
                        $stotString .= 'нула';
                    } else {
                        $stotString .= ' и нула';
                    }
                }
                break;
        }
        return $stotString;
    }

    public function actionCategories()
    {
        if (!empty($_POST['Category'])) {
            $cat = new Category();
            $cat->setAttributes($_POST['Category']);
            $cat->save();
        }
        $categories = Category::find()->all();
        return $this->render('categories', ['categories' => $categories]);
    }

    public function actionInvoices()
    {
        $facturi = Factura::find()->all();
        return $this->render('invoices', ['facturi' => $facturi]);
    }

    public function actionDeleteInvoiceModal()
    {
        $factura = Factura::findOne(intval($_GET['id']));
        return $this->renderPartial('_delete-invoice-modal', ['factura' => $factura]);
    }

    public function actionDeleteInvoice()
    {
        if (Yii::$app->request->isAjax) {
            $factura = Factura::findOne(intval($_POST['invoiceId']));
            if ($factura) {
                $factura->active = 0;
                $factura->save();
            }
            return $this->redirect(Yii::$app->urlManager->createUrl('admin/invoices'));
        }
        return false;
    }

    public function actionPlaces()
    {
        $companyId = intval(Yii::$app->request->get('companyId'));
        $company = User::findOne($companyId);
        if ($company) {
            return $this->render('places', ['company' => $company]);
        }
        return $this->redirect(Yii::$app->urlManager->createUrl('admin/profiles'));
    }

    public function actionEditPlace()
    {
        $place = Place::findOne(intval(Yii::$app->request->get('id')));
        $req = Yii::$app->request;
        if ($place) {
            if (!empty($req->post('Place'))) {
                $place->setAttributes($req->post('Place'));
                $place->checked = 1;
                if ($place->save()) {
                    if ($place->active)
                        User::sendEmailToUsersByPlace($place);
                    Yii::$app->session->setFlash('success', 'Промените са записани!');
                }
            }
            return $this->render('edit-place', ['place' => $place]);
        }
        return $this->redirect(Yii::$app->urlManager->createUrl('admin/profiles'));
    }

    public function actionProfilesUsers()
    {
        // get all users
        $users = User::findAll(['type' => User::TYPE_USER]);
        return $this->render('list-users', ['users' => $users]);
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
