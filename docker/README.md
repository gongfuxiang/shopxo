# 用docker-compose 部署shopxo

## Nginx PHP MySQL

方案来源于 [nanoninja/docker-nginx-php-mysql](https://github.com/nanoninja/docker-nginx-php-mysql)涉及到php相关的内容,请参考该仓库

## 部署步骤

1. [安装docker](https://www.docker.com/)  
   [国内用户安装docker](https://www.runoob.com/docker/ubuntu-docker-install.html)  
   [docker从入门到实践](https://legacy.gitbook.com/book/yeasy/docker_practice/details)
2. [安装docker-compose](https://docs.docker.com/compose/reference/overview/)

3. 克隆代码并且修改shopxo文件夹权限
  - git clone git@github.com:gongfuxiang/shopxo.git
  - sudo chmod -R 777 shopxo 

4. 用docker-compose 运行项目包括数据库,如果不想用容器里面的数据库可以单独配置数据库
  - cd shopxo
  - docker-compose up 
  - 访问 localhost:10000 

5. 如果使用容器里面的数据库,可以将数据库的地址设置为容器名称 利用docker网络访问数据库

