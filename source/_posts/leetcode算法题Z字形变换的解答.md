---
title: leetcode算法题Z字形变换的解答
date: 2019-04-20 16:07:31
tags:
  - leetcode
desc: leetcode算法题Z字形变换的解答
keywords:  leetcode 算法题 Z字形变换 解答 学习笔记 大数据
categories:
- leetcode

---

## 题目内容如下
将一个给定字符串根据给定的行数，以从上往下、从左到右进行 Z 字形排列。

比如输入字符串为 "LEETCODEISHIRING" 行数为 3 时，排列如下：

```
L   C   I   R
E T O E S I I G
E   D   H   N
```

之后，你的输出需要从左往右逐行读取，产生出一个新的字符串，比如：`"LCIRETOESIIGEDHN"`。
<!--more-->

请你实现这个将字符串进行指定行数变换的函数：
```
string convert(string s, int numRows);
```
> 示例 1:

输入: `s = "LEETCODEISHIRING", numRows = 3`  

输出: `"LCIRETOESIIGEDHN"`   

> 示例 2: 

输入: `s = "LEETCODEISHIRING", numRows = 4 `  

输出: `"LDREOEIIECIHNTSG"`  

解释:
```
L     D     R
E   O E   I I
E C   I H   N
T     S     G
```

## 题目解答
思路：
明显的解决方案就是row个数组，  
然后循环切割字符串，row个数组里填。  
当最大的时候就往回`-1`,最小时候就`+1`,如此循环。
当然，当`row==1`时候直接返回元字符串


> 解法1

```python
class Solution(object):
    def convert(self, s, numRows):
        """
        :type s: str
        :type numRows: int
        :rtype: str
        """
        tmp = {}
        z = 0
        f = 0
        if numRows == 1:
            print(s)
            return s
        for n in range(numRows):
            tmp[n] = []

        for i in s:
            tmp[z].append(i)
            if f == 1:
                z -= 1
            else:
                z += 1
            if z == (numRows-1):
                f = 1
            elif z == 0:
                f = 0
        r = ""
        for t in tmp:
            r += "".join(tmp[t])
        return r
```
这个解法的效率不高，思路不变，优化一下代码：
```python
class Solution(object):
    def convert(self, s, numRows):
        """
        :type s: str
        :type numRows: int
        :rtype: str
        """
        z = 0
        f = True
        if numRows == 1:
            return s
        tmp = ["" for i in range(numRows)]
        for i in s:
            tmp[z] += i
            if z == (numRows-1) or z == 0: f = not f
            z = z-1 if f else z+1
        return "".join(tmp)
```