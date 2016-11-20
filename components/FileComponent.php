<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 18.11.2016 г.
 * Time: 21:14
 */

namespace app\components;

use app\models\Proforma;
use app\models\User;
use Yii;

class FileComponent
{
    const TYPE_FACTURA = 'ФАКТУРА';
    const TYPE_PROFORMA = 'ПРОФОРМА ФАКТУРА';
    const TYPE_ORIGINAL = 'О Р И Г И Н А Л';
    const TYPE_DUBLICATE = 'К О П И Е';

    public $filePathProforma;
    public $filePathFactura;
    public $imagesPath;

    public function __construct()
    {
        $currentUser = $this->getCurrentUser();
        $this->filePathProforma = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web'
            . DIRECTORY_SEPARATOR . 'proforma'
            . DIRECTORY_SEPARATOR;
        $this->filePathFactura = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web'
            . DIRECTORY_SEPARATOR . 'facturi'
            . DIRECTORY_SEPARATOR;
        $this->imagesPath = Yii::$app->homeUrl . DIRECTORY_SEPARATOR . 'profile_images' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return Proforma
     */
    public function getProforma($id)
    {
        return Proforma::findOne($id);
    }

    /**
     * @return \TCPDF
     */
    public function preparePdfData()
    {
        /* @var $pdf \TCPDF */
        $pdf = new \TCPDF('P');
        $pdf->setPrintHeader(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->setCellHeightRatio(1);
        $pdf->SetFontSize(14);
        $pdf->AddPage();
        $pdf->setPrintFooter(false);
        $pdf->setFooterMargin(1);
        return $pdf;
    }

    /**
     * @return bool|User
     */
    private function getCurrentUser()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Няма такъв потребител!');
            return false;
        }
        return $user;
    }
}