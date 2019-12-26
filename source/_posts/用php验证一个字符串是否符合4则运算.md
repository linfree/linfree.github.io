---
title: 用php验证一个字符串是否符合4则运算
date: 2017-05-20 20:07:31
tags:
  - php
desc: 用php验证一个字符串是否符合4则运算
keywords: php 
categories:
- php

---

实际项目中遇到的一个问题。  
如何实现Google高级查询的字段解析功能。  

例如：
```
$expression = "(张三-(赵六|田七))+朱八";
```
需要解析成：
``` 
and(张三，朱八) not( or(赵六，田七))
```

> 解决思路是：  
> 先判断是否符合+|-三种运算语法。  
> 如果符合，再将内容解析转换为`逆波兰式`。
> 最后拼接查询语句。

检测代码如下：

<!--more-->


```php
    /**
     * 验证关键字是不是符合四则运算
     * @return bool
     */
    private function check_bool($keyword) {

        $k = $keyword != "" ? $keyword : die('关键字不能为空');
        //剔除空白字符
        str_replace(" ", "", $k);
        //符号连续的情况
        if (preg_match("/[\+\-\|]{2,}/", $k)) {
            return false;
        };
        //空括号的情况
        if (preg_match("/\(\)/", $k)) {
            return false;
        }
        //括号不配对
        $stack = [];
        for ($i = 0; $i < strlen($k); $i++) {
            $item = substr($k, $i, 1);
            if ('(' === $item) {
                array_push($stack, '(');
            } else if (')' === $item) {
                if (count($stack) > 0) {
                    array_pop($stack);
                } else {
                    return false;
                }
            }
        }
        if (0 !== count($stack)) {
            return false;
        }
        // 错误情况，(后面是运算符
        if (preg_match("/\([\+\-\|]/", $k)) {
            return false;
        }
        // 错误情况，)前面是运算符
        if (preg_match("/[\+\-\|]\)/", $k)) {
            return false;
        }
        // 错误情况，(前面不是运算符
        if (preg_match("/[^\+\-\|]\(/", $k)) {
            return false;
        }
        // 错误情况，)后面不是运算符
        if (preg_match("/\)[^\+\-\|]/", $k)) {
            return false;
        }
        //没有除了符号外的关键字
        //切割
        $tmp_str = preg_replace('/[\(\)\+\-\|]{1,}/', '`', $k);
        $arr = explode('`', $tmp_str);
        //清除空的数量
        $keys = array_keys($arr, '');
        if (!empty($keys)) {
            foreach ($keys as $key) {
                unset($arr[$key]);
            }
        }
        //如果删除后只剩一个，
        if (count($arr) <= 1) {
            return false;
        }
        for ($i = 0; $i < count($arr); $i++) {
            if ((1 < $i) && ($i < count($arr) - 1)) {
                if (preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/", $arr[$i]) || $arr[$i] == '') {
                    return false;
                }
            } else {
                //var_dump($arr[$i]);
                if (preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/", $arr[$i]) && $arr[$i] != '') {
                    return false;
                }
            }
        }
        return true;
	}
	


	    //测试实例
	$expression = "(A+(BXXX|CXXX)-EXXX+F)-111-211111";
	//$expression = "(张三-(李四-王五)-(赵六|田七))-朱八";
	var_dump(check_bool($expression));
```