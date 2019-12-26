---
title: elasticsearch各种组件的安装_head_kopf_bigdesk
date: 2017-05-20 20:07:31
tags:
  - Elasticsearch
desc: Elasticsearch非权威指南，安装
keywords: elasticsearch 学习笔记 大数据 非权威指南 
categories:
- Elasticsearch非权威指南
---

## 一、安装Elasticsearch-Head
### 1.插件安装方式（推荐）
在Elasticsearch目录下
```shell
$/bin/plugin install mobz/elasticsearch-head
```
如果提示
```
ERROR: unknown command [-install]. Use [-h] option to list available commands
```
是因为好像2.0以上的版本-install 变成了 install了。

```
elasticsearch/bin/plugin install mobz/elasticsearch-head
```

<!--more-->

### 2.下载安装方式
从[https://github.com/mobz/elasticsearch-head](https://github.com/mobz/elasticsearch-head)下载ZIP包。
```
sudo ./plugin install file:///Users/Richard/Downloads/elasticsearch-head-master.zip
```

二、重启Elasticsearch。访问。  
访问地址是： `http://{你的ip地址}:9200/_plugin/head/`

`http`  端口默认是: `9200` 。 


## 二、安装Elasticsearch-kopf

### 1.插件安装方式（推荐）
在Elasticsearch目录下

```
./bin/plugin install lmenezes/elasticsearch-kopf/{branch|version}
```
支持版本表
| elasticsearch   | version branch    |   latest version
| --- | --- |  --- | 
0.90.X          |   0.90            |   v0.90
1.X             |   1.0             |   v1.6.1
2.X             |   2.0             |   v2.1.1  



### 2.下载安装方式
从[https://github.com/lmenezes/elasticsearch-kopf](https://github.com/lmenezes/elasticsearch-kopf)下载ZIP包。
``` 
sudo ./plugin install file:///dir/elasticsearch-kopf.zip
``` 


## 三、安装Elasticsearch-Bigdesk

###  1.插件安装方式（推荐）
在Elasticsearch目录下
```
./bin/plugin install hlstudio/bigdesk
```
2.下载安装方式
从[https://github.com/hlstudio/bigdesk](https://github.com/hlstudio/bigdesk)下载ZIP包。
```
sudo ./plugin install file:///dir/elasticsearch-kopf.zip
```

