---
title: Elasticsearch简介
date: 2017-05-20 20:07:31
tags:
  - Elasticsearch
desc: Elasticsearch非权威指南-简介
keywords: elasticsearch 学习笔记 大数据 非权威指南 
categories:
- Elasticsearch非权威指南

---

## 一、elasticsearch是什么
> ElasticSearch是一个基于[Lucene](http://baike.baidu.com/item/Lucene?sefr=enterbtn)的搜索服务器。它提供了一个分布式多用户能力的全文搜索引擎，基于RESTful web接口。Elasticsearch是用Java开发的，并作为Apache许可条款下的开放源码发布，是当前流行的企业级搜索引擎。设计用于[云计算](http://baike.baidu.com/view/1316082.htm)中，能够达到实时搜索，稳定，可靠，快速，安装使用方便。
(百度百科)
<!--more-->
理解这段话简单理解就是：ES是一个搜索引擎，是基于Lucene的。它是一个提供了基于[RESTful](http://baike.baidu.com/item/RESTful?sefr=enterbtn) 的web接口，能够达到实时，稳定，可靠，快速的搜索引擎。

Elasticsearch也使用Java开发并使用Lucene作为其核心来实现所有索引和搜索的功能，但是它的目的是通过简单的RESTful API来隐藏Lucene的复杂性，从而让全文搜索变得简单。

ES是开源的，它的官网是：[www.elastic.co](www.elastic.co)，

github项目地址是：[www.github.com/elastic/elasticsearch](https://github.com/elastic/elasticsearch)

中文论坛：[elasticsearc.cn](elasticsearch.cn)

## 二、elasticsearch能做什么

Elasticsearch不仅仅是Lucene和全文搜索，其他特点还包括：
* 分布式的实时文件存储，每个字段都被索引并可被搜索
* 分布式的实时分析搜索引擎
* 可以扩展到上百台服务器，处理PB级结构化或非结构化数据

而且，所有的这些功能被集成到一个服务里面，你的应用可以通过简单的RESTful API、各种语言的客户端甚至命令行与之交互。
总结一句话：**ES是一个功能强大，使用简单的分布式的全文搜索引擎。**

## 三、elasticsearch文档的概念
在Elasticsearch中，数据是以文档(document)形式存在的，归属于一种类型(type),而这些类型存在于索引(index)。和关系型数据库中的概念对比：

| SQL | database | table | row | column|
| :------------------ | :------------- | :------ | :----- | :--------- |
|** elasticsearch** | index | type | document | field|

其实这样的对比并不是完全的准确的，但是有助于我们理解elasticsearch的数据存储格式。

## 四、个人对ES的一些理解

elasticsearch主要优势是：**速度快**，使用方便，分布式的，功能强大。
ES官方的想做的是ELK结合起来做日志分析等工作。估计这也是它最多的应用场景。
ES使用非常方便，官方文档也比较全，社区也很活跃。估计以后的发展会越来越好，应用场景会越来越多。

关于更详细的ES简介可以查看网上有朋友翻译的[《Elasticsearch权威指南》](https://es.xiaoleilu.com/)
也可以看看这个朋友写的ES基本概念：[Elasticsearch学习，请先看这一篇！](http://blog.csdn.net/laoyang360/article/details/52244917)

*[《elasticsearch非权威指南》目录](http://www.jianshu.com/p/ede55b4110b1)*
*本笔记欢迎转载，欢迎分享，转载分享不用通知作者。不过，如果可以的话希望能注明出处，看完文章还能点个赞。*