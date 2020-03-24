<?php
error_reporting(E_ALL^E_NOTICE);
//读取管理项目，并展示
require_once 'lib/dir.func.php';
date_default_timezone_set('PRC');
//定义管理的路径
define('WEBROOT','webRoot');
$path = $_REQUEST['path'] ? $_REQUEST['path'] : WEBROOT;  //获取目标路径
$act = $_REQUEST['act'] ? $_REQUEST['act'] : '';
$dirName = $_REQUEST['dirName'] ? $_REQUEST['dirName'] : '';
$fileName = $_REQUEST['fileName'] ? $_REQUEST['fileName'] : '';
//读取目录内容
$info = read_directory($path);
if (!is_array($info) || empty($info)) {
    exit("<script>
        alert('读取失败');
        location.href='index.php';
    </script>");
}
//根据不同请求完成不同操作
switch ($act) {
    case 'createDir':
        $res = create_dir($path.DIRECTORY_SEPARATOR.$dirName);
        //判断是否创建成功
        if ($res === true) {
            $result['msg'] = $dirName.'创建成功';
            $result['icon'] = 1;
        }else {
            $result['msg'] = $res;
            $result['icon'] = 2;
        }
        //返回JSON字符串
        exit(json_encode($result));
        break;
    case 'renameDir':
        $newName = $path.DIRECTORY_SEPARATOR.$dirName;
        $res = rename_dir($fileName,$newName);
        if ($res === true) {
            $result['msg'] = basename($fileName).'重命名成功';
            $result['icon'] = 1;
        }else {
            $result['msg'] = $res;
            $result['icon'] = 2;
        }
        exit(json_encode($result));
        break;
    case 'cutDir':
        $dest = WEBROOT.DIRECTORY_SEPARATOR.$dirName;
        $res = cut_dir($fileName,$dest);
        if ($res === true) {
            $result['msg'] = basename($fileName).'剪切成功';
            $result['icon'] = 1;
        }else {
            $result['msg'] = $res;
            $result['icon'] = 2;
        }
        exit(json_encode($result));
        break;
    case 'copyDir':
        $dest = WEBROOT.DIRECTORY_SEPARATOR.$dirName;
        $res = copy_dir($fileName,$dest);
        if ($res === true) {
            $result['msg'] = basename($fileName).'复制成功';
            $result['icon'] = 1;
        } else {
            $result['msg'] = $res;
            $result['icon'] = 2;
        }
        exit(json_encode($result));
        break;
    case 'delDir':
        break;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>WEB在线文件管理器</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
      <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
      <!--[if lt IE 9]>
        <!--<script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>-->
      <!--<script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>-->
      <![endif]-->
  </head>
  <body>
  <div class="container">
      <div class="row clearfix">
          <div class="col-md-12 column">
              <nav class="navbar navbar-default" role="navigation">
                  <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">切换导航</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-home" ></span>首页</a>
                  </div>

                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">
                          <li class="active">
                              <a href="javascript:void(0)" id="createDir" data-url="index.php?act=createDir&path=<?php echo $path;?>"><span class="glyphicon glyphicon-folder-open" >新建目录</a>
                          </li>
                          <li>
                              <a herf="#"><span class="glyphicon glyphicon-file" >新建文件</a>
                          </li>
                          <li>
                              <a href="#"><span class="glyphicon glyphicon-upload ">上传文件</a>
                          </li>
                          <li>
                              <a href="#"><span class="glyphicon glyphicon-info-sign ">系统信息</a>
                          </li>
                      </ul>
                      <form class="navbar-form navbar-left" role="search">
                          <div class="form-group">
                              <input type="text" class="form-control" />
                          </div> <button type="submit" class="btn btn-default">搜索</button>
                      </form>
                  </div>

              </nav>
              <div class="jumbotron">
                  <h1>
                      WEB在线文件管理器
                  </h1>
                  <p>
                      WEB在线文件管理器主要是用于管理项目文件，实现在线编辑、修改、删除等操作。
                  </p>
                  <p>
                      <a class="btn btn-primary btn-large" href="#">查看更多 »</a>
                  </p>
              </div>
              <table class="table table-bordered table-hover table-condensed">
                  <thead>
                  <tr>
                      <th>
                          类型
                      </th>
                      <th>
                          名称
                      </th>
                      <th>
                          读/写/执行
                      </th>
                      <th>
                          访问时间
                      </th>
                      <th>
                          操作
                      </th>
                  </tr>
                  </thead>
                  <tbody>
                  <!-- 目录部分 -->
                      <?php
                          if (is_array($info['dir'])): foreach ($info['dir'] as $dir):?>
                              <tr class="success">
                                  <td><span class="glyphicon glyphicon-folder-open" ></td>
                                  <td><?php echo $dir['showName']; ?></td>
                                  <td>
                                      <span class="glyphicon <?php echo $dir['readable']?'glyphicon-ok':'glyphicon-remove'; ?>"></span>
                                      <span class="glyphicon <?php echo $dir['writable']?'glyphicon-ok':'glyphicon-remove'; ?>"></span>
                                      <span class="glyphicon <?php echo $dir['executable']?'glyphicon-ok':'glyphicon-remove'; ?>"></span>
                                  </td>
                                  <td><?php echo $dir['atime']; ?></td>
                                  <td>
                                      <a href="index.php?path=<?php echo $dir['fileName']; ?>" class="btn btn-primary btn-sm">打开</a>
                                      <a href="javascript:void(0)" class="btn btn-primary btn-sm renameDir"  data-url="index.php?act=renameDir&fileName=<?php echo $dir['fileName'];?>&path=<? echo $path;?>" data-showName="<?php echo $dir['showName'];?>">重命名</a>
                                      <a href="javascript:void(0)" class="btn btn-primary btn-sm cutDir" data-url="index.php?act=cutDir&fileName=<?php echo $dir['fileName'];?>&path=<?php echo $path;?>">剪切</a>
                                      <a href="javascript:void(0)" class="btn btn-primary btn-sm copyDir" data-url="index.php?act=copyDir&fileName=<?php echo $dir['fileName'];?>&path=<?php echo $path;?>">复制</a>
                                      <a href="javascript:void(0)" class="btn btn-danger btn-sm delDir" data-url="index.php?act=delDir&fileName=<?php echo $dir['fileName'];?>&path=<? echo $path;?>"data-showName="<?php echo $dir['showName'];?>">>删除</a>
                                  </td>
                              </tr>
                          <?php endforeach; endif; ?>
                  <!-- 文件部分 -->
<!--                  --><?php
                  if (is_array($info['file'])): foreach ($info['file'] as $file):?>
                      <tr class="warning">
                          <td><span class="glyphicon glyphicon-file" ></td>
                          <td><?php echo $file['showName']?></td>
                          <td>
                              <span class="glyphicon <?php echo $file['readable']?'glyphicon-ok':'glyphicon-remove'; ?>"></span>
                              <span class="glyphicon <?php echo $file['writable']?'glyphicon-ok':'glyphicon-remove'; ?>"></span>
                              <span class="glyphicon <?php echo $file['executable']?'glyphicon-ok':'glyphicon-remove'; ?>"></span>
                          </td>
                          <td><?php echo $file['atime']; ?></td>
                          <td>
                              <a href="#" class="btn btn-primary btn-sm">查看</a>
                              <a href="#" class="btn btn-primary btn-sm">编辑</a>
                              <a href="#" class="btn btn-primary btn-sm">下载</a>
                              <a href="#" class="btn btn-primary btn-sm">重命名</a>
                              <a href="#" class="btn btn-primary btn-sm">剪切</a>
                              <a href="#" class="btn btn-primary btn-sm">复制</a>
                              <a href="#" class="btn btn-danger btn-sm">删除</a>
                          </td>
                      </tr>
                  <?php endforeach; endif; ?>
                  </tbody>
              </table>


          </div>
      </div>
  </div>

    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="js/bootstrap.min.js"></script>
    <script src="layer/layer.js"></script>
    <script src="js/dir.js"></script>
    <script src="js/file.js"></script>
  </body>

</html>