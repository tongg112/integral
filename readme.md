<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## About Integral
这是一个使用laravel开发的积分系统
## 使用
执行 `composer install`

执行 `php artisan migrate`

执行 `npm install`

执行 `npm run dev`或者`npm run production`

更新日志

v0.4.1
增加积分变更备注功能

v0.5.0
增加积分变动列表

v0.5.1
对列表的积分变动增加颜色标记

v0.5.2
实现积分列表自动刷新

v0.5.4
修改布局，积分变动后重置输入框

v0.5.5
积分系统的等级规则：
    等级n的积分区间：2^n ~ (2^(n+1)) - 1
    例：
    lvl0 : 0 ~ 1 // 2^0 ~ (2^1) - 1
    lvl1 : 2 ~ 3 // 2^1 ~ (2^2) - 1
    lvl2 : 4 ~ 7 // 2^2 ~ (2^3) - 1
    .
    .
    .
    
---
## 部署详细步骤
- 登录服务器
- 安装lnmp（失败！因为宝塔面板兼容问题导致服务器cpu满负载，直接死机。如果没有安装宝塔面板等工具，则可以执行成功）
安装screen
```
yum install screen
```
打开新的screen窗口
```
screen
```
在`https://lnmp.org/auto.html`页面生成无人值守安装脚本，并在screen中粘贴执行
```
wget http://soft.vpser.net/lnmp/lnmp1.5.tar.gz -cO lnmp1.5.tar.gz && tar zxf lnmp1.5.tar.gz && cd lnmp1.5 && LNMP_Auto="y" DBSelect="4" DB_Root_Password="root" InstallInnodb="y" PHPSelect="8" SelectMalloc="1" ./install.sh lnmp
```
最小化screen：ctrl+a然后ctrl+d
恢复screen：`screen -r`
- 安装nodejs、npm
```
cd /usr/local && wget https://npm.taobao.org/mirrors/node/v10.7.0/node-v10.7.0-linux-x64.tar.xz && xz -d node-v10.7.0-linux-x64.tar.xz && tar xvf node-v10.7.0-linux-x64.tar && sudo ln -s /usr/local/node-v10.7.0-linux-x64/bin/node /usr/local/bin/node && 
sudo ln -s /usr/local/node-v10.7.0-linux-x64/bin/npm /usr/local/bin/npm && node -v && npm -v
```
安装成功最后显示node版本和npm版本：
```
v10.7.0
6.1.0
```
- 克隆代码
在想要部署代码的目录进行代码克隆

```
git clone https://github.com/tongg112/dnmp.git
git clone https://github.com/tongg112/integral.git
```
在dnmp目录下，编辑docker-compose.yml
修改端口80:80 为 8090:80（因为其他程序占用了80端口，这里使用其他端口然后做反向代理）
修改mysql的数据库root密码
运行`docker-compose up -d`
运行`docker exec -it dnmp_php72_1 /bin/bash`进入php容器
进入代码目录执行composer install
```
cd integral
composer install
php artisan key:generate
```