<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-10
 * Time: 15:09
 */
namespace App\Http\Controllers\File;

use App\Models\FileDirAccess;
use App\Service\FileToolsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends BaseFileController
{

    function index()
    {
        $data = [
            'pid' => 0,
            'idpath' => '/0/',
//            'uploadConfig' => Attach::getUploadConfig(),
            'isManager' => 1
        ];
        return view('file.company',$data);
    }

    public function listData()
    {
        $pid = $this->request->input('pid',0);
        $condition = $this->getCondition($pid);
        if (is_array($condition)){
            $list = $this->File->where($condition)->get()->toArray();
        } else {
            $list = $this->File->whereRaw($condition)->get()->toArray();
        }
        $breadCrumbs = FileToolsService::getBreadCrumb($pid);

        $params = array(
            'pid' => $pid,
            'breadCrumbs' => $breadCrumbs,
//            'data' => $this->handleCompanyList($this->handleList($list['datas']), $pid),
            'data' => $list,
            'page' => [
                "count"=> "1",
                "curPage"=> 0,
                "limit"=> 21
            ],
            'pDir' => $this->mergeCurDirAccess(FileToolsService::getDirInfo($pid), Auth::id())
        );
        return $this->jsonResult($params);
    }

    public function getCondition($pid)
    {
        $where = [
            "f.pid" => $pid,
            "f.belong" => 1, // 0 公司 1 个人
            "f.isdel" => 0,
        ];

//        if () { // 如果不是网盘管理员，查找出有阅读权限的fid
            $fids = DB::table('file as f')
                ->select('f.fid')
                ->leftJoin('file_detail as fd','fd.fid','=','f.fid')
                ->where($where)
                ->pluck('fid')
                ->toArray();
        if (!empty($fids)){
            $fidStr = implode(',', $fids);
            $accessArr = FileDirAccess::whereRaw("FIND_IN_SET(`fid`, '{$fidStr}')")->get()->toArray();
            foreach ($accessArr as $access) {
                if (FileToolsService::getAccess($access, Auth::id()) == FileToolsService::NONE_ACCESS) { // 去掉没有权限的fid
                    $key = array_search($access['fid'], $fids);
                    if (isset($fids[$key])) {
                        unset($fids[$key]);
                    }
                }
            }
        }

            $where = sprintf("FIND_IN_SET(f.`fid`, '%s')", implode(',', $fids));
//        }
        return $where;
    }

    /**
     * 组合当前文件夹的权限
     * @param object $file
     * @param integer $uid
     * @return object
     */
    protected function mergeCurDirAccess($file, $uid)
    {
        if ($file->uid == $uid){
            $file->access = FileToolsService::WRITEABLED;
        } elseif (!empty($file->fid)) {
            $fids = array_merge(array($file->fid), FileToolsService::getPidsByIdPath($file->idpath));
            $fileDirAccess =new FileDirAccess();
            $accessArr = $fileDirAccess->fetchAllSortByFid($fids);
            $file->access = self::getAccess($accessArr, $file, $uid);
        } else {
            $file->access = FileToolsService::READABLED;
        }
        return $file;
    }

    /**
     * 获取实际权限
     * @param array $accessArr 权限数据
     * @param object $file 文件/文件夹数据
     * @param integer $uid 用户id
     * @return integer
     */
    protected function getAccess($accessArr, $file, $uid)
    {
        // 权限赋值
        if (isset($accessArr[$file->fid])) {
            dd('1');
            $access = FileToolsService::getAccess($accessArr[$file->fid], $uid);
        } else if ($file->pid != 0) { // 找父级权限
            dd('2');
            $parentF =  FileToolsService::getByFid($file->pid);
            $access = $this->getAccess($accessArr, $parentF, $uid);
        } else {
            dd('3');
            $access = FileToolsService::READABLED;
        }
dd($access);
        return $access;
    }

    /**
     * 添加文件或文件夹
     */
    public function actionAdd()
    {
        $op = $this->request->input('op');
        $allowOps = array('upload', 'mkDir', 'mkOffice');
        if (!in_array($op, $allowOps)) {
            $this->error('error');
        }
        $pid = $this->request->input('pid');
        $access = FileDirAccess::where(['fid' => $pid])->first();
        if (FileToolsService::getAccess($access, Auth::id()) != FileToolsService::WRITEABLED) {
            $this->error("No write permission");
        }
        return $this->$op(1);
    }
}
