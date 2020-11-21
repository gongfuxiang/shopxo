var fs = require('fs');
var path = require('path');


// 把所有的后缀都改成ttss以及ttml
function fileDisply( filePath ){
    fs.readdir(filePath,function(err,files){
        if(err){
            console.warn(err)
        }else{
            //遍历读取到的文件列表
            files.forEach(function(filename){
                //获取当前文件的绝对路径
                var filedir = path.join(filePath,filename);
                //根据文件路径获取文件信息，返回一个fs.Stats对象
                fs.stat(filedir,function(eror,stats){
                    if(eror){
                        console.warn('获取文件stats失败');
                    }else{
                        var isFile = stats.isFile();//是文件
                        var isDir = stats.isDirectory();//是文件夹
                        if(isFile){
                            console.log(filedir);
                            var regcss = /(wxss)$/;
                            var regswan = /(wxml)$/
                            var regjs = /(js)$/
                            
                            //如果是wxss则转成qss
                            if( regcss.test( filedir ) ){
                                fs.rename( filedir, filedir.replace(regcss,'qss'), function(err){
                                    if(err){
                                        console.error(err);
                                        return;
                                    }
                                })
                            }
                            // 如果是swan的文件转成qml
                            if( regswan.test( filedir ) ){
                                let callback = function(){
                                    fs.rename( filedir, filedir.replace(regswan,'qml'), function(err){
                                        if(err){
                                            console.error(err);
                                            return;
                                        }
                                    })
                                }
                                amendText( filedir ,callback )
                            }
                            // 如果是js文件则将所有的swan转成qq
                            if( regjs.test( filedir ) ){
                                amendSwanToTT( filedir )
                            }
                            
                        }
                        if(isDir){
                            fileDisply(filedir);//递归，如果是文件夹，就继续遍历该文件夹下面的文件
                        }
                    }
                })
            })
        }
    })
}

fileDisply( path.resolve( __dirname ))

function amendText( path ,callback){
    fs.readFile(path,'utf8',function(err,files){
        console.log(err,files)
        var result = files.replace(/wx:for/g,'qq:for')
        .replace(/wx:if/g,'qq:if')
        .replace(/wx:for-item/g,'qq:for-item')
        .replace(/wx:for-index/g,'qq:for-index')
        .replace(/wx:key/g,'qq:key')
        .replace(/wxs/g,'qs');
        fs.writeFile( path, result, 'utf8', function (err) {
            if (err) return console.log(err);
            callback()
        });
    
    })
}

function amendSwanToTT( path ){
    fs.readFile(path,'utf8',function(err,files){
        console.log(err,files)
        var result = files.replace(/wx\./g,'qq.');
        fs.writeFile( path, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
    
    })
}
// test
//amendSwanToTT(path.resolve( __dirname +'/app.js' ))

   