---
title: 常用mysql命令笔记
date: 2019-04-10 22:07:01
tags:
  - MySQL
desc: 常用mysql命令笔记
keywords: mysql note
categories:
- MySQL

---


### 添加用户
```sql
CREATE USER zhangsan IDENTIFIED BY 'zhangsan';
```

### 查看当前用户
```sql
SELECT USER();
SELECT CURRENT_USER();
```
<!--more-->

### 查看用户的权限
```sql
SHOW GRANTS FOR 你的用户;
SHOW GRANTS FOR root@'localhost';
SHOW GRANTS FOR webgametest@10.3.18.158;
```

### 重载权限表
```sql 
FLUSH PRIVILEGES;
```

### 授权
```sql
GRANT ALL PRIVILEGES ON zhangsanDb.* TO zhangsan@'%' IDENTIFIED BY 'zhangsan';
# FLUSH PRIVILEGES;
```
> 说明：
除了“ALL PRIVILEGES”是所有权限外，还有常用的：    
** SELECT **：读取权限。  
** DELETE **：删除权限。  
** UPDATE **：更新权限。  
** CREATE **：创建权限。  
** DROP **：删除数据库、数据表权限。  

###修改密码
```sql
UPDATE mysql.user SET password = PASSWORD('zhangsannew') WHERE	user = 'zhangsan' AND HOST = '%';
#FLUSH PRIVILEGES;
```

### 删除用户
```sql
DROP USER zhangsan@'%';
```

### 导入数据
```sql
# mysql 命令导入
mysql -uroot -p123456 -Ddbname < file.sql
# source 命令导入数据库需要先登录到数库终端：
source /home/abc/abc.sql  # 导入备份数据库
# load命令上传
LOAD DATA LOCAL INFILE 'dump.txt' INTO TABLE mytbl FIELDS TERMINATED BY ':' LINES TERMINATED BY '\r\n';
```

### 数据导出
```bash
# 导出某个表
mysqldump -u root -p DB table > dump.txt
# 导出某个库
mysqldump -u root -p DB > database_dump.txt
# 导出所有数据库：
mysqldump -u root -p --all-databases > database_dump.txt
```

### 查看数据库的引擎
```sql
SHOW ENGINES;
```

### 创建数据库并指定编码
```sql
CREATE DATABASE IF NOT EXISTS my_db default charset utf8 COLLATE utf8_general_ci;
```


### 复制表(同一表结构)
```sql
CREATE TABLE  IF NOT EXISTS teacher_his LIKE teacher ;
```

### 重命名表
```sql
ALTER TABLE t1 RENAME t2; 
```

### 查看当前数据库的编码
```sql
USE DB;
SHOW VARIABLES LIKE 'character_set_database';
```

### 修改数据库的编码
```sql
ALTER DATABASE xxx CHARACTER SET gb2312;
```
