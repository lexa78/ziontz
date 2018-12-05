<?php

namespace app\models;

use app\models\helpers\PrepareData;
use app\models\traits\BatchTrait;
use Yii;

/**
 * This is the model class for table "oss".
 *
 * @property int $id
 * @property string $os
 *
 * @property Logs[] $logs
 */
class Os extends \yii\db\ActiveRecord
{
    use BatchTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['os'], 'required'],
            [['os'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'os' => 'Os',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Logs::className(), ['os_id' => 'id']);
    }

    public static function batchInsert($data)
    {
        $oss = PrepareData::prepareSingleDataForSave($data, 'os');
        $os = new self();
        $os->insertIgnore($oss);
    }

    public static function makeOsAsKeyArray()
    {
        $ossArr = [];
        $oss = self::find()->all();
        while($osModel = array_pop($oss)) {
            $ossArr[$osModel->os] = $osModel->id;
        }
        unset($oss, $osModel);
        return $ossArr;
    }


}
