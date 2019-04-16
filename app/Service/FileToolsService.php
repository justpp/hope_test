<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-11
 * Time: 14:31
 */
namespace App\Service;


use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileToolsService
{

    const NONE_ACCESS = 0; // 无权限
    const READABLED = 1; // 只读
    const WRITEABLED = 2; // 读写

    /**
     * 文件类型
     */
    const FILE = 0;

    /**
     * 文件夹类型
     */
    const FOLDER = 1;

    /**
     * 所属个人
     */
    const BELONG_PERSONAL = 0;

    /**
     * 所属公司
     */
    const BELONG_COMPANY = 1;

    /**
     * 每页显示文件个数
     */
    const PAGESIZE = 21;

    /**
     * 顶级文件夹的idpath
     */
    const TOP_IDPATH = '/0/';

    public static function getBreadCrumb($fid)
    {
        $breadCrumbs = array();
        if (!empty($fid)) {
            $dir = self::getByFid($fid);

            $breadCrumbs = self::getParentsByIdPath($dir->idpath);
            array_push($breadCrumbs, $dir);
        }
        return array_values($breadCrumbs);
    }


    /**
     * 根据fid获取一条文件数据
     * @param $fid
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getByFid($fid)
    {
        return DB::table('file as f')
            ->select('*','f.fid as fid')
            ->leftJoin('file_detail as fd','f.fid','=','fd.fid')
            ->where('f.fid',$fid)
            ->first();
    }

    /**
     * 通过idpath获得所有父级,已fid为键值返回父级文件数组
     * @param string $idPath 文件idpath
     * @return array
     */
    public static function getParentsByIdPath($idPath)
    {
        $pids = self::getPidsByIdPath($idPath);
        $parents = self::getListByIdPath($pids);
        return $parents;
    }

    /**
     * 通过idpath获得所有父级id
     * @param string $idPath 文件idpath
     * @return array
     */
    public static function getPidsByIdPath($idPath)
    {
        $pids = array();
        if (preg_match('/^\/(\d+\/)+$/', $idPath)) {
            $idPath = str_replace('/0/', '', $idPath);
            if (!$idPath){
                return $pids;
            }
            $pids = explode('/', trim($idPath, '/'));
        }
        return $pids;
    }

    public static function getListByIdPath($fids)
    {
        $fids = is_array($fids) ? implode(',', $fids) : $fids;
        $re = DB::table('file as f')
            ->leftJoin('file_detail as fd','f.fid','fd.fid')
            ->whereRaw("FIND_IN_SET(f.fid, '{$fids}')")
            ->get()
            ->toArray();
        foreach ($re as $file) {
            $re[$file->fid] = $file;
        }
        return $re;
    }

    public static function getAccess($access,$uid)
    {
        if (empty($access)) {
            return self::WRITEABLED;
        }
        if (empty($access['ruids'])) {
            return self::WRITEABLED;
        }
        $hasRead = self::hasUserAccess($access['ruids'], $uid);
        if ($hasRead) {
            return self::READABLED;
        }
        return self::NONE_ACCESS;
    }

    /**
     * 查找是否有权限
     * @param $access
     * @param $uid
     * @return bool
     */
    public static function hasUserAccess($access,$uid)
    {
        if ( self::findIn($access, $uid)) {
            return true;
        }
        return true;
    }

    /**
     * 查找是否包含在内,两边都可以是英文逗号相连的字符串
     * @param string $strId 目标范围
     * @param  string $id 所有值
     * @return boolean
     */
    protected static function findIn($strId, $id)
    {
        return StringUtilService::findIn($strId, $id);
    }

    /**
     * 获取文件/文件夹信息，用于页面显示
     * @param $fid
     * @return array
     */
    public static function getDirInfo($fid)
    {
        $file = self::getByFid($fid);
        if ($file) {
            if ($file->type == self::FOLDER) {
                $file->size = self::countSizeByFid($file->fid);
            }
            $file->formattedsize = StringUtilService::sizeCount($file->size);
            $file->formattedaddtime = date('Y/m/d', $file->addtime);
        } else {
            $file = (object)[];
            $file->uid = 0;
            $file->formattedsize = 0;
            $file->formattedaddtime = '';
        }
        return $file;
    }

    /**
     * 获取一个文件夹的总大小
     * @param integer $fid 文件夹id（必须是文件夹，若果传的是文件，会返回0）
     * @return integer
     */
    public static function countSizeByFid($fid)
    {
        $fid = intval($fid);
        $size = DB::table('file')
            ->selectRaw("sum(size) as s")
            ->where('idpath','like',"%.'/'.$fid.'/'.%")
            ->first();
        return intval($size->s);
    }

    /**
     * 检测是否存在同名文件或文件夹
     * @param string $name 文件/文件夹名
     * @param integer $pid 父级id
     * @param integer $uid 用户id
     * @param integer $belong 类型，0个人，1公司
     * @return boolean
     */
    public static function isExist($name, $pid, $uid, $cloudid, $belong = 0)
    {
        $name = htmlspecialchars(strtolower($name));
        $pid = intval($pid);
        $uid = intval($uid);
        $where = [
            'name'    => $name,
            'pid'     => $pid,
            'uid'     => $uid,
            'isdel'   => 0,
            'cloudid' => $cloudid,
            'belong'  => $belong
        ];
        $record = File::where($where)->first();

        return !empty($record);
    }

    /**
     * 创建文件夹
     * @param object $fileAttr 文件属性对象
     * @param string $dirName 文件夹名
     * @return mixed
     * @throws \Exception
     */
    public static function mkDir($fileAttr, $dirName)
    {
        $file = new  File();
        $fid = $file->addDir($fileAttr->pid, $dirName, $fileAttr->uid, $fileAttr->belongType, $fileAttr->cloudid);
        return $fid;
    }

}
