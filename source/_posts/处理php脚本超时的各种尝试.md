---
title: 处理php脚本超时的各种尝试
date: 2017-12-15 16:04:07
tags:
  - php
desc: 处理php脚本超时的各种尝试
keywords: php ssh 拓展
categories:
- php

---


## php配置方面的调整
1. php代码中的调整  

```php
<?php
	ignore_user_abort();
	set_time_limit(0);
?>
```

<!--more-->
2. php配置文件的修改
```
#/usr/local/php/etc/php.ini
max_execution_time = 0
```

3. php-fpm.conf 参数  

```
request_terminate_timeout = 0
```

* 设置单个请求的超时中止时间. 
* 该选项可能会对`php.ini`设置中的'`max_execution_time`'因为某些特殊原因没有中止运行的脚本有用. 
* 设置为 '`0`' 表示 '`Off`'.
* 当经常出现502错误时可以尝试更改此选项。 
`request_slowlog_timeout = 10s`
* 当一个请求该设置的超时时间后，就会将对应的PHP调用堆栈信息完整写入到慢日志中. 设置为 '`0`' 表示 '`Off`'


## Nginx方面的调整
nginx如果要解析php脚本语言，就必须通过配置`fastcgi`模块来提供对php支持  

### 1. fast_cgi的配置
```
fastcgi_connect_timeout  

配置语法：  fastcgi_connect_timeout 时间(单位为s)   
默认值： fastcgi_connect_timeout 60s  
配置区域： http server location  
配置项说明： 指定nginx与后端fastcgi server连接超时时间  

---
fastcgi_send_timeout   
配置语法：  fastcgi_send_timeout 时间(单位为s)   
默认值： fastcgi_send_timeout 60s;  
配置区域： http server location   
配置项说明：指定nginx向后端传送请求超时时间（指已完成两次握手后向fastcgi传送请求超时时间）   

``` 