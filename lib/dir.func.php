<?php

/**
 * 读取目录下的信息返回
 * @param  string   $path 目标目录
 * @return mixed        false | array
 */
function read_directory($path)
{
    //判断是否为目录
    if (!is_dir($path)) {
        return false;
    }
    //定义空数组
    $info = [];
    $arr = [];
    //打开目录句柄
    $handle = opendir($path);
    //遍历目录
    while (($item = readdir($handle)) !== false) {
        //去掉 . 和 ..
        if ($item != '.' && $item != '..') {
            $filePath = $path.DIRECTORY_SEPARATOR.$item; //文件名
            $info['fileName'] = $filePath;
            $info['showName'] = $item;
            $info['readable'] = is_readable($filePath) ? true : false;
            $info['writable'] = is_writable($filePath) ? true : false;
            $info['executable'] = is_executable($filePath) ? true : false;
            $info['atime'] = date('Y-m-d H:i:s',fileatime($filePath));
            if (is_file($filePath)) {
                $arr['file'][] = $info;
            }
            if (is_dir($filePath)) {
                $arr['dir'][] = $info;
            }
        }
    }
    closedir($handle);
    return $arr;
}

/**
 * 创建目录
 * @param  string   $path   目录名称
 * @return mixed         true|string
 */
function create_dir($path) {
    if (is_dir($path)) {
        return '当前目录已存在同名目录'.$path;
    }
    if (!mkdir($path,0755,true)) {
        return $path.'目录'.$path.'创建失败';
    }
    return true;
}


/**
 * 重命名目录
 * @param  string     $oldName  原目录
 * @param  string     $newName  新目录
 * @return mixed         true|string
 */
function rename_dir($oldName, $newName) {
    if (!is_dir($oldName)) {
        return $oldName.'目录'.$oldName.'不存在';
    }
    if (is_dir($newName)) {
        return '当前目录已存在同名目录'.$newName;
    }
    if (!rename($oldName,$newName)) {
        return '目录'.$oldName.'重命名失败';
    }
    return true;
}

/**
 * 剪切目录
 * @param string $oldName 原目录
 * @param string $dst     目标目录
 * @return mixed   true|string
 */
function cut_dir($oldName, $dst) {
    //原目录是否存在
    if (!is_dir($oldName)) {
        return '目录'.$oldName.'不存在';
    }
    //目标目录是否存在
    if (!is_dir($dst)) {
        mkdir($dst,0755,true);
    }
    //目标目录下是否存在同名目录
    $dest = $dst.DIRECTORY_SEPARATOR.basename($oldName);
    if (is_dir($dest)) {
        return '当前目录已存在同名目录'.$oldName;
    }
    //剪切
    if (!rename($oldName,$dest)) {
        return '目录'.$oldName.'剪切失败';
    }
    return true;
}

/**
 * 复制目录内容
 * @param string  $oldName 原目录
 * @param string  $dst     新目录
 * @return mixed   true|string
 */
function copy_dir($oldName, $dst) {
    if (!is_dir($oldName)) {
        return '目录'.$oldName.'不存在';
    }
    if (!is_dir($dst)) {
        mkdir($dst,0755,true);
    }
    $dest = $dst.DIRECTORY_SEPARATOR.basename($oldName);
    if (is_dir($dest)) {
        return '当前目录已存在同名目录'.$oldName;
    }
    //打开文件句柄
    $handle = opendir($oldName);
    // var_dump($handle);exit;
    //打开目录
    while (($item = readdir($handle)) !== false) {
        //去掉 . ..
        if ($item != '.' && $item != '..') {
            $oldFile = $oldName.DIRECTORY_SEPARATOR.$item;
            $newFile = $dst.DIRECTORY_SEPARATOR.$item;
            if (is_file($oldFile)) {
                $a = copy($oldFile,$newFile);
                // dump($a);exit;
            }
            if (is_dir($oldName)) {
                $func = __FUNCTION__;
                $func($oldFile,$newFile);
            }
        }
    }
    //关闭句柄
    closedir($handle);
    return true;
}