<?php
namespace app\models\traits;

use Yii;

trait BatchTrait
{
    /**
     * main function
     *
     * Example:  $data = [
     *     ['id' => 1, 'name' => 'John'],
     *     ['id' => 2, 'name' => 'Mike'],
     * ];
     * @param array $data is an array of array.
     * @param array $updateColumns NULL or [] means update all columns
     * @return void
     */
    public static function insertOnDuplicateKey(array $data, array $updateColumns = null)
    {
        $db = Yii::$app->db;
        $sql = self::prepareSql($data, $db);
        if(! $sql) {
            throw new \Exception('Ошибка подготовки запроса');
        }
        $sql .= ' ON DUPLICATE KEY UPDATE';
        if (empty($updateColumns)) {
            $fields = static::getFields($data);
            $sql .= static::buildValuesList($fields);
        } else {
            $sql .= static::buildValuesList($updateColumns);
        }
        $db->createCommand($sql)->execute();
    }

    public static function insertIgnore(array $data)
    {
        $db = Yii::$app->db;
        $sql = self::prepareSql($data, $db);
        if(! $sql) {
            throw new \Exception('Ошибка подготовки запроса');
        }
        $sql = str_replace('INSERT', 'INSERT IGNORE', $sql);
        $db->createCommand($sql)->execute();
    }

    private static function prepareSql(array $data, $db)
    {
        if (empty($data)) {
            return false;
        }
        if (!isset($data[0])) {
            $data = [$data];
        }
        $fields = static::getFields($data);
        $sql = $db->queryBuilder->batchInsert(static::getTablePrefix() . static::getTableName(),
            $fields, $data);
        return $sql;
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public static function getTableName()
    {
        $class = get_called_class();
        return (new $class())->tableName();
    }

    /**
     * Get the table prefix.
     *
     * @return string
     */
    public static function getTablePrefix()
    {
        return Yii::$app->db->tablePrefix;
    }
    /**
     * Get get fields of the table from the $data array as keys.
     *
     * @param array $data
     *
     * @return array
     */
    protected static function getFields(array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data.');
        }
        list($first) = $data;
        if (!is_array($first)) {
            throw new \InvalidArgumentException('$data is not an array of array.');
        }
        return array_keys($first);
    }
    /**
     * Build a value list.
     *
     * @param array $updatedColumns
     *
     * @return string
     */
    protected static function buildValuesList(array $updatedColumns)
    {
        $out = [];
        foreach ($updatedColumns as $key => $value) {
            if (is_numeric($key)) {
                $out[] = sprintf('`%s` = VALUES(`%s`)', $value, $value);
            } else {
                $out[] = sprintf('%s = %s', $key, $value);
            }
        }
        return implode(', ', $out);
    }
}