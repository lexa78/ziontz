<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class Uploader extends Model
{
    /**
     * @var UploadedFile
     */
    public $uploadedFile;

    public function rules()
    {
        return [
            [['uploadedFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt, log'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = Yii::$app->params['pathUploads'];
            $fileName = $path.'/' . $this->uploadedFile->baseName . '_' . time() . '.' . $this->uploadedFile->extension;
            $this->uploadedFile->saveAs($path.'/' . $this->uploadedFile->baseName . '_' . time() . '.' . $this->uploadedFile->extension);
            return $fileName;
        } else {
            return false;
        }
    }
}