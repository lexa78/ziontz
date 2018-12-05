<?php

namespace app\models;

use app\models\helpers\PrepareData;
use app\models\traits\BatchTrait;
use Yii;

/**
 * This is the model class for table "browsers".
 *
 * @property int $id
 * @property string $browser
 *
 * @property Logs[] $logs
 */
class Browser extends \yii\db\ActiveRecord
{
    use BatchTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'browsers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['browser'], 'required'],
            [['browser'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'browser' => 'Browser',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Logs::className(), ['browser_id' => 'id']);
    }

    public static function batchInsert($data)
    {
        $browsers = PrepareData::prepareSingleDataForSave($data, 'browser');
        $browser = new self();
        $browser->insertIgnore($browsers);
    }

    public static function makeBrowserAsKeyArray()
    {
        $browsersArr = [];
        $browsers = self::find()->all();
        while($browserModel = array_pop($browsers)) {
            $browsersArr[$browserModel->browser] = $browserModel->id;
        }
        unset($browsers, $browserModel);
        return $browsersArr;
    }

}
