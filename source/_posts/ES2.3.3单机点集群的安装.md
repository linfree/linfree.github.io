---
title: ES2.3.3单机点集群的安装
date: 2017-05-20 20:07:31
tags:
  - Elasticsearch
desc: Elasticsearch非权威指南，安装
keywords: elasticsearch 学习笔记 大数据 非权威指南 
categories:
- Elasticsearch非权威指南
---

本文介绍在一个机器上，安装三个节点Elasticsearch，并自动组成集群的方式。



<!--more-->
## 第一步：准备工作

我们先准备三个目录，分别如下

`/usr/es`， `/usr/es2`， `/usr/es3`

每个目录下，都方式一份默认的 Elasticsearch 2.3.3 解压后的文件。



## 第二步：修改Elasticsearch 2.3.3的配置文件


网上很多的文章都说不用修改文件即可，有个前提是版本是ES 1.x

修改config下的`elasticserach.yml`文件。这个配置文件非常的重要，也是我们后面在不断深入学习中会一直伴随的一个文件。

主要修改以下内容：

节点名称：`node.name`：

对外服务的http端口，默认为9200：`http.port`  

节点间交互的tcp端口,默认为9300：`transport.tcp.port`

集群中master节点的初始列表，这个必须要设置，因为ES 2.X默认是节点单播发现模式，而不是广播发现模式：`discovery.zen.ping.unicast.hosts`

那么修改完的结果如下：
> 节点一：
```
	node.name: node1  
	http.port: 9200   
	transport.tcp.port: 9300  
	discovery.zen.ping.unicast.hosts :['127.0.0.1','127.0.0.1'] 
```
> 节点二：
```
	node.name: node2
	http.port: 9202 
	transport.tcp.port: 9302
	discovery.zen.ping.unicast.hosts :['127.0.0.1','127.0.0.1:9203']
```

> 节点三：
```
	node.name: node3
	http.port: 9203 
	transport.tcp.port: 9303
	discovery.zen.ping.unicast.hosts :['127.0.0.1','127.0.0.1:9202']
```
## 第三步：启动

按顺序依次启动ES2,ES3,ES。

```
./bin/elasticsearch -d
```

## 第四步：验证：

新打开一个窗口,输入：
```
curl -XGET 'http://localhost:9200/_cluster/health?pretty=true'
```

这个时候，我们就看到了上面的内容。
```
"cluster_name" : "elasticsearch", //集群名称，默认是elasticserarch
"status" : "green",  // 集群的状态，有三个值，green表示正常
"timed_out" : false,  //是否超时，
"number_of_nodes" : 3,//节点个数
"number_of_data_nodes" : 3,//数据节点个数
"active_primary_shards" : 0,//主分片，因为我们尚未创建索引，所以个数是零，默认是5
"active_shards" : 0,//从分片，即复制的分片，默认是一个从复制，所以默认的复制分片也是5.
"relocating_shards" : 0,
"initializing_shards" : 0,
"unassigned_shards" : 0,
"delayed_unassigned_shards" : 0,
"number_of_pending_tasks" : 0,
"number_of_in_flight_fetch" : 0,
"task_max_waiting_in_queue_millis" : 0,
"active_shards_percent_as_number" : 100.0

```
看到了这些内容，就表示单机器多节点集群搭建成功，并运行成功！