<?php

namespace app\controllers;

use app\models\Browser;
use app\models\BrowserIpOs;
use app\models\helpers\PrepareData;
use app\models\Ip;
use app\models\LogFromFile;
use app\models\Os;
use app\models\Uploader;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use yii\web\UploadedFile;

class FileLoadController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Uploader();

        if (Yii::$app->request->isPost) {
            $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            if ($fName = $model->upload()) {
                $dataFromFile = PrepareData::handleFile($fName);
                if(! $dataFromFile) {
                    throw new Exception('Не удалось прочитать загружаемый файл');
                }
                if(count($dataFromFile[0]) > 3) {
                    $ips = array_unique(array_column($dataFromFile, 2));
                    Ip::batchInsert($ips);
                    LogFromFile::batchInsert($dataFromFile);
                } else {
                    $ips = array_unique(array_column($dataFromFile, 0));
                    Ip::batchInsert($ips);
                    $browsers = array_unique(array_column($dataFromFile, 1));
                    Browser::batchInsert($browsers);
                    $oss = array_unique(array_column($dataFromFile, 2));
                    Os::batchInsert($oss);
                    BrowserIpOs::batchInsert($dataFromFile);
                }
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionShowTable()
    {
        $dataProvider = new SqlDataProvider([
            'sql' => '
                SELECT i.ip, l1.url_from, l1.log_date first_date, t2.url_to, t2.log_date last_date, t3.uniq_number, o.os, b.browser
                FROM logs l1
                LEFT JOIN
                    (
                        SELECT l11.ip_id, l11.url_to, l11.log_date
                        FROM logs l11
                        WHERE l11.log_date =
                            (
                                SELECT MAX(l22.log_date)
                                FROM logs l22
                                WHERE l11.ip_id = l22.ip_id)
                                GROUP BY l11.ip_id
                            ) t2 ON t2.ip_id = l1.ip_id
                LEFT JOIN
                    (
                        SELECT ip_id, COUNT(url_to) as uniq_number
                        FROM logs
                        GROUP by ip_id ) t3 ON t3.ip_id = l1.ip_id
                LEFT JOIN ip_os_browser iob ON iob.ip_id = l1.ip_id
                LEFT JOIN ips i ON i.id = l1.ip_id
                LEFT JOIN oss o ON o.id = iob.os_id
                LEFT JOIN browsers b ON b.id = iob.browser_id
                WHERE l1.log_date = (SELECT MIN(l2.log_date) FROM logs l2 WHERE l1.ip_id = l2.ip_id)
                GROUP BY l1.ip_id, iob.os_id, iob.browser_id
            ',
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('show_table',['dataProvider' =>$dataProvider]);
    }
}
