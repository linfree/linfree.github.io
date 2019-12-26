---
title: 如何更改MySQL的datadir目录
date: 2017-05-20 20:07:31
tags:
  - Linux
  - Mysql
  - 数据库
desc: 如何更改MySQL的datadir目录
keywords: 目录 MySQL 数据库 
categories:
- 数据库

---
本人小白，遇到的问题也是小白的问题。写下心得是希望对其他的小白有所帮助。
这两天在倒腾一个比较大的数据库，（Ubuntu环境）发现虚拟机硬盘不够了，所以添加了一块。但是加了硬盘又涉及到了修改mysql数据库的datadir。
本以为只是简单的修改一下配置文件中的

<!--more-->

```
datadir=“目录”
```
就可以了。没想到修改后Mysql居然打不开了。
<!--more-->
于是又开始求助万能的百度:``如何修改Mysql的datadir目录``。结果还是有很多，但基本上是转载的同一篇文章。
说是要修改
```
socket=/var/lib/mysql/mysql.sock //mysql配置文件my.cnf中的这个值
```
还有修改mysql文件的所有者、权限等等。。ps：这一步还是有必要的。
还要修改一堆文件。比如``/etc/init.d/mysqld`` 文件等等。秉着宁错过不放过的原则，我改改改。。然而还是没有什么用。。
反而把配置文件弄得乱七八糟。。
这时候，大神同事给了个建议，直接卸载了重装吧。
然后就百度了一下：``Ubuntu如何干净的卸载mysql?``，得到方法如下：
```
1、删除 mysql
sudo apt-get autoremove --purge mysql-server-5.5
sudo apt-get remove mysql-server
sudo apt-get autoremove mysql-server
sudo apt-get remove mysql-common (非常重要)

2、清理残留数据
dpkg -l |grep ^rc|awk '{print $2}' |sudo xargs dpkg -P
在最后清理数据的时候会弹出一个对话框，问你是否要清除数据，清除就可以完全卸载了。

```
然后又百度了一下：``Ubuntu安装mysql时如何修改datadir？``
稍稍的改了一下找寻目标，结果就发现了一个和之前不一样的答案。
>#####关于如何更改datadir目录的问题：
ubuntu默认安装mysql的时候，会将datadir设置为``/var/lib/mysql``下面，但是我们大多数时候都需要指定一个我们准备好的方便查找的目录为数据存储目录，我们可以在``my.cnf``下面更改``datadir``这一行，将'``=``'后边的目录更改成我们自己的目录即可。
    例如：我将datadir改成``/data``下，则在my.cnf中做如下更改，在``[mysqld]``段
```
port            = 3306
basedir         = /usr
datadir         = /data/mysql

```
更改完成保存退出，可以重启mysql服务了，不知道您的服务器会不会报错，我的反正mysql是起不来了。只要将datadir换回来就能启动。出现这个问题的原因是在ubuntu中存在一个apparmor的服务。

<b><i>这个服务主要作用是主要的作用是设置某个可执行程序的访问控制权限，可以限制程序 读/写某个目录/文件，打开/读/写网络端口等等。</i>(原来，我们虽然讲新的目录所有者改为了mysql,但我们没有告诉mysql要给新的目录什么权限，于是就悲剧了)</b>

他的配置文件在``/etc/apparmor.d/``中，在这里我们可以看到一个``usr.sbin.mysqld``的配置文件，打开看一下就明白了。

我们的日志路径、pid路径等等都存放在这里，所以我们要改datadir路径，这里也需要做更改，要更改log路径同样也要在这里更改。更改后配置如下：
```
  /logs/mysql/mysql.log rw,
  /logs/mysql/mysql.err.log rw,
  /data/mysql/ r,
  /data/mysql/** rwk,
  /logs/mysql/ r,
  /logs/mysql/* rw,
```
 这是我更改过的路径。更改完成以后，因为这是一个服务，所以我们需要重启一下这个服务。
```
/etc/init.d/apparmor restart
```
这里基本上不会存在问题了，(我就是在这里重启了一下服务器，就OK了)，如果还是无法启动你的mysql，那么请使用如下命令
```
mysql_install_db --datadir=/data/mysql
```
查看一下是不是有报错信息，根据错误排查一下。

最终按照这个方法解决了问题。真是多谢前辈。
最后本人做个总结，只作为一种尝试解决方案：
>######将mysql默认的datadir目录"/var/lib/mysql"改为 "/home/mysql_data"

1、关掉数据库
``` 
sudo /etc/init.d/mysql stop

```
2、因为我们指定的数据库文件目录为/home/mysql_data
```
cd /home                     //打开home
mkdir mysql_data             //创建目录
chown mysql:mysql mysql_data       //并修改其拥有者及所属群组为mysql:mysql.命令
//修改mysql配置文件my.cnf：
将 datadir=/var/lib/mysql 改为 datadir=/home/mysql_data

```
3、修改ubuntu中的安全设置
```
sudo gedit /etc/apparmor.d/usr.sbin.mysqld 
在这个文件里面加入权限设定，将原来的
/var/lib/mysql/ r,
/var/lib/mysql/** rwk,
更换成(或直接添加)
/home/mysql_data/mysql/ r,
/home/mysql_data/mysql/** rwk,

```
4、重新初始化数据文件：执行
```
sudo mysql_install_data

```
5、启动mysql数据库服务(或重启服务器)：
```
sudo /etc/init.d/mysql start

```
这个方法中最值得一提是修改``/etc/apparmor.d/usr.sbin.mysqld``这个文件，改变应用程序的权限。这种配置权限方式让我这个小白对linux权限的理解又深刻了不少。