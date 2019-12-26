---
title: Hadoop+Hbase+ZooKeeper集群搭建方法
date: 2017-05-20 20:07:31
tags:
  - Hadoop
desc: Hadoop+Hbase+ZooKeeper集群搭建方法
keywords: 环境搭建 hadoop hbase zookeeper 
categories:
- 数据库

---
[hadoop的下载地址](http://archive.apache.org/dist/hadoop/common/)
[hbase的下载地址](http://archive.apache.org/dist/hbase/)
[zookeeper的下载地址](http://archive.apache.org/dist/hadoop/zookeeper/)

---

## 1、 主机配置如下：
（添加到/etc/hosts文件里面）   
```
192.168.0.211 master  
#（用于集群主机提供hmaster namenode jobtasker服务 ）  
192.168.0.212 s1      
#(用于集群丛机提供regionsrever datanode tasktacuter服务)  
192.168.0.213 s2  
```
<!--more-->
## 2、安装jdk1.6.2.X  
## 3、添加java环境变量 
（``/etc/profile``），后执行source /etc/profile ,使环境变量立即生效
```
export JAVA_HOME=/usr/java/jdk1.6.0_26/  #java 的目录
export CLASSPATH=$CLASSPATH:$JAVA_HOME/lib:$JAVA_HOME/jre/lib  
export PATH=$JAVA_HOME/bin:$PATH:$CATALINA_HOME/bin  
export HADOOP_HOME=/home/hadoop/hadoop  
export HBASE_HOME=/home/hadoop/hbase  
PATH=$PATH:$JAVA_HOME/bin:$HADOOP_HOME/bin:$HBASE_HOME/bin  

```
## 4、在三台电脑上添加hadoop用户

```
useradd hadoop 
```
## 5、在``/home/hadoop/.bashrc``添加变量

* <u>(将hadoop hbase的配置文件放到hadoop安装包根目录文件下，目的在于以后升级hadoop和hbase的时候不用重新导入配置文件)</u>
*
```
export HADOOP_CONF_DIR=/home/hadoop/hadoop-config  
export HBASE_CONF_DIR=/home/hadoop/hbase-config  
```
## 6、将hadoop hbase zookepper的安装包解压 
到``/home/hadoop/``下，<u>并重命名</u>为``hadoop hbase zookepper``，在``home/hadoop/``下建立``hadoop-config``和``hbase-config``文件夹，并且将``home/hadoop/hadoop/conf``下的``masters、slaves、core-site、mapred-sit、hdfs-site、hadoop-env``拷贝到此文件夹，将``home/hadoop/hbase/conf``下的``hbase-site``和``hbase-env.sh``拷贝到次文件夹。

## 7、修改masters、slaves文件：
 分别为 ``master`` 和``s1``与``s2``

## 8、修改``hadoop-env.sh``的变量：
```
export JAVA_HOME=/usr/java/jdk1.6.0_26/  
export HADOOP_PID_DIR=/home/hadoop/hadoop/tmp 
 
```


##9、修改``core-site.xml ``
```
<configuration> 
<property> 
<name>fs.default.name</name> 
<value>hdfs://master:9000</value> 
</property> 
</configuration> 
```
修改``mapred-site.xml ``
```
<configuration> 
<property> 
   <name>mapred.job.tracker</name> 
   <value>hdfs://master:9001/</value> 
</property>  
</configuration> 
```
修改``hdfs-site.xml``（<u>name和data文件夹不要手动建立</u>）
```
<configuration> 
<property> 
<name>dfs.name.dir</name> 
<value>/home/hadoop/hadoop/name</value> 
</property> 
<property> 
<name>dfs.data.dir</name> 
<value>/home/hadoop/hadoop/data/</value> 
</property> 
<property> 
   <name>dfs.replication</name> 
   <value>3</value> 
</property> 
</configuration>
```
## 10、设置master, s1, s2机几台器之间无密码访问：

## 11、复制目录至集群丛机
```
scp -r /home/hadoop/hadoop s1:/home/hadoop
scp -r /home/hadoop/hadoop s2:/home/hadoop
```
## 12、切换到``/home/hadoop/hadoop``目录下
执行

```
bin/hadoop namenode -format

```
(格式化master主机生成name data tmp等文件夹)

## 13、启动namenode
执行 
```
bin/start-dfs.sh

```
使用jps命令查看``namenode、secondnamenode``是否正常启动：
ie里面输入http://master:50070 查看`namenode`的相关配置信息、运行状态和日志文件

## 14、启动`mapred`
执行 
```
bin/start-mapred.sh
```
使用``jps``命令查看``nomenode、secondnamenode``是否正常启动：
ie里面输入http://master:50030  查看jobtasker的相关配置信息、运行状态和日志文件

---

# hbase+zookeeper集群搭建：

## 1、复制目录修改文件
将``/home/hadoop/hadoop/conf/``目录下的``hbase-site.xml、regionserver和hbase-env.sh``拷贝到``/home/hadoop/hbase-config/``目录下；
编辑``hbase-site.xml``配置文件，如下：
 
```
<property> 
<name>hbase.rootdir</name> 
<value>hdfs://master:9000/hbase</value> 
</property> 
<property> 
<name>hbase.cluster.distributed</name> 
<value>true</value> 
</property> 
<property> 
<name>hbase.master</name> 
<value>master</value> 
</property> 
<property> 
<name>hbase.zookeeper.quorum</name> 
<value>s1,s2</value> 
</property> 
<property> 
<name>zookeeper.session.timeout</name> 
<value>60000000</value> 
</property> 
<property> 
<name>hbase.zookeeper.property.clientport</name> 
<value>2222</value> 
</property> 
```

## 2、编辑regionserver文件
```
S1 
S2
```

## 3、编辑hbase-env.xml文件

```
export JAVA_HOME=/usr/java/jdk1.6.0_26/  
export CLASSPATH=$CLASSPATH:$JAVA_HOME/lib:$JAVA_HOME/jre/lib  
export PATH=$JAVA_HOME/bin:$PATH:$CATALINA_HOME/bin  
export HADOOP_HOME=/home/hadoop/hadoop  
export HBASE_HOME=/home/hadoop/hbase  
export HBASE_MANAGES_ZK=true 
export PATH=$PATH:/home/hadoop/hbase/bin
```

## 4、复制文件到集群丛机
```
scp -r /home/hadoop/hbase s1:/home/hadoop 
scp -r /home/hadoop/hbase s2:/home/hadoop
```

## 5、进入``/home/hadoop/zookeeper/conf/``中

> (1)
```
cp zoo_sample.cfg zoo.cfg
```
> (2)
```
vim zoo.cfg
```
如下： 

```
# The number of milliseconds of each tick  
tickTime=2000 
# The number of ticks that the initial  
# synchronization phase can take  
initLimit=10 
# The number of ticks that can pass between  
# sending a request and getting an acknowledgement  
syncLimit=5 
# the directory where the snapshot is stored.  
dataDir=/home/hadoop/zookeeper/data  
# the port at which the clients will connect  
clientPort=2181 
server.1=s1:2888:3888  
server.2=s2:2888:3888  
```
> (3)
```
touch myid
```
*编辑：1（<u>此序号设置和zoo.cfg里面的server设置要对应</u>) 
```
scp -r /home/hadoop/zookeeper s1:/home/hadoop 
scp -r /home/hadoop/zookeeper s2:/home/hadoop
```
> 4）在所有的节点执行
```
chown -R hadoop.hadoop /home/hadoop```
启动hbase集群：
（1）```/home/hadoop/hbase/bin/start-base.sh```
（2）执行```jps```显示Hmaster是否启动
（3）执行```bin/hbase shell```
 (4)
```
>create 't1' t2'' 't3'#(测试利用hmaster插入数据) 
    >list #（显示已经插入的数据） 
    >t1+t2+t3
```
输入：http://master:60010 

*延伸：Hadoop 页面监控信息网址列表*

将Hadoop中可能用到的网页地址list到下面，方便查阅：

1. http://master:50030

查看MapReduce上的jobtracker（在启动了hdfs和MapReduce之后查阅）

2. http://master:50060 

查看MapReduce上的tasktracker（在启动了hdfs和MapReduce之后查阅）

3. http://master:50070  

查看HDFS上的节点信息（在启动了HDFS之后查阅）

4. http://master:60010/master.jsp

查看master连点信息 （在启动了HDFS、MapReduce、ZooKeeper和HBase之后查阅）

5. http://master:60030/regionserver.jsp

查看regionserver信息（在启动了HDFS、MapReduce、ZooKeeper和HBase之后查阅）

6. http://master:60010/zk.jsp

查看zookeeper信息（在启动了HDFS、MapReduce、ZooKeeper和HBase之后查阅）