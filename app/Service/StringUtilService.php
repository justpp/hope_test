<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-12
 * Time: 10:20
 */
namespace App\Service;

use League\Flysystem\Util;

class StringUtilService extends Util
{
    /**
     * 查找是否包含在内
     *
     * @param string|array $string 目标范围
     * @param  string|array $id 所有值
     * @return boolean
     * @author Ring
     */
    public static function findIn($string, $id)
    {
        if (is_array($string)) {
            $string = implode(',', $string);
        }
        if (is_array($id)) {
            $id = implode(',', $id);
        }

        $string = trim($string, ",");
        $newId = trim($id, ",");
        if ($newId == '' || $newId == ',') {
            return false;
        }
        $idArr = array_filter(explode(",", $newId));
        $strArr = array_filter(explode(",", $string));
        if (array_intersect($strArr, $idArr)) {
            return true;
        }
        return false;
    }

    /**
     * 字节格式化单位
     * @param integer $size 大小(字节)
     * @return string 返回格式化后的文本
     */
    public static function sizeCount($size)
    {
        if ($size >= 1073741824) {
            $size = round($size / 1073741824 * 100) / 100 . ' GB';
        } elseif ($size >= 1048576) {
            $size = round($size / 1048576 * 100) / 100 . ' MB';
        } elseif ($size >= 1024) {
            $size = round($size / 1024 * 100) / 100 . ' KB';
        } else {
            $size = $size . ' Bytes';
        }
        return $size;
    }

}
