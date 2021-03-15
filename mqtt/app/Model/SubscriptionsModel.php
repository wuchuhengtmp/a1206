<?php
/**
 * Class SubscriptionsModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;


use Utils\Message;
use Utils\ReportFormat;

class SubscriptionsModel extends BaseModel
{
    private $_tableName = 'subscriptions';

    public function isExistsByMap(array $map = []): bool
    {
        $isDevice = $this->has($this->_tableName, $map);
        return $isDevice;
    }

    /**
     *  获取一条数据
     * @param array $map
     * @return ReportFormat
     */
    public function getFirstByMap(array $map): ReportFormat
    {
        $res = new ReportFormat();
        $hasData = $this->get($this->_tableName,'*', $map);
        if ($hasData) {
            $res->isError = false;
            $res->res = $hasData;
        }
        return $res;
    }

    /**
     * 更新一条订阅
     * @param int $id
     * @param $volums
     */
    public function updateSub(int $id, $volums): void
    {
        $this->update($this->_tableName, $volums, ['id' => $id]);
    }

    /**
     * 添加一条新的订阅
     * @param array $volums
     */
    public function createSub(array $volums): void
    {
        $this->insert($this->_tableName, $volums);
    }
}