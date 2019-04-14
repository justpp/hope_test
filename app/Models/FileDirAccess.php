<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileDirAccess extends Model
{
    // 公司文件柜读写权限（包括云盘权限）
    protected $table = 'file_dir_access';

    /**
     * 根据fid集查找所有符合条件的权限数据，以fid作键名数组返回
     * @param mix $fids fid数组或逗号隔开字符串
     * @return array
     */
    public function fetchAllSortByFid($fids)
    {
        $fids = is_array($fids) ? implode(',', $fids) : $fids;
        $record = $this->table->whereRaw("FIND_IN_SET(`fid`, '{$fids}')")->get()->toArray();
        $res = array();
        foreach ($record as $r) {
            $res[$r['fid']] = $r;
        }
        return $res;
    }
}
