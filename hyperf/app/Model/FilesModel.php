<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class FilesModel extends Model
{
    public $appends = [
        'url'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getFileById(int $id): array
    {
        $file = self::where('id', $id)->first()->toArray();
        $disk = $file['disk'];
        $url = make(\Hyperf\Filesystem\FilesystemFactory::class)->get($disk)->getAdapter();
        $file['url'] = $url->getUrl($file['path']);
        return $file;
    }

    public function getUrlAttribute(): string
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];
        $file = make(\Hyperf\Filesystem\FilesystemFactory::class)->get($disk)->getAdapter();
        return $file->getUrl($path);
    }

    public function getSizeAttribute($size)
    {
        if ($size === null) {
            $diskName = $this->attributes['disk'];
            $path = $this->attributes['path'];
            $disk = make(\Hyperf\Filesystem\FilesystemFactory::class)->get($diskName)->getAdapter();
            $size = $disk->getSize($path)['size'];
            $fileModel = new self();
            $id = $this->attributes['id'];
            $file = $fileModel->where('id', $id)->first();
            $file->size = $size;
            $file->save();
        }
        return $size;
    }

    public function setSizeAttribute($value)
    {
        $diskName = $this->attributes['disk'];
        $path = $this->attributes['path'];
        $disk = make(\Hyperf\Filesystem\FilesystemFactory::class)->get($diskName)->getAdapter();
        $this->attributes['size'] = $disk->getSize($path)['size'];
    }

    public function getUrlByName(string $fileCurlName): string
    {
        $fileCurlName = str_replace("mp3", '.mp3',  strtolower($fileCurlName));
        $fileCurlName = str_replace(' ', '', $fileCurlName);
        var_dump($fileCurlName);
        return self::where('path', 'like', "%" . $fileCurlName)->first()->url ?? '';
    }
}
