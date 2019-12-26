---
title: elasticsearch_cat_api笔记
date: 2018-12-09 22:57:11
tags:
  - Elasticsearch
desc: Elasticsearch非权威指南，cat
keywords: elasticsearch 学习笔记 大数据 非权威指南 
categories:
- Elasticsearch非权威指南
---

如果经常在命令行环境下工作，cat API 对你会非常有用。用 Linux 的 cat 命令命名，这些 API 也就设计成像 *nix 命令行工具一样工作了。

他们提供的统计和前面已经讨论过的 API ( 健康、节点统计 等等 ) 是一样的。但是输出以表格的形式提供，而不是 JSON。对于系统管理员来说这是 非常 方便的，你仅仅想浏览一遍集群或者找出内存使用偏高的节点而已。


<!--more-->
通过 GET 请求发送 cat 命名可以列出所有可用的 API：

```shell
GET /_cat

=^.^=
/_cat/allocation
/_cat/shards
/_cat/shards/{index}
/_cat/master
/_cat/nodes
/_cat/indices
/_cat/indices/{index}
/_cat/segments
/_cat/segments/{index}
/_cat/count
/_cat/count/{index}
/_cat/recovery
/_cat/recovery/{index}
/_cat/health
/_cat/pending_tasks
/_cat/aliases
/_cat/aliases/{alias}
/_cat/thread_pool
/_cat/plugins
/_cat/fielddata
/_cat/fielddata/{fields}
```


### 健康（health）
```
GET /_cat/health?v

epoch   time    cluster status node.total node.data shards pri relo init
1408[..] 12[..] el[..]  1         1         114 114    0    0     114
unassign
```
> `?v`是为了显示数据的标题

### 命令help
我们看到集群里节点的一些统计，不过和完整的 节点统计 输出相比而言是非常基础的。你可以包含更多的指标，但是比起查阅文档，让我们直接问 cat API 有哪些可用的吧。  
 
你可以过对任意 API 添加 `?help` 参数来做到这点：  
```
GET /_cat/nodes?help

id               | id,nodeId               | unique node id
pid              | p                       | process id
host             | h                       | host name
ip               | i                       | ip address
port             | po                      | bound transport port
version          | v                       | es version
build            | b                       | es build hash
...
...
```

### 节点统计（nodes）
```
GET /_cat/nodes?v

host         ip            heap.percent ram.percent load node.role master name
zacharys-air 192.168.1.131           45          72 1.85 d         *      Zach
```

```
GET /_cat/nodes?v&h=ip,port,heapPercent,heapMax

ip            port heapPercent heapMax
192.168.1.131 9300          53 990.7mb
```