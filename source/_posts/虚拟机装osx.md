---
title: 虚拟机装osx
date: 2018-11-10 15:06:34
tags:
  - tools
desc: 虚拟机装osx
keywords: osx
categories:
- tools

---


## 准备工作
### 工具/原料
[VMware® Workstation 12 Pro]()  
[unlocker 208（for OS X 插件补丁）]()  
[Mac OS X 10.11镜像]()   
[darwin.iso]()

## 方法/步骤

### 1. 下载以上文件   
### 2. unlocker208安装   


<!--more-->
![I.png](https://i.loli.net/2017/10/23/59edad5acc0a5.png)  
VM安装完成后，打开任务管理器，找到服务项，选择按描述排序，将框中关于VMware全部停止运行。  

![W7.png](https://i.loli.net/2017/10/23/59edae0758792.png)  

解压`unlocker208`文件，找到`win-install.cmd`文件，**右键以管理员身份运行** 。  

**这一步骤很关键，决定了后续VM会不会识别出OS X。**   

我安装的时候，出现了VM无法识别Mac OS X 的问题，找到了好多 `unlocker`文件都没能解决，最后试了下`208`可以了。

![Q.png](https://i.loli.net/2017/10/23/59edaee451965.png) 

### 3. 创建虚拟机    

### 4. 选择安装程序光盘映像文件，点击选择CDR镜像文件路径  

![FL.png](https://i.loli.net/2017/10/23/59edaf6e13c88.png)  

### 5. 选择安装Apple Mac OS X  

> 如果第二步unlocker文件没有处理好的话，这个地方可能就不会出现Apple Mac OS X。  
> 如果不行，可以多下载几个unlocker试试。版本根据实际版本选择，我的是10.11.  
> 如果还是不行，关闭所有的vm进程和服务后再试试（我就是这样试好的）   

### 6. 名称和安装位置自己定义一下  
### 7. 指定磁盘大小 40G ，我选择的是“将虚拟磁盘拆分成多个文件”,点击"下一步"  
### 8. 自定义硬件 设置内存4G CPU 4个 点击“完成”  

点击编辑虚拟机设置--》点击选项卡--》常规中  “增强型键盘”选择“在可用时使用（推荐）” 不设置,后面是没办法使用键盘操作的  

开启虚拟机会提出错误  
![vv.png](https://i.loli.net/2017/10/23/59edb12934158.png)  

> 解决上面错误方法：找到VM安装的根文件，找到根文件下的 OS X xx.xx.vmx，右键用记事本方式打开，找到`` smc.present = "TRUE" ``在其后面加上`` smc.version = "0" ``  保存关闭，再开启时就没有错误了。  
![2X.png](https://i.loli.net/2017/10/23/59edb1957e5ae.png)  
![L7.png](https://i.loli.net/2017/10/23/59edb21e13cb2.png)  
![SC.png](https://i.loli.net/2017/10/23/59edb24577359.png)  
** 如果开启出现如下图蓝屏项有两种可能：**  

1. CD/DVD(IDE)设置问题 看看设备状态的“启动时连接”是否勾选；  
2. 你下载的镜像文件有问题;  

** 如果开启出现苹果标后重启现象**，基本确定是您的电脑的硬件DEP（数据执行保护）打开了。    
** 关闭操作：** 硬件DEP选项一般都会包含`` "EXECUTE DISABLE BIT", "NX", "DATA EXECUTION PREVENTION" 或 "XD"`` 四个关键词中的一个。  
一般都能在主菜单的“Power”或“Advanced”中找到，设置为Disabled后重新启动电脑(最好是冷启动)即可。
这下启动虚拟机一切正常了  
选择系统语言 `` 继续``  
选择“磁盘工具”选择虚拟磁盘 点击 `` 抹掉 `` 
选择 `` 硬盘安装``  
恭喜你，已经成功了，等待安装完成

