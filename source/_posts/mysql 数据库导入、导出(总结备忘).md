---
title: mysql 数据库导入/导出(总结备忘)
date: 2017-05-20 20:07:31
tags:
  - Mysql
  - 数据库
desc: mysql 数据库导入/导出(总结备忘)
keywords: MySQL 数据库 数据库优化
categories:
- 数据库

---

在linux下直接用命令行操作就可以 在windows下 一般情况下有两种方法一个也是用命令行 另一个是用phpmyadmin
 
## 1.phpmyadmin
先来说说phpmyadmin 这个工具导出和导入很简单 而且导入时无需建库 直接还原成原来的数据库   用** source ** 
 也可以还原 但他导入文件时有大小限制不能超过20M
<!--more-->
再来说说 ** mysqldump **和 ** source **  用命令操作很快 但是想把导出的文件再导入时必须先建立一个数据库(这个库可以随便起名) 然后进入数据库后才能导入用phpmyadmin导入
** mysqldump **导出的文件也得需要这步
 
## 2.其他命令方式

下面是从前辈那copy来的命令具体使用方法
1.导出整个数据库
``` 
mysqldump -u 用户名 -p 数据库名 > 导出的文件名

```
``` 
mysqldump -uroot -proot dingding > 1.sql
```
2.导出一个表
``` 
mysqldump -u 用户名 -p 数据库名 表名> 导出的文件名
```
``` 
mysqldump -uroot -proot dingding >F:/dingding/wcnc.sql
```
3.导出一个数据库结构
``` 
mysqldump -u wcnc -p -d --add-drop-table smgp_apps_wcnc >d:wcnc_db.sql
```
-d 没有数据 --add-drop-table 在每个create语句之前增加一个drop table
　　
备忘:
> mysqldump在linux下可直接使用 在windows下有时需要进入mysql/bin中使用 因为有时用的是类似appserv的套装软件 这样的话命令就不能直接使用 因为这些命令没在环境变量的目录里 而且用mysqldump导出的备份 必须先建立数据库才能导入


 4.导入数据库
　　常用`` source ``命令
　　进入mysql数据库控制台，
　　如`` mysql -u root -p ``
　　mysql>use 数据库
　　然后使用source命令，后面参数为脚本文件(如这里用到的.sql)
　　`` mysql>source d:wcnc_db.sql``

存疑: phpmyadmin导入有大小限制 不知道source导入时有没限制 而且导出时是不可以限制文件大小 并且分数个文件导出