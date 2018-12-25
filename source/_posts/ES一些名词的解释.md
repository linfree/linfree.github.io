---
title: Elasticsearch名词解释
date: 2017-05-20 20:07:31
tags:
  - Elasticsearch
desc: Elasticsearch非权威指南-名词的解释
keywords: elasticsearch 学习笔记 大数据 非权威指南 
categories:
- Elasticsearch非权威指南

---
本来这个打算后面再来写的，看到简书上一个朋友归纳好了，我就直接借过来了。

## 数据层面：

> **Index**：</span>Elasticsearch用来存储数据的逻辑区域，它类似于关系型数据库中的db概念。一个index可以在一个或者多个shard上面，同时一个shard也可能会有多个replicas。

>** Document type**：为了查询需要，一个index可能会有多种类型document，也就是会有多个         document type，但需要注意，不同的document type里面同名的field一定要是相同类型的。

> **Document**：Elasticsearch里面存储的实体数据，类似于关系数据中一个table里面的一行数据。

> **field：**document由多个field组成，不同类型的document里面同名的field一定具有相同的类型。

>**multivalued**： document里面field可以重复出现，也就是一个field会有多个值，即multivalued。

>**Mapping**：存储field的相关映射信息，不同document type会有不同的mapping。

* *对于熟悉MySQL的童鞋，我们只需要大概认为Index就是一个database，document就是一行数据，field就是table的column，mapping就是table的定义，而document type就是一个table就可以了。*
***
*[《elasticsearch学习笔记》目录](http://www.jianshu.com/p/ede55b4110b1)*
*本笔记欢迎转载，欢迎分享，转载分享不用通知作者。不过，如果可以的话希望能注明出处，看完文章还能点个赞。*