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
    public $imagesPathForPictures;

    public function __construct()
    {
        $currentUser = $this->getCurrentUser();
        $this->filePathProforma = getcwd()
            . DIRECTORY_SEPARATOR . 'proforma'
            . DIRECTORY_SEPARATOR . $currentUser->username;
        if (!is_dir($this->filePathProforma))
            mkdir($this->filePathProforma, 0777, true);
        $this->filePathFactura = getcwd()
            . DIRECTORY_SEPARATOR . 'facturi'
            . DIRECTORY_SEPARATOR . $currentUser->username;
        if (!is_dir($this->filePathFactura))
            mkdir($this->filePathFactura, 0777, true);

        $this->imagesPath = getcwd() . DIRECTORY_SEPARATOR . 'place_images'
            . DIRECTORY_SEPARATOR . $currentUser->username . DIRECTORY_SEPARATOR;
        if (!is_dir($this->imagesPath))
            mkdir($this->imagesPath, 0777, true);
        $this->filePathFactura .= DIRECTORY_SEPARATOR;
        $this->filePathProforma .= DIRECTORY_SEPARATOR;
        $this->imagesPathForPictures = Yii::$app->getHomeUrl() . 'place_images'
            . DIRECTORY_SEPARATOR . $currentUser->username . DIRECTORY_SEPARATOR;
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
            throw new \Exception('Няма такъв потребител!');
        }
        return $user;
    }
}