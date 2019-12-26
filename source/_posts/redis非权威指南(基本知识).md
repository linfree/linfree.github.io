---
title: Redis非权威指南(基本知识)
date: 2018-08-20 20:07:31
tags:
  - Redis
  - 数据库
desc: Redis非权威指南(基本知识)
keywords: redis 缓存 基础 教程 php 
categories:
- 数据库

---

## Redis 简介
<center>![redis_logo.png](https://i.loli.net/2017/09/11/59b6a42bf239f.png)</center>  
Redis是完全开源免费的一个高性能的key-value存储系统。
它可以用作数据库、缓存和消息中间件。  
> 
* Redis支持数据的持久化，可以将内存中的数据保持在磁盘中，重启的时候可以再次加载进行使用。
* Redis不仅仅支持简单的key-value类型的数据，同时还提供list，set，zset，hash等数据结构的存储。
* Redis支持数据的备份，即master-slave模式的数据备份。

<!--more-->
## Redis的特点

Redis将其数据库完全保存在内存中，因此性能极高,能读的速度是110000次/s,写的速度是81000次/s 。  
Redis支持开发人员常用的大多数数据类型，例如列表，集合，排序集和散列等等。这使得Redis很容易被用来解决各种问题，因为我们知道哪些问题可以更好使用地哪些数据类型来处理解决。   
Redis的所有操作都是原子性的，同时Redis还支持对几个操作全并后的原子性执行。  
Redis还支持 publish/subscribe, 通知, key 过期等等特性。

## Redis在项目中的作用

<b>Redis在项目中使用一般作为主要缓存服务。</b>

### 1、会话缓存（Session Cache）

最常用的一种使用Redis的情景是会话缓存（session cache）。用Redis缓存会话比其他存储（如Memcached）的优势在于：Redis提供持久化。

随着 Redis 这些年的改进，很容易找到怎么恰当的使用Redis来缓存会话的文档。甚至广为人知的商业平台Magento也提供Redis的插件。

### 2、全页缓存（FPC）

除基本的会话token之外，Redis还提供很简便的FPC平台。回到一致性问题，即使重启了Redis实例，因为有磁盘的持久化，用户也不会看到页面加载速度的下降，这是一个极大改进，类似PHP本地FPC。

再次以Magento为例，Magento提供一个插件来使用Redis作为全页缓存后端。

此外，对WordPress的用户来说，Pantheon有一个非常好的插件  wp-redis，这个插件能帮助你以最快速度加载你曾浏览过的页面。

### 3、队列

Reids在内存存储引擎领域的一大优点是提供 list 和 set 操作，这使得Redis能作为一个很好的消息队列平台来使用。Redis作为队列使用的操作，就类似于本地程序语言（如Python）对 list 的 push/pop 操作。

如果你快速的在Google中搜索“Redis queues”，你马上就能找到大量的开源项目，这些项目的目的就是利用Redis创建非常好的后端工具，以满足各种队列需求。例如，Celery有一个后台就是使用Redis作为broker，你可以从这里去查看。

### 4、排行榜/计数器

Redis在内存中对数字进行递增或递减的操作实现的非常好。集合（Set）和有序集合（Sorted Set）也使得我们在执行这些操作的时候变的非常简单，Redis只是正好提供了这两种数据结构。

### 5、发布/订阅

发布/订阅的使用场景确实非常多。人们在社交网络连接中使用，还可作为基于发布/订阅的脚本触发器，甚至用Redis的发布/订阅功能来建立聊天系统！

## Redis 对比 memcached
------------------------------
 对比 |  持久化 | 数据一致性 | 数据类型
------|---------|------------|---
redis | 支持持久化 | 无cas命令/有事务| 多种数据结构
memcached | 不支持持久化 | 有cas保证数据一致性 | 单一key-value结构  

## Redis安装

### Window 下安装
下载地址：[https://github.com/MSOpenTech/redis/releases](https://github.com/MSOpenTech/redis/releases)

Redis 支持 32 位和 64 位。这个需要根据你系统平台的实际情况选择，这里我们下载 Redis-x64-xxx.zip压缩包到 C 盘，解压后，将文件夹重新命名为 redis。
![file.png](https://i.loli.net/2017/09/11/59b6985850bac.png)

打开一个 cmd 窗口 使用cd命令切换目录到 C:\redis 运行 :		
``` 
redis-server.exe redis.windows.conf 
```
如果想方便的话，可以把 redis 的路径加到系统的环境变量里，这样就省得再输路径了，后面的那个 redis.windows.conf 可以省略，如果省略，会启用默认的。输入之后，会显示如下界面：
![cmd.png](https://i.loli.net/2017/09/11/59b699409e6c5.png)

这时候另启一个cmd窗口，原来的不要关闭，不然就无法访问服务端了。
切换到redis目录下运行  
``` 
redis-cli.exe -h 127.0.0.1 -p 6379
```
设置键值对: `` set myKey abc`` 
取出键值对: `` get myKey``
![cli.png](https://i.loli.net/2017/09/11/59b69a1a58b58.png)

### Linux下安装
下载地址：[http://redis.io/download](http://redis.io/download)，下载最新文档版本。  
本教程使用的最新文档版本为 2.8.17，下载并安装：  
``` 
$ wget http://download.redis.io/releases/redis-2.8.17.tar.gz
$ tar xzf redis-2.8.17.tar.gz
$ cd redis-2.8.17
$ make
```
make完后 redis-2.8.17目录下会出现编译后的redis服务程序redis-server, 还有用于测试的客户端程序redis-cli,两个程序位于安装目录 src 目录下：  
下面启动redis服务.  

``` 
$ cd src
$ ./redis-server
```

注意这种方式启动redis 使用的是默认配置。  
也可以通过启动参数告诉redis使用指定配置文件使用下面命令启动。

``` 
$ cd src
$ ./redis-server redis.conf
```

redis.conf是一个默认的配置文件。我们可以根据需要使用自己的配置文件。  
启动redis服务进程后，就可以使用测试客户端程序redis-cli和redis服务交互了。 比如:

``` 
$ cd src
$ ./redis-cli
redis> set foo bar
OK
redis> get foo
"bar"
```

### Ubuntu 下安装
在 Ubuntu 系统安装 Redi 可以使用以下命令:

``` 
$sudo apt-get update
$sudo apt-get install redis-server
```

启动 Redis

``` 
$ redis-server
```

查看 redis 是否启动？

``` 
$ redis-cli
```

以上命令将打开以下终端：

``` 
redis 127.0.0.1:6379>  
```

127.0.0.1 是本机 IP ，6379 是 redis 服务端口。现在我们输入 PING 命令。

``` 
redis 127.0.0.1:6379> ping
PONG
```

以上说明我们已经成功安装了redis。

## Redis 配置

Redis 的配置文件位于 Redis 安装目录下，文件名为 redis.conf。
你可以通过 CONFIG 命令查看或设置配置项。
### 1.查看配置(GET命令)

Redis CONFIG 命令格式如下：

``` 
redis 127.0.0.1:6379> CONFIG GET CONFIG_SETTING_NAME
```
#### 例如

``` 
redis 127.0.0.1:6379> CONFIG GET loglevel
```

1) "loglevel"  
2) "notice"

### 2.配置redis(SET命令)

CONFIG SET 命令基本语法：

``` 
redis 127.0.0.1:6379> CONFIG SET CONFIG_SETTING_NAME NEW_CONFIG_VALUE
```

#### 例如

``` 
redis 127.0.0.1:6379> CONFIG SET loglevel "notice"  
OK   
redis 127.0.0.1:6379> CONFIG GET loglevel  

1) "loglevel"
2) "notice"
```

### 3.redis配置文件详解
查看链接：[redis 配置 参数 详解](http://blog.51yip.com/nosql/1724.html)

## Redis 数据类型

Redis支持五种数据类型：string（字符串），hash（哈希），list（列表），set（集合）及zset(sorted set：有序集合)。

### String（字符串）
string是redis最基本的类型，你可以理解成与Memcached一模一样的类型，一个key对应一个value。
string类型是二进制安全的。意思是redis的string可以包含任何数据。比如jpg图片或者序列化的对象 。
string类型是Redis最基本的数据类型，一个键最大能存储512MB。

> 例如

``` 
redis 127.0.0.1:6379> SET name "hello"
OK
redis 127.0.0.1:6379> GET name
"hello"
```

在以上实例中我们使用了 Redis 的 SET 和 GET 命令。键为 name，对应的值为 hello。

<i>注意：一个键最大能存储512MB。</i>

### Hash（哈希）
Redis hash 是一个键名对集合。
Redis hash是一个string类型的field和value的映射表，hash特别适合用于存储对象。
> 例如

``` 
127.0.0.1:6379> HMSET user:1 username hello password hello points 200
OK
127.0.0.1:6379> HGETALL user:1
1) "username"
2) "hello"
3) "password"
4) "hello"
5) "points"
6) "200"
```

以上实例中 hash 数据类型存储了包含用户脚本信息的用户对象。 实例中我们使用了 ``Redis HMSET``, ``HGETALL``命令，user:1 为键值。
每个 hash 可以存储 232 -1 键值对（40多亿）。

### List（列表）

Redis表是简单的字符串列表，按照插入顺序排序。你可以添加一个元素到列表的头部（左边）或者尾部（右边）。
> 例如：
``` 
redis 127.0.0.1:6379> lpush hello redis
(integer) 1
redis 127.0.0.1:6379> lpush hello mongodb
(integer) 2
redis 127.0.0.1:6379> lpush hello rabitmq
(integer) 3
redis 127.0.0.1:6379> lrange hello 0 10
1) "rabitmq"
2) "mongodb"
3) "redis"
redis 127.0.0.1:6379>
```

列表最多可存储 2^32 - 1 元素 (4294967295, 每个列表可存储40多亿)。


### Set（集合）
Redis的Set是string类型的无序集合。  
集合是通过哈希表实现的，所以添加，删除，查找的复杂度都是O(1)。

** sadd命令 **
添加一个string元素到,key对应的set集合中，成功返回1,如果元素已经在集合中返回0,key对应的set不存在返回错误。

``` 
sadd key member
```

> 例如：

``` 
redis 127.0.0.1:6379> sadd hello redis
(integer) 1
redis 127.0.0.1:6379> sadd hello mongodb
(integer) 1
redis 127.0.0.1:6379> sadd hello rabitmq
(integer) 1
redis 127.0.0.1:6379> sadd hello rabitmq
(integer) 0
redis 127.0.0.1:6379> smembers hello

1) "rabitmq"
2) "mongodb"
3) "redis"
```

注意：以上实例中 rabitmq 添加了两次，但根据集合内元素的唯一性，第二次插入的元素将被忽略。  
集合中最大的成员数为 232 - 1(4294967295, 每个集合可存储40多亿个成员)。  

### zset(sorted set：有序集合)

Redis zset 和 set 一样也是string类型元素的集合,且不允许重复的成员。
不同的是每个元素都会关联一个double类型的分数。redis正是通过分数来为集合中的成员进行从小到大的排序。  
** zset的成员是唯一的,但分数(score)却可以重复。**

** zadd 命令**  
添加元素到集合，元素在集合中存在则更新对应score  
`` 
zadd key score member 
``
> 例如
``` 
redis 127.0.0.1:6379> zadd hello 0 redis
(integer) 1
redis 127.0.0.1:6379> zadd hello 0 mongodb
(integer) 1
redis 127.0.0.1:6379> zadd hello 0 rabitmq
(integer) 1
redis 127.0.0.1:6379> zadd hello 0 rabitmq
(integer) 0
redis 127.0.0.1:6379> ZRANGEBYSCORE hello 0 1000

1) "redis"
2) "mongodb"
3) "rabitmq"
```