<?php

namespace app\models\helpers;


use app\models\Browser;
use app\models\Ip;
use app\models\Os;

class PrepareData
{
    public static function handleFile($file)
    {
        $handle = fopen($file, "r");
        if ($handle) {
            $result = [];
            while (($row = fgets($handle)) !== false) {
                $result[] = explode('|', $row);
            }
            if (!feof($handle)) {
                return null;
            }
            fclose($handle);
            return $result;
        } else {
            return null;
        }
    }

    private static function makeDateTime($date, $time)
    {
        list($day, $month, $year) = explode('.', $date);
        $datetime = "{$year}-{$month}-{$day} {$time}";
        return $datetime;
    }

    public static function prepareIpOsBrDataForSave($data)
    {
        $ips = Ip::makeIpAsKeyArray();
        $oss = Os::makeOsAsKeyArray();
        $browsers = Browser::makeBrowserAsKeyArray();
        $dataForSave = [];
        while ($item = array_pop($data)) {
            $dataForSave[] = [
                'ip_id' => $ips[$item[0]],
                'os_id' => $oss[$item[2]],
                'browser_id' => $browsers[$item[1]],

            ];
        }
        return $dataForSave;
    }

    public static function prepareLogDataForSave($data)
    {
        $ips = Ip::makeIpAsKeyArray();
        $dataForSave = [];
        while ($item = array_pop($data)) {
            $dateTime = self::makeDateTime($item[0], $item[1]);
            $dataForSave[] = [
                'log_date' => $dateTime,
                'url_from' => $item[3],
                'url_to' => $item[4],
                'ip_id' => $ips[$item[2]]
            ];
        }
        return $dataForSave;
    }

    public static function prepareSingleDataForSave($data, $key)
    {
        $result = [];
        while ($item = array_pop($data)) {
            $result[] = [$key => $item];
        }
        return $result;
    }

    public static function prepareDataForLogSave($data)
    {

    }
}