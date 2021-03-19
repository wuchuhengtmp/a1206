<?php
/**
 * Class FilesModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

class FilesModel extends BaseModel
{
    private $_tableName = 'files';

    public function createOne(string $path, string $disk): int
    {
        return (int) $this->insert($this->_tableName, ['path' => $path, 'disk' => $disk]);
    }
}