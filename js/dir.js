// 创建目录
$('#createDir').on('click',function () {
    var url = $(this).attr('data-url');
    //加载layer prompt
    layer.prompt({
        formType: 0,
        title: '新建目录',
    }, function(value, index){
        //得到用户填写的值之后，通过ajax发送请求，将目录创建成功
        $.ajax({
           url:url+'&dirName='+value,
           type:'GET',
           success:function (data) {
               var obj =JSON.parse(data);
               layer.alert(obj.msg,{icon:obj.icon},function (index) {
                   location.reload();
                   layer.close(index);
               });
           }
        });
         layer.close(index);
    });
});

//重命名目录
$('.renameDir').on('click',function () {
    var url = $(this).attr('data-url');
    var showName = $(this).attr('data-showName');
    //加载layer prompt
    layer.prompt({
        formType:0,
        title:'重命名目录',
        value:showName
    },function (value,index) {
        //得到用户填写的值之后，通过ajax发送请求，将目录重命名
        $.ajax({
            type:'GET',
            url:url+'&dirName='+value,
            success:function (data) {
                var obj = JSON.parse(data);
                layer.alert(obj.msg,{icon:obj.icon},function (index) {
                    location.reload();
                    layer.close(index);
                })
            }
        });
        layer.close(index);
    });
});

//剪切目录
$('.cutDir').on('click',function () {
    //获取原目录路径
    var url = $(this).attr('data-url');
    //加载layer prompt
    layer.prompt({
        formType:0,
        title:'剪切目录',
    },function (value,index) {
        //得到用户填写的值后，通过ajax发送请求，将目录剪切到新目录下
        $.ajax({
            type:'GET',
            url:url+'&dirName='+value,
            success:function (data) {
                //将JSON字符串转换为对象
                var obj = JSON.parse(data);
                layer.alert(obj.msg,{icon:obj.icon},function (index) {
                    location.reload();
                    layer.close(index);
                })
            }
        });
        layer.close(index);
    });
});

//复制目录
$('.copyDir').on('click',function () {
    //获取原目录路径
    var url = $(this).attr('data-url');
    //加载layer prompt
    layer.prompt({
        formType:0,
        title:'复制目录',
    },function (value,index) {
        //得到用户填写的值后，通过ajax发送请求，将目录剪切到新目录下
        $.ajax({
            type:'GET',
            url:url+'&dirName='+value,
            success:function (data) {
                //将JSON字符串转换为对象
                var obj = JSON.parse(data);
                layer.alert(obj.msg,{icon:obj.icon},function (index) {
                    location.reload();
                    layer.close(index);
                })
            }
        });
        layer.close(index);
    })
});

//删除目录
