<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-10
 * Time: 14:32
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class File extends Model
{
    /**
     * 顶级文件夹的idpath
     */
    const TOP_IDPATH = '/0/';

    protected $table = 'file';

    public $timestamps = false;

    /**
     * 添加文件夹
     * @param integer $pid 上级文件夹
     * @param string $name 文件夹名
     * @param integer $uid 用户id
     * @param integer $belong 所属（0为个人，1为公司）
     * @param integer $cloudid 云盘（0为本地，其他为云盘id）
     * @return mixed 返回添加的fid
     * @throws \Exception
     */
    public function addDir($pid, $name, $uid, $belong, $cloudid)
    {
        $data = array(
            'pid' => intval($pid),
            'uid' => intval($uid),
            'name' => htmlspecialchars(strtolower($name)),
            'type' => 1,
            'remark' => '',
            'size' => 0,
            'addtime' => time(),
            'idpath' => self::TOP_IDPATH, //默认在根文件夹，开头必需有斜杠
            'belong' => $belong,
            'cloudid' => $cloudid
        );
        if ($pid > 0) {
            $dir = $this->fetchByPk($pid);
            if (!empty($dir)) {
                $data['idpath'] = $dir['idpath'] . $dir['fid'] . '/';
            }
        }
        $fid = DB::table($this->table)->insertGetId($data);
        return $fid;
    }

    /**
     * 如果缓存存在数据，则直接读取缓存。否则根据主键查找一条记录，返回数组格式
     * @param $pk
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function fetchByPk($pk)
    {
        dd($this->getAttribute($pk));
        $record = $this->fetchCache($pk);
        if (false === $record) {
            $object = $this->find($pk);
            if (is_object($object)) {
                $record = $object->attributes;

                if ($this->getIsAllowCache()) {
                    Cache::set($this->getCacheKey($pk), $record, $this->cacheLife);
                }
            } else {
                $record = null;
            }
        }
        return $record;
    }

    /**
     *  获取数据缓存
     * @param $pk
     * @return bool|mixed 无缓存数据,其他为缓存数据
     * @throws \Exception
     */
    protected function fetchCache($pk)
    {
        $resource = Cache::get($this->getCacheKey($pk));
        if (!$resource){
            $resource = false;
        }
        return $resource;
    }

    /**
     * 获取缓存键值
     * @param string $pk 主键
     * @return string 处理后的键值
     * @throws \Exception
     */
    protected function getCacheKey($pk = '')
    {
        $modelClass = get_class($this);
        if (empty($pk)) {
            $modelPk = $this->getKey();
            if (!$modelPk) {
//                return false;
                throw new \Exception('Cache must have a primary key');
            }
            $pk = $modelPk;
        }
        $key = strtolower($modelClass) . '_' . $pk;
        return $key;
    }


}
