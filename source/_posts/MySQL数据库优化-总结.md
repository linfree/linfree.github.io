---
title: MySQL数据库优化-总结
date: 2017-05-20 20:07:31
tags:
  - Mysql
  - 数据库
desc: MySQL数据库优化-总结
keywords: MySQL 数据库 数据库导入
categories:
- 数据库

---


面试时遇到的问题：千万级的mysql数据库如何优化？
作为一个刚入门的phper,遇到这个问题时,我还是压力山大的.还好有一个周末的时间来学习.本文就是这周末三天的整理总结.
<!--more-->
## 方案一:缓存
通过redis或memcache,添加缓存服务器.
原理:将经常查询的内容自动添加到缓存,访问量低的通过正常查询获得,可以让绝大多数的内容从内存中自动访问.

> ridis和memcache的区别:

1 Redis不仅仅支持简单的k/v类型的数据，同时还提供list，set，hash等数据结构的存储。

2 Redis支持数据的备份，即master-slave模式的数据备份。

3 Redis支持数据的持久化，可以将内存中的数据保持在磁盘中，重启的时候可以再次加载进行使用。

## 方案二:添加索引
通过对常用字段添加索引的办法可以极大的提高查询的效率.
注意事项:

1. 首先应考虑在 where 及 order by 涉及的列上建立索引。///禁用排名,`` oder by null``

2. 可以在 num 上设置默认值 0,确保表中 num 列没有 null 值。

3. 不要写一些没有意义的查询.

4. 用 exists 代替 in 是一个好的选择.如:
> 
select num from a where num in(select num from b);  
可以用  
select num from a where exists(select 1 from b where num=a.num);  
来代替.

5. 索引并不是越多越好，索引固然可以提高相应的 select 的效率，但同时也降低了 insert 及 update 的效率，因为 insert 或 update 时有可能会重建索引，所以怎样建索引需要慎重考虑，视具体情况而定。一个表的索引数最好不要超过 6 个，若太多则应考虑一些不常使用到的列上建的索引是否有必要。

6. 尽量使用数字型字段，若只含数值信息的字段尽量不要设计为字符型，这会降低查询和连接的性能,并增加存储开销。这是因为引擎在处理查询和连接时会逐个比较字符串中每一个字符，而对于数字型而言 只需要比较一次就够了。


7. 尽可能的使用 ```varchar/nvarchar ```代替 ```char/nchar ```, 因为首先变长字段存储空间小， 可以节省存储空间， 其次对于查询来说，在一个相对较小的字段内搜索效率显然要高些。


8. 不要使用 ``select * from t ``,用具体的字段列表代替“*”,不要返回用不到的任何字段。

#### 避免全表扫描:

+ 避免在 where 子句中对字段进行 null 值判断，否则将导致引擎放弃使用索引而进行全表扫描。

+ ``` select id from t where name like '%c%';```也将导致全表扫描。

+ 如果在 where 子句中使用参数，也会导致全表扫描.如:`` select id from t where num=@num ;``可以改为强制查询使用索引:`` select id from t with(index(索引名)) where num=@num ;``

+ 在 where 子句中对字段进行表达式操作， 这将导致引擎放弃使用索引而进行全表扫描。如:`` select id from t where num/2=100;``

+ 在 where 子句中对字段进行函数操作，这将导致引擎放弃使用索引而进行全表扫描。如：`` select id from t where substring(name,1,3)='abc';#name 以 abc 开头的 id``

## 方案三:水平分库/分表
原理:  
一个1000多万条记录的用户表user,查询起来非常之慢，分表的做法是将其散列到100个表中，分别从user_0到user_99，然后根据userId分发记录到这些表中.

## 方案四:Sphinx等索引工具
原理:  
Sphinx工具是一个基于SQL的索引检索引擎.原理是将SQL中的数据建立索引,php通过API的方式从Sphinx中获得检索的值.php不直接通过mysql取值.

![Sphinx的原理图](http://upload-images.jianshu.io/upload_images/2229907-552a7cc0a9351d0d.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

### Sphinx的特性（优、缺点）  
#### 优点：  

+ 高速索引 (在新款CPU上,近10 MB/秒);  
+ 高速搜索 (2-4G的文本量中平均查询速度不到0.1秒);  
+ 高可用性 (单CPU上最大可支持100 GB的文本,100M文档);
+ 提供良好的相关性排名
+ 支持分布式搜索;
+ 提供文档摘要生成;
+ 提供从MySQL内部的插件式存储引擎上搜索
+ 支持布尔,短语, 和近义词查询;
+ 支持每个文档多个全文检索域(默认最大32个);
+ 支持每个文档多属性;
+ 支持断词;
+ 支持单字节编码与UTF-8编码;
+ 支持多字段的检索域
+ 支持MySQL（MYISAM和INNODB）和Postgres数据库
+ 支持windows, linux, unix, mac等平台

#### 缺点：
+ 必须要有主键
+ 主键必须为整型
+ 不负责数据存储
+ 配置不灵活

## 方案五:读写分离
原理：  
通过物理的方式来提升mysql的性能.
...未完待续

