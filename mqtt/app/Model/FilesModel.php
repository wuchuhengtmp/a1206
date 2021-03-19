<?php
/**
 * Class FilesModel
 * @package App\Model
 * @author wuchuheng  <wuchuheng@163.com>
 */
declare(strict_types=1);

namespace App\Model;

use App\Storages\Storage;

class FilesModel extends BaseModel
{
    private $_tableName = 'files';

    public function createOne(string $path, string $disk): int
    {
        return (int) $this->insert($this->_tableName, ['path' => $path, 'disk' => $disk]);
    }

    /**
     * @param int $fileId
     * @return false|mixed
     */
    public function getFileById(int $fileId)
    {
        $file = $this->get($this->_tableName, '*', ['id' => $fileId]);
        $file['url'] = (new Storage())->disk($file['disk'])->url($file['path']);
        return $file;
    }

    /**
     * @param int $fileId
     * @param int $size]
     */
    public function updateSize(int $fileId, int $size): void
    {
        $this->update($this->_tableName, ['size' => $size], ['id' => $fileId ]);
    }
}