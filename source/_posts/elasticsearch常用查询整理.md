---
title: elasticsearch常用查询整理
date: 2019-07-19 14:06:32
tags:
  - Elasticsearch
desc: Elasticsearch非权威指南，cat
keywords: elasticsearch 学习笔记 大数据 非权威指南 
categories:
- Elasticsearch非权威指南
---

## 1. 结构话查询（Structured search）

### 1.1 精确查询（term） 
最为常用的 term 查询， 可以用它处理数字（numbers）、布尔值（Booleans）、日期（dates）以及文本（text）。

```json
{
    "term" : {
        "price" : 20
    }
}
```
类似sql的
```sql
SELECT document
FROM   products
WHERE  price = 20
```

<!--more-->
### 1.2 查找多个精确值（terms）
```json
{
    "terms" : {
        "price" : [20, 30]
    }
}
```

### 1.3 范围查询(range)
```json
"range" : {
    "price" : {
        "gte" : 20,
        "lte" : 40
    }
}
```

类似sql的
在 SQL 中，范围查询可以表示为：
```sql
SELECT document
FROM   products
WHERE  price BETWEEN 20 AND 40
```

## 2.全文检索（full-text search）

### 2.1 match查询（match）
查询搜索全文字段中的单个词或多个词

```json
GET /my_index/my_type/_search
{
    "query": {
        "match": {
            "title": "QUICK!"
        }
    }
}
```

### 2.2 match链接符（operator)
match 查询的结构需要做稍许调整才能使用 operator 操作符参数。
下面查询会查找`BROWN`和`DOG`同时存在的doc
```json
GET /my_index/my_type/_search
{
    "query": {
        "match": {
            "title": {      
                "query":    "BROWN DOG!",
                "operator": "and"
            }
        }
    }
}
```

### 2.3 组合查询（bool）
```json
GET /my_index/my_type/_search
{
  "query": {
    "bool": {
      "must":     { "match": { "title": "quick" }},
      "must_not": { "match": { "title": "lazy"  }},
      "should": [
                  { "match": { "title": "brown" }},
                  { "match": { "title": "dog"   }}
      ]
    }
  }
}
```

### 2.4 多字段查询（multi_match）
多匹配查询的类型有多种： best_fields 、 most_fields 和 cross_fields （最佳字段、多数字段、跨字段）

默认情况下，查询的类型是 best_fields ，这表示它会为每个字段生成一个 match 查询，然后将它们组合到 dis_max 查询的内部，如下：
```json
{
  "dis_max": {
    "queries":  [
      {
        "match": {
          "title": {
            "query": "Quick brown fox",
            "minimum_should_match": "30%"
          }
        }
      },
      {
        "match": {
          "body": {
            "query": "Quick brown fox",
            "minimum_should_match": "30%"
          }
        }
      },
    ],
    "tie_breaker": 0.3
  }
}
```
上面这个查询用 multi_match 重写成更简洁的形式：
```json
{
    "multi_match": {
        "query":                "Quick brown fox",
        "type":                 "best_fields", 
        "fields":               [ "title", "body" ],
        "tie_breaker":          0.3,
        "minimum_should_match": "30%" 
    }
}
```
> best_fields 类型是默认值，可以不指定。

还可以模糊字段
```json
{
    "multi_match": {
        "query":  "Quick brown fox",
        "fields": "*_title"
    }
}
```
### 2.5 短语匹配（match_phrase ）

```json
GET /my_index/my_type/_search
{
    "query": {
        "match_phrase": {
            "title": "quick brown fox"
        }
    }
}
```

> 什么是短语  
> 一个被认定为和短语 quick brown fox 匹配的文档，必须满足以下这些要求： > 
> * quick 、 brown 和 fox 需要全部出现在域中。  
> * brown 的位置应该比 quick 的位置大 1 。  
> * fox 的位置应该比 quick 的位置大 2 。  
> 
> 如果以上任何一个选项不成立，则该文档不能认定为匹配  

### 2.6 前缀查询(prefix)
```json
GET /my_index/address/_search
{
    "query": {
        "prefix": {
            "postcode": "W1"
        }
    }
}
```
> 为了支持前缀匹配，查询会做以下事情：
1. 扫描词列表并查找到第一个以 W1 开始的词。  
2. 搜集关联的文档 ID 。  
3. *移动到下一个词。  
4. 如果这个词也是以 W1 开头，查询跳回到第二步再重复执行，直到下一个词不以 W1 为止。  

### 2.7 通配符查询（wildcard）

 wildcard 通配符查询也是一种底层基于词的查询，与前缀查询不同的是它允许指定匹配的正则式。它使用标准的 shell 通配符查询： ? 匹配任意字符， * 匹配 0 或多个字符
```json
GET /my_index/address/_search
{
    "query": {
        "wildcard": {
            "postcode": "W?F*HW" 
        }
    }
}
```

### 2.8 正则查询（regexp）
```json
GET /my_index/address/_search
{
    "query": {
        "regexp": {
            "postcode": "W[0-9].+" 
        }
    }
}
```
> prefix 、 wildcard 和 regexp 查询是基于词操作的，如果用它们来查询 analyzed 字段，它们会检查字段里面的每个词，而不是将字段作为整体来处理。  
> 
>比方说包含 “Quick brown fox” （快速的棕色狐狸）的 title 字段会生成词： quick 、 brown 和 fox 
   


## 3.query_string

### 3.1 query_string
title字段包含crime，且权重为10，也要包含punishment，但是otitle不包含cat，同时author字段包含Fyodor和dostoevsky。

```json
{ 
    "query": {
        "query_string": {
             "query":"title:crime^10 +title:punishment -otitle:cat +author:(+Fyodor +dostoevsky)",
             "default_field":"title"
        }
    }
}
```
常见query_string写法
常见写法：

name字段为obama
```json
{
	"query": {
		"query_string": "name:obama"
	}
}
```

存在一个nam开头的字段，值为obama
```json
{
	"query": {
		"query_string": "nam\\*:obama"
	}
}
```

name字段值为null的文档

```json
{
	"query": {
		"query_string": "__missing__:name"
	}
}
```

name字段值不为null的文档
```json
{
	"query": {
		"query_string": "__exists__:name"
	}
}
```

name字段为Obama或者xidada的文档

```json
{
	"query": {
		"query_string": "name:（obama OR xidada)"
	}
}
```


> Wildcards   
> query的内容中支持？与`*` `？`可以代替一个任意字符、`*`可代表任意个字符（包括零个）。比如你要查询的内容很长，记不清了但是你记得末尾是tor，那么你只需要把query内容写成`*tor`即可  


> 正则  
> 如果要在query的内容中使用正则表达式，在两端加上正斜杠/即可。比如` name:/ob[am]{2}a/`  


### 3.2 simple_query_string查询 
解析出错时不抛异常，丢弃查询无效的部分

```json
{ 
    "query": {
        "simple_query_string": {
             "query":"title:crime^10 +title:punishment -otitle:cat +author:(+Fyodor +dostoevsky)",
             "default_operator":"or"
        }
    }
}
```

### 3.3 标识符查询
```json
{ 
    "query": {
        "ids": {
             "type":"book",
             "values":["1","2","3"]
        }
    }
}
```



## tips
bool查询的
```json
GET test*/_search
{
  "size":3,
  "query": {
    "bool":{
      "must": [
          {"match":{"message": "学生"}},
          {"match":{"message": "所有"}}
        ],
      "should": [
          {"match": {"port": "53198"}},
          {"match": {"@timestamp":"2018-09-17T17:49:25.991Z"}}
        ],
      "must_not": [
          {"match": {"port": "64273"}},
          {"match": {"port":"1234"}}
        ]
    }
  }
}
```
等价于
```json

GET test*/_search
{
  "size":3,
  "query": {
    "query_string":{"query": "message:学生 +message:所有 -port:55714"}
  }
}
```




