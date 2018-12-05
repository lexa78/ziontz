<?php

namespace app\models;

use app\models\helpers\PrepareData;
use app\models\traits\BatchTrait;
use Yii;

/**
 * This is the model class for table "ips".
 *
 * @property int $id
 * @property string $ip
 *
 * @property Logs[] $logs
 */
class Ip extends \yii\db\ActiveRecord
{
    use BatchTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Logs::className(), ['ip_id' => 'id']);
    }

    public static function batchInsert($data)
    {
        $ips = PrepareData::prepareSingleDataForSave($data, 'ip');
        $ip = new self();
        $ip->insertIgnore($ips);
    }

    public static function makeIpAsKeyArray()
    {
        $ipsArr = [];
        $ips = self::find()->all();
        while($ipModel = array_pop($ips)) {
            $ipsArr[$ipModel->ip] = $ipModel->id;
        }
        unset($ips, $ipModel);
        return $ipsArr;
    }

}
