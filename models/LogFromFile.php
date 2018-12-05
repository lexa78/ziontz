<?php

namespace app\models;

use app\models\helpers\PrepareData;
use app\models\traits\BatchTrait;
use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string $log_date
 * @property string $url_from
 * @property string $url_to
 * @property int $ip_id
 * @property int $os_id
 * @property int $browser_id
 *
 * @property Browsers $browser
 * @property Ips $ip
 * @property Oss $os
 */
class LogFromFile extends \yii\db\ActiveRecord
{
    use BatchTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_date', 'url_from', 'url_to', 'ip_id', 'os_id', 'browser_id'], 'required'],
            [['log_date'], 'safe'],
            [['ip_id', 'os_id', 'browser_id'], 'integer'],
            [['url_from', 'url_to'], 'string', 'max' => 50],
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
            'log_date' => 'Log Date',
            'url_from' => 'Url From',
            'url_to' => 'Url To',
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
        $preparedData = PrepareData::prepareLogDataForSave($data);
        $log = new self();
        $log->insertIgnore($preparedData);
    }
}
