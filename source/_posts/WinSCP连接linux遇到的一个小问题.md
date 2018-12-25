---
title: WinSCP连接linux遇到的一个小问题
date: 2017-05-20 20:07:31
tags:
  - 工具
  - Linux
desc: WinSCP连接linux遇到的一个小问题
keywords: linux
categories:
- tools

---
第一次在win上使用WinSCP来链接linux，遇到的一个小问题：
提示我：
```
服务器拒绝了sftp连接，但它监听ftp连接.想要用ftp协议来代替sftp
```
然后我将链接方式改成了ftp。又爆出另一个蛋疼的提示：

```
由于目标机器积极拒绝，无法连接```

<!--more-->
两个提示都百度了一下，果然有很多答案，说是要关防火墙啊，改连接方式啊等等。满世界的答案都成功的避开了我遇到的问题。
    纠结了很久，我考虑到WinSCP连接linux的原理是什么呢？再仔细想想自己哪一步可能漏掉？？
    突然想起，这是自己新装的一个虚拟机，还没配SSH，难道是这个原因？？
果断的配上了SSH，发现果然OK了。果断的记录一下，如果也有新手跟我遇到同样的问题，就能有所帮助。
关于SSH可以参考另一篇文章：[SSH原理和运用](http://www.jianshu.com/writer#/notebooks/4873723/notes/4574585)