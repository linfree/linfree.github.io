---
title: mysql通过触发器远程同步
date: 2019-05-27 14:11:53
tags:
  - MySQL
desc: mysql通过触发器远程同步
keywords: mysql 触发器 
categories:
- MySQL

---

## mysql通过触发器远程同步


>约定：  

* 需要同步的表为: `A表`  
* 中间的表为: `B表`  
* 同步到的远程表为: `C表`  
![逻辑表](https://static.oschina.net/uploads/space/2017/0124/211715_4QI1_733235.png)


<!--more-->
### 一、检查mysql是否支持federated数据引擎
#### 1.查看开启的储存引擎   
```
SHOW ENGINES;
```

#### 2.如果不支持
`Support`的值是`NO`   
`| FEDERATED          | NO      | Federated MySQL storage engine                             | NULL         | NULL | NULL       |`

可能需要修改mysql配置文件；  
若没有`federated`引擎需要配置`my.cnf`文件。
```shell  
vim /usr/local/mysql/my.cnf
```  
在`[mysqld]`后面直接加`federated`，并且注释掉`skip-federated`（前面加#）
```
[mysqld]
federated
```

### 二、建立同步的federated表和远程的表
> ip示例如下：

- 数据源：	      192.168.1.1
- Federated:	  192.168.1.1
- 远程：		  192.168.1.156


#### 原表数据结构如下：
```sql
CREATE TABLE `test_20180425` (
  `id` int(11) NOT NULL,  
  `somthing` int(11) DEFAULT NULL,

) ENGINE=MyISAM DEFAULT CHARSET=utf8;

```
#### federated表

```sql
CREATE TABLE `db_bak` (
  `id` int(11) NOT NULL,
  `somthing` int(11) DEFAULT NULL,
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://abc:abc123@192.168.1.156/remote_db/db_admin'
```


#### 触发器
```sql
DELIMITER $$
USE `db`$$

DROP TRIGGER /*!50032 IF EXISTS */
`t_db_admin_insert`$$

CREATE /*!50017 DEFINER = 'root'@'%' */
TRIGGER `t_db_admin_insert` AFTER INSERT ON `test_20180425` FOR EACH ROW
BEGIN
	INSERT INTO db.`db_bak` (
		`id`,
		`someting`
	)
VALUES
	(
		NEW.id,		
		NEW.someting
	) ;
END ;$$

DELIMITER ;
```