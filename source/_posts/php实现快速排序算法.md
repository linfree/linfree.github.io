---
title: php实现快速排序算法
date: 2018-03-12 10:35:11
tags:
  - php
desc: php实现快速排序算法
keywords: php 排序 正则表达式 高级
categories:
- php

---


快速排序由C. A. R. Hoare在1960年提出。


## 基本思想

通过一趟排序将要排序的数据分割成独立的两部分，其中一部分的所有数据都比另外一部分的所有数据都要小，然后再按此方法对这两部分数据分别进行快速排序，整个排序过程可以递归进行，以此达到整个数据变成有序序列.

快速排序又是一种分而治之思想在排序算法上的典型应用。本质上来看，快速排序应该算是在冒泡排序基础上的递归分治法。
<!--more-->


## 算法复杂度
在平均状况下，排序 n 个项目要 Ο(nlogn) 次比较。在最坏状况下则需要 Ο(n2) 次比较，但这种状况并不常见。事实上，快速排序通常明显比其他 Ο(nlogn) 算法更快，因为它的内部循环（inner loop）可以在大部分的架构上很有效率地被实现出来。

## 示意图

![image.png](https://i.loli.net/2019/12/27/plkW653EmtYIGLN.png)

## 动态图 

![b7003af33a87e950707fdf2110385343fbf2b416.gif](https://i.loli.net/2019/12/27/gRTB2Jh5cwmpifx.gif)


## PHP实现

```php
function quickSort($arr)
{
	# 数组总数小于1时候返回本身
    if (count($arr) <= 1)
        return $arr;
	# 随机选取一个中间数
	$middle = $arr[0];

    $leftArray = array();
    $rightArray = array();

    for ($i = 1; $i < count($arr); $i++) {
		if ($arr[$i] > $middle)
			# 小于中间数放到左边
			$rightArray[] = $arr[$i];
		else
			# 大于中间数放到右边
            $leftArray[] = $arr[$i];
	}
	## 左边递归
	$leftArray = quickSort($leftArray);
	## 加入中间数
	$leftArray[] = $middle;

    $rightArray = quickSort($rightArray);
    return array_merge($leftArray, $rightArray);
}

```