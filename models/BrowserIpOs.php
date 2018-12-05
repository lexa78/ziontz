<?php

namespace app\models;

use app\models\helpers\PrepareData;
use app\models\traits\BatchTrait;
use Yii;

/**
 * This is the model class for table "ip_os_browser".
 *
 * @property int $id
 * @property int $ip_id
 * @property int $os_id
 * @property int $browser_id
 *
 * @property Browsers $browser
 * @property Ips $ip
 * @property Oss $os
 */
class BrowserIpOs extends \yii\db\ActiveRecord
{
    use BatchTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ip_os_browser';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip_id', 'os_id', 'browser_id'], 'required'],
            [['ip_id', 'os_id', 'browser_id'], 'integer'],
            [['ip_id', 'os_id', 'browser_id'], 'unique', 'targetAttribute' => ['ip_id', 'os_id', 'browser_id']],
            [['browser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Browsers::className(), 'targetAttribute' => ['browser_id' => 'id']],
            [['ip_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ips::className(), 'targetAttribute' => ['ip_id' => 'id']],
            [['os_id'], 'exist', 'skipOnError' => true, 'targetClass' => Oss::className(), 'targetAttribute' => ['os_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip_id' => 'Ip ID',
            'os_id' => 'Os ID',
            'browser_id' => 'Browser ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrowser()
    {
        return $this->hasOne(Browsers::className(), ['id' => 'browser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIp()
    {
        return $this->hasOne(Ips::className(), ['id' => 'ip_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOs()
    {
        return $this->hasOne(Oss::className(), ['id' => 'os_id']);
    }

    public static function batchInsert($data) {
        $preparedData = PrepareData::prepareIpOsBrDataForSave($data);
        $model = new self();
        $model->insertIgnore($preparedData);
    }

}
