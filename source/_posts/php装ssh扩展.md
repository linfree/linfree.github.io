---
title: php装ssh扩展
date: 2017-05-20 20:07:31
tags:
  - php
desc: php装ssh扩展
keywords: php ssh 拓展
categories:
- php

---

## 1.编译安装libssh2

``` 
wget http://www.libssh2.org/download/libssh2-1.2.9.tar.gz

tar zxvf libssh2-1.2.9.tar.gz

cd libssh2-1.2.9

./configure && make && make install
```
<!--more-->

## 2.编译安装ssh2(官网http://www.php.net/ssh2)
```
wget   http://pecl.php.net/get/ssh2-0.11.3.tgz

tar zxvf ssh2-0.11.3.tgz

cd ssh2-0.11.3

phpize（如果没有找到该命令，请确定是否安装的是php-dev）

./configure --with-ssh2 --with-php-config=/usr/local/php/bin/php-config

make
```
## make后有两种方案

### 方法1:
```
make install
```
### 方法2

```
cp modules/ssh2.so /usr/local/php/lib/php/extensions/no-debug-non-zts-20060613/
```
然后
``` 
echo "extension=ssh2.so" >> /usr/local/php/etc/php.ini 
# (视php.ini的具体位置确定，也可能是/etc/php.ini)
```