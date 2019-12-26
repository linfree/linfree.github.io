---
title: SQLAlchemy的基本操作
date: 2018-09-23 19:57:17
tags:
  - python
desc: SQLAlchemy的基本操作
keywords: python SQLAlchemy 
categories:
- python

---

## 安装

通过pip安装SQLAlchemy

```shell
$ pip install sqlalchemy
```

<!--more-->
## 连接数据库

### Flask config配置方法  
以MySQL为例   
```py  
#config.py
DB_URI = "mysql://{}:{}@{}:{}/{}?charset=utf8".format(USERNAME,PASSWORD,HOST_NAME,PROT,DATABAES)
SQLALCHEMY_DATABASE_URI =DB_URI

#models.py
from flask import Flask
from flask_sqlalchemy import SQLAlchemy
db = SQLAlchemy()
app = Flask(__name__)
app.config.from_object(config)
db.init_app(app)
```

### 直接连接的写法  
```py
# coding:utf-8
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker

#sqlite内存：
DATABAES_URL = 'sqlite:///:memory:'
#sqlite文件: 
DATABAES_URL = 'sqlite:///./test.db'
#mysql+pymysql：
DATABAES_URL = 'mysql+pymysql://username:password@hostname:port/dbname'
#mssql+pymssql: 
DATABAES_URL ='mssql+pymssql://username:password@hostname:port/dbname'

engine = create_engine(DATABAES_URL,echo=True)
DB_Session = sessionmaker(bind=engine)
#create_engine() 会返回一个数据库引擎，echo 参数为 True 时，会显示每条执行的 SQL 语句，生产环境下可关闭。
session = DB_Session()
```

## 创建数据模型  
```py
from flask import Flask
from flask_sqlalchemy import SQLAlchemy,Column
app = Flask(__name__)
db = SQLAlchemy(app)

# 定义Person对象
class Person(db.Model):
	'''Person table'''
	# 表的名字
    __tablename__  = "person"
    __table_args__ = {
		"mysql_engine":"InnoDB",   # 表的引擎
		"mysql_charset":"utf8"   # 表的编码格式
	}
	# 表结构
	id=Column(Integer,primary_key=True,autoincrement=True)
	name = Column(String(128),primary_key=True,nullable=False)

db.create_all()

```

*** 表结构更具体的参数设置,如主键、自增、外键等属性，见另一篇文章 [ SQLAlchemy的应用 ]**
## 数据的CRUD
* 添加数据  
```py
person = Person(name = 'aaa')
db.session.add(person)
#事务提交
db.session.commit()
db.session.close()
```
* 查询数据  
```py
person = Person.query.filter(Person.name = 'aaa').first()
print person.name

```
* 修改数据  
```py
# 先把要修改的数据查找出来
person = Person.query.filter(Person.name = 'aaa').first()
person.name = 'bbb'
db.session.commit()
db.session.close()

```
* 删除数据
```py
# 先把要修改的数据查找出来
person = Person.query.filter(Person.name = 'aaa').first()
db.session.delete(person)
db.session.commit()
db.session.close()
```

> 增、删、改操作都需要提交事务` db.session.commit()` ,查询操作不需要

## 执行sql语句
使用` execute() ` 方法
```py
s=db.session()
# 不能用 `?` 的方式来传递参数 要用 `:param` 的形式来指定参数
# s.execute('INSERT INTO person (name, age, password) VALUES (?, ?, ?)',('bigpang',2,'1122121'))  
# 这样执行报错 
# s.execute('INSERT INTO person (name, age, password) VALUES (:aa, :bb, :cc)',({'aa':'bigpang2','bb':22,'cc':'998'}))
# s.commit()
# 这样执行成功
res=s.execute('select * from person where name=:aaa',{'aaa':'aaa'})
# print(res['name'])  # 错误
# print(res.name)    # 错误
# print(type(res))   # 错误
for r in res:
	print(r['name'])
	
s.close()
```

## 完整的示例代码


```py
# coding:utf-8
from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy import Column, Integer, String
from sqlalchemy.orm import sessionmaker
# ***************************
# 初始化数据库连接
DATABAES_URL = 'mysql+pymysql://username:password@hostname:port/dbname'
engine = create_engine(DATABAES_URL,echo=True)
# 创建对象的基类
Base=declarative_base()
# 创建会话类
DBSession=sessionmaker(bind=engine)
# ******************
# 定义User对象
class User(Base):
	"""Users table"""
	# 表的名字
	__tablename__='users'
	__table_args__={'sqlite_autoincrement': True}
	# 表结构
	id=Column(Integer,primary_key=True,autoincrement=True)
	name=Column(String(32),nullable=False)
	age=Column(Integer,default=0)
	password=Column(String(64),unique=True)
class Blog(Base):
	"""docstring for Blog"""
	__tablename__='blogs'
	id=Column(Integer,primary_key=True)
	title=Column(String(100))
	desc=Column(String(500))
class Tips(Base):
	"""docstring for Tips"""
	
	__tablename__='tips'
	#表结构
	id=Column(Integer,primary_key=True)
	name=Column(String(32))
# ***********************
# 添加一条数据
def newUser():
	# 创建会话对象
	session=DBSession()
	new_user=User(name='Jery',password='123')
	session.add(new_user)
	session.commit()
	session.close()
# 添加一条数据
def addUserForZhCn():
	session=DBSession()
	new_user=User(name=u'关羽2',password='12322233')
	session.add(new_user)
	session.commit()
	session.close()
# 新增多条数据
def addmoreUser():
	session=DBSession()
	session.add_all([
		User(name='guanyu',age=4,password='11111'),
		User(name='zhangfei',password='2233'),
		User(name='zhenji',password='44556')
		])
	session.commit()
	session.close()
# 查询
def queryUser():
	session=DBSession()
	quser=session.query(User).filter(User.id==4).one()
	print('name:',quser.name)
	session.close()
# 删除
def deleteUser():
	session=DBSession()
	duser=session.query(User).filter(User.id==2).delete()
	session.commit()
	session.close()
# 执行sql语句
def SQlUser():
	s=DBSession()
	# 不能用 `?` 的方式来传递参数 要用 `:param` 的形式来指定参数
	# s.execute('INSERT INTO users (name, age, password) VALUES (?, ?, ?)',('bigpang',2,'1122121'))  
	# 这样执行报错 
	
	# s.execute('INSERT INTO users (name, age, password) VALUES (:aa, :bb, :cc)',({'aa':'bigpang2','bb':22,'cc':'998'}))
	# s.commit()
	# 这样执行成功
	res=s.execute('select * from users where age=:aaa',{'aaa':4})
	# print(res['name'])  # 错误
	# print(res.name)    # 错误
	# print(type(res))   # 错误
	for r in res:
		print(r['name'])
	s.close()
# 执行sql语句
def SQlUser2():
	# **传统 connection方式**
	# 创建一个connection对象，使用方法与调用python自带的sqlite使用方式类似
	# 使用with 来创建 conn，不需要显示执行关闭连接
	# with engine.connect() as conn:
	# 	res=conn.execute('select * from users')
	# 	data=res.fetchone()
	# 	print('user is %s' %data[1])
	# 与python自带的sqlite不同，这里不需要 cursor 光标，执行sql语句不需要commit。如果是增删改，则直接生效，也不需要commit.
	
	# **传统 connection 事务**
	with engine.connect() as conn:
		trans=conn.begin()
		try:
			r1=conn.execute("select * from users")
			print(r1.fetchone()[1])
			r2=conn.execute("insert into users (name,age,password) values (?,?,?)",('tang',5,'133444'))
			trans.commit()
		except:
			trans.rollback()
			raise
	# **session**
	session=DBSession()
	session.execute('select * from users')
	session.execute('insert into users (name,age,password) values (:name,:age,:password)',{"name":'dayuzhishui','age':6,'password':'887'})
	# 注意参数使用dict，并在sql语句中使用:key占位
	# 如果是增删改，需要 commit
	session.commit()
	# 用完记得关闭，也可以用 with
	session.close()
# 更多操作
def TestUser():
	session=DBSession()
	# test1
	# 使用merge方法，如果存在则修改，如果不存在则插入（只判断主键，不判断unique列）
	# t1=session.query(User).filter(User.name=='zhenji').first()
	# t1.age=34
	# session.merge(t1)
	# session.commit()
	# test2
	# merge方法，如果数据库中没有则添加
	# t2=User()
	# t2.name='haha'
	# session.merge(t2)
	# session.commit()
	# test3
	# 获取第2-3项
	# tUser=session.query(User)[1:3]   
	# for u in tUser:
	# 	print(u.id)
	# test4
	# 
if __name__ == '__main__':
	
	# 删除全部数据库
	# Base.metadata.drop_all(engine)
	
	# 初始化数据库
	# Base.metadata.create_all(engine)
	# 删除全部数据库
	# Base.metadata.drop_all(engine)
	# 删除指定的数据库
	# 如删除 Blogs表
	# 详见 ：http://stackoverflow.com/questions/35918605/how-to-delete-a-table-in-sqlalchemy
	# Blog.__table__.drop(engine)
	
	# 新增数据
	# newUser()
	# 新增多条数据
	# addmoreUser()
	# 新增数据含中文
	# addUserForZhCn()
	# 查询数据
	# queryUser()
	
	# 删除
	# deleteUser()
	# 测试
	# TestUser()
	 
	# 执行sql语句
	# SQlUser()
	
	# 执行sql语句2
	SQlUser2()
	print('ok')
```
