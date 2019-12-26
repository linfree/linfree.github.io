---
title: docker学习笔记
date: 2019-02-01 10:17:51
tags:
  - docker
desc: docker学习笔记
keywords: docker 学习笔记 docker 
categories:
- docker

---


## 1.docker是什么？？  
Docker 是一个开源的应用容器引擎，让开发者可以打包他们的应用以及依赖包到一个可移植的容器中，然后发布到任何流行的Linux 机器上，也可以实现虚拟化。 容器是完全使用沙箱机制，相互之间不会有任何接口。		
讲简单点：docker就类似一个虚拟机软件，但是与虚拟机软件又有所区别。

docker和虚拟机的区别	  
	
![docker和虚拟机的区别.png](https://i.loli.net/2018/10/11/5bbef55470222.png)


<!--more-->
## 2.docker的重要概念
### 镜像（image）
镜像类似于虚拟机的一个快照，一般包含了系统，我们想要的服务程序等。
例如：Nginx的官方image就包含了，基本的linux操作系统和Nginx服务器的程序。

### 容器（container）
镜像（image）我们可以把它看做一个虚拟机的快照文件。这个快照

### 仓库（repository）
docker在很多地方都借鉴了git的优秀思想，仓库这个估计也是。
仓库是一个集中存放镜像的地方。
这样做的好处有很多，最典型的就是，我们需要在内网多个服务器上使用某个镜像时，可以从本地的镜像仓库中pull。

* 注册服务器（Registry），一个注册服务器上可以有多个仓库，一个仓库里可以放多个镜像。

### docker架构图   
![架构图.png](https://i.loli.net/2018/10/11/5bbf09d504115.png)


## 3.docker的基本操作	
### 基本操作图	
1. 常用命令   
![常用命令.png](https://i.loli.net/2018/10/11/5bbf0c83a9bb9.png)  	

2. 简单版  	
![docker_cli_stage.png](https://i.loli.net/2018/10/11/5bbf0d0247ed9.png)   	

### 镜像（image）的操作	

```
docker images
```
列出全部的image	

```
docker rmi [imageID,name]
```
删除指定的某个镜像，可以用id或者image的name。	
注：当有镜像正在被使用的时候是无法删除的	

```
docker tag [image_name:tag] [new_name:new_tag]
```
docker给镜像（image）写标签

```
docker history [imageID,name]
```
查看指定的镜像构成的历史

```
docker run -it [imageID,name]
```
执行一个镜像到

### 容器（container）的操作  
```
docker ps -a
```
查看当前的容器

```
docker start 
```
