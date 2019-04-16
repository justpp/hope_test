<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-15
 * Time: 11:40
 */
namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Service\FileToolsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseFileController extends Controller
{

    protected $request;

    protected $File;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->File = DB::table('file as f');
    }

    /**
     * 创建文件夹
     * @param $belongType
     * @throws \Exception
     */
    protected function mkDir($belongType)
    {
        $pid     = $this->request->input('pid');
        $dirname = $this->request->input('name');
        if (FileToolsService::isExist($dirname, $pid, Auth::id(), 0, $belongType)) {
           return $this->error('The same name folder exist');
        }

        $fileObject = (object)[
            'pid' => $pid,
            'uid' => Auth::id(),
            'cloudid' => 0,
            'belongType' => $belongType,
        ];
        $fid = FileToolsService::mkDir($fileObject, $dirname);
        if ($fid) {
            return $this->success(['fid'=>$fid],'Create succeed');
        } else {
            return $this->error([],'Create failed');
        }
    }
}
