<?php

namespace App\Http\Controllers;

use App\Service\FileToolsService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function jsonResult($data, array $header = [])
    {
        return response()->json($data)->withHeaders($header);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed     $msg 提示信息
     * @param mixed     $data 返回的数据
     * @return void
     */
    protected function success($data=[], $msg='')
    {
        $result = [
            'isSuccess' => true,
            'msg'  => $msg,
            'data' => $data
        ];

        return response()->json($result);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed     $msg 提示信息
     * @param mixed     $data 返回的数据
     * @return void
     */
    protected function error($msg = '', $data = '')
    {
        $result = [
            'isSuccess' => false,
            'msg'  => $msg,
            'data' => $data,
        ];

        return response()->json($result);
    }

    /**
     * 创建文件夹
     * @param Request $request
     */
    protected function mkDir(Request $request)
    {
        $pid     = $request->input('pid');
        $dirname = $request->input('name');
        if (FileToolsService::isExist($dirname, $pid, Auth::id(), $this->cloudid, $this->belongType)) {
            $this->ajaxReturn(array('isSuccess' => false, 'msg' => Ibos::lang('The samename folder exist')));
        }
        $fid = FileOperationApi::getInstance()->mkDir($this->getFileAttr($pid), $dirname);
        if ($fid) {
            $this->ajaxReturn(array('isSuccess' => true, 'msg' => Ibos::lang('Create succeed'), 'fid' => $fid));
        } else {
            $this->ajaxReturn(array('isSuccess' => false, 'msg' => Ibos::lang('Create failed')));
        }
    }

}
