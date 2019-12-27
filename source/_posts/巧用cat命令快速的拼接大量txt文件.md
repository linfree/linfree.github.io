---
title: 巧用cat命令快速的拼接大量txt文件
tags:
  - linux
categories:
  - linux
toc: false
desc: 巧用cat命令快速的拼接大量txt文件
keywords: cat 网络传输 linux 加密
date: 2019-02-19 21:03:16
---

## 遇到问题

记得很久很久以前，有一堆的
```
1.txt
2.txt
3.txt
...
...
9999.txt
10000.txt
```
摆在我面前。

我想把这些文件合并成一个叫`all.txt`的文件。

<!--more-->

## 解决办法


### python

遇到批量问题找python！

三下五除二，python出来了。
```python

import os  
#获取目标文件夹的路径  
meragefiledir = os.getcwd()+'\\MerageFiles'
#获取当前文件夹中的文件名称列表  
filenames=os.listdir(meragefiledir)  
#打开当前目录下的result.txt文件，如果没有则创建
file=open('all.txt','w')  
#向文件中写入字符  
  
#先遍历文件名  
for filename in filenames:  
    filepath=meragefiledir+'\\'
    filepath=filepath+filename
    #遍历单个文件，读取行数  
    for line in open(filepath):  
        file.writelines(line)  
    file.write('\n')  
#关闭文件  
file.close() 
```

标准的读写读写。


### cat命令
一条命令就解决问题
```bash
cat *.txt > new.txt 
```
涨姿势了啊。`cat`还有这般妙用。一直以为是现实内容的。

## 细说cat命令

原来`cat` 命令的名称来源于单词`catenate`，此单词的意思是一个接一个地连接起来。`cat` 命令的用途是连接文件或标准输入并打印，这个命令常用来显示文件内容，或者将 几个文件连接起来显示，或者从标准输入读取内容并显示。


### 基本用法

```
cat [OPTION] [FILE]...
-n 或 --number：由 1 开始对所有输出的行数编号。
-b 或 --number-nonblank：和 -n 相似，只不过对于空白行不编号。
-s 或 --squeeze-blank：当遇到有连续两行以上的空白行，就代换为一行的空白行。
-v 或 --show-nonprinting：使用 ^ 和 M- 符号，除了 LFD 和 TAB 之外。
-E 或 --show-ends : 在每行结束处显示 $。
-T 或 --show-tabs: 将 TAB 字符显示为 ^I。
-e : 等价于 -vE。
-A, --show-all：等价于 -vET。
-t：等价于"-vT"选项；
```

#### exp1 显示文件
```bash
# cat /etc/passwd
root:x:0:0:root:/root:/bin/bash
bin:x:1:1:bin:/bin:/sbin/nologin
narad:x:500:500::/home/narad:/bin/bash
```

#### exp2 显示多个文件
```bash
# cat test test1
Hello everybody
Hi world,
```

#### exp3 使用cat命令创建文件

```bash
# cat > test.txt
Hello everybody
```
#### exp4 cat大文件时候

```bash
# cat test.txt | more
```

#### exp5 显示行号

```bash
# cat -n test.txt
```
### 其他用法

```bash
cat -n file1 > file2 
# 把 file1 的档案内容加上行号后输入 file2 这个档案里
cat -b file1 file2 >> file3 
# file1 和 file2 的文档内容加上行号（空白行不加）之后将内容附加到 file3 文档里