<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 百度编辑器配置信息
// +----------------------------------------------------------------------
// path_type指定的路径值
$config_path_type = PathToParams('path_type', 'other');

// 上传指定路径值 - 符号换成目录分隔符
$upload_path = str_replace('-', '/', $config_path_type);

return [
      // 上传图片配置项
      // 执行上传图片的action名称
      'imageActionName'           =>  'uploadimage',

      // 提交的图片表单名称
      'imageFieldName'            =>  'upfile',

      // 上传大小限制，单位B
      'imageMaxSize'              =>  MyC('home_max_limit_image', 2048000, true),

      // 上传图片格式显示
      'imageAllowFiles'           =>  ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],

      // 是否压缩图片,默认是true
      'imageCompressEnable'       =>  true,

      // 图片压缩最长边限制
      'imageCompressBorder'       =>  1600,

      // 插入的图片浮动方式
      'imageInsertAlign'          =>  'none',

      // 图片访问路径前缀
      'imageUrlPrefix'            =>  '',

      // 上传保存路径,可以自定义保存路径和文件名格式 
      'imagePathFormat'           =>  __MY_ROOT_PUBLIC__.'static/upload/images/'.$upload_path.'/{yyyy}/{mm}/{dd}/{time}{rand:6}',


      // 涂鸦图片上传配置项
      // 执行上传涂鸦的action名称
      'scrawlActionName'      =>  'uploadscrawl',

      // 提交的图片表单名称
      'scrawlFieldName'       =>  'upfile',

      // 上传保存路径,可以自定义保存路径和文件名格式
      'scrawlPathFormat'      =>  __MY_ROOT_PUBLIC__.'static/upload/images/'.$upload_path.'/{yyyy}/{mm}/{dd}/{time}{rand:6}',

      // 上传大小限制，单位B
      'scrawlMaxSize'         =>  MyC('home_max_limit_image', 2048000, true),

      // 上传图片格式显示
      'scrawlAllowFiles'           =>  ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],

      // 图片访问路径前缀
      'scrawlUrlPrefix'       =>  '',

      // 插入的图片浮动方式
      'scrawlInsertAlign'     =>  'none',


      // 截图工具上传
      // 执行上传截图的action名称
      'snapscreenActionName'  =>  'uploadimage',

      // 上传保存路径,可以自定义保存路径和文件名格式
      'snapscreenPathFormat'  =>  __MY_ROOT_PUBLIC__.'static/upload/images/'.$upload_path.'/{yyyy}/{mm}/{dd}/{time}{rand:6}',

      // 图片访问路径前缀
      'snapscreenUrlPrefix'   =>  '',

      // 插入的图片浮动方式
      'snapscreenInsertAlign' =>  'none',


      // 抓取远程图片配置
      // 执行抓取远程图片的action名称
      'catcherLocalDomain'    =>  ['127.0.0.1', 'localhost', 'img.baidu.com'],

      // 执行抓取远程图片的action名称
      'catcherActionName'     =>  'catchimage',

      // 提交的图片列表表单名称
      'catcherFieldName'      =>  'source',

      // 上传保存路径,可以自定义保存路径和文件名格式
      'catcherPathFormat'     =>  __MY_ROOT_PUBLIC__.'static/upload/images/'.$upload_path.'/{yyyy}/{mm}/{dd}/{time}{rand:6}',

      // 图片访问路径前缀
      'catcherUrlPrefix'      =>  '',

      // 上传大小限制，单位B
      'catcherMaxSize'        =>  MyC('home_max_limit_image', 2048000, true),

      // 抓取图片格式显示
      'catcherAllowFiles'     =>  ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],


      // 上传视频配置
      // 执行上传视频的action名称
      'videoActionName'       =>  'uploadvideo',

      // 提交的视频表单名称
      'videoFieldName'        =>  'upfile',

      // 上传保存路径,可以自定义保存路径和文件名格式
      'videoPathFormat'       =>  __MY_ROOT_PUBLIC__.'static/upload/video/'.$upload_path.'/{yyyy}/{mm}/{dd}/{time}{rand:6}',

      // 视频访问路径前缀
      'videoUrlPrefix'        =>  '',

      // 上传大小限制，单位B，默认100MB
      'videoMaxSize'          =>  MyC('home_max_limit_video', 102400000, true),

      // 上传视频格式显示
      'videoAllowFiles'       =>  ['.swf', '.ogg', '.ogv', '.mp4', '.webm', '.mp3'], 


      // 上传文件配置
      // controller里,执行上传视频的action名称
      'fileActionName'        =>  'uploadfile',

      // 提交的文件表单名称
      'fileFieldName'         =>  'upfile',

      // 上传保存路径,可以自定义保存路径和文件名格式
      'filePathFormat'        =>  __MY_ROOT_PUBLIC__.'static/upload/file/'.$upload_path.'/{yyyy}/{mm}/{dd}/{time}{rand:6}',

      // 文件访问路径前缀
      'fileUrlPrefix'         =>  '',

      // 上传大小限制，单位B，默认50MB
      'fileMaxSize'           =>  MyC('home_max_limit_file', 51200000, true),

      // 上传文件格式显示
      'fileAllowFiles'        =>  ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid','.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],


      // 列出指定目录下的图片
      // 执行图片管理的action名称
      'imageManagerActionName'=>  'listimage',

      // 指定要列出图片的目录
      'imageManagerListPath'  =>  __MY_ROOT_PUBLIC__.'static/upload/images/'.$config_path_type.'/',

      // 每次列出文件数量
      'imageManagerListSize'  =>  20,

      // 图片访问路径前缀
      'imageManagerUrlPrefix' =>  '',

      // 插入的图片浮动方式
      'imageManagerInsertAlign'=> 'none',

      // 列出的文件类型
      'imageManagerAllowFiles'=>  ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],


      // 列出指定目录下的文件
      // 执行文件管理的action名称
      'fileManagerActionName' =>  'listfile',

      // 指定要列出文件的目录
      'fileManagerListPath'   =>  __MY_ROOT_PUBLIC__.'static/upload/file/'.$config_path_type.'/',

      // 文件访问路径前缀
      'fileManagerUrlPrefix'  =>  '',

      // 每次列出文件数量
      'fileManagerListSize'   =>  20,

      // 列出的文件类型
      'fileManagerAllowFiles' =>  ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid','.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],

      // 执行视频管理的action名称
      'videoManagerActionName' =>  'listvideo',

      // 指定要列出文件的目录
      'videoManagerListPath'   =>  __MY_ROOT_PUBLIC__.'static/upload/video/'.$config_path_type.'/',

      // 文件访问路径前缀
      'videoManagerUrlPrefix'  =>  '',

      // 每次列出文件数量
      'videoManagerListSize'   =>  20,

      // 列出的文件类型
      'videoManagerAllowFiles' =>  ['.swf', '.ogg', '.ogv', '.mp4', '.webm', '.mp3'], 
];
?>