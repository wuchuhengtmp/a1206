<?php
/**
 * Class CategoriesModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use Utils\WsMessage;

class CategoriesModel extends BaseModel
{
    private $_tableName = "categories";

    public function getAll(): array
    {
        return $this->select($this->_tableName, ['id', 'name']);
    }
}