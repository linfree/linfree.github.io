---
title: SQLAlchemy的属性介绍
date: 2018-09-26 21:20:54
tags:
  - python
desc: SQLAlchemy的属性介绍
keywords: python SQLAlchemy 
categories:
- python

---



## SQLAlchemy的应用



### 行数据类型

|类型|说明|
| --- 			| ---- 	|
|Integer		|	整数	|
|String (size)  |	有最大长度的字符串	|
|Text			|长 unicode 文本	|
|Date			|表示为日期	|
|DateTime		|表示为 datetime 对象 的时间和日期	|
|Float			|存储浮点值	|
|Boolean		|存储布尔值	|
|PickleType		|存储一个持久化 Python 对象	|
|LargeBinary 	|	存储任意大的二进制数据	|

<!--more-->

### 其他行属性
` primary_key=True` 是否是主键  
` db.ForeignKey('person.id')` 表示设置XX表名.XX字段名外键    
` nullable=False ` 是否能为空   
` unique=True` 是否能重复  
` autoincrement=True` 是否自增长 
` default=0 ` 默认值 
` index=True ` 索引
` name ` 名称
` type_ ` 列类型
### 关系链接  
一对多的关系：  

如果要表示一对一的关系，在定义` relationship ` 的时候设置` uselist` 为` False ` （默认为` True ` ）

` db.relationship('Teams') ` 定义一个关系
` backref=db.backref('users')` 反向引用
` primaryjoin='Persion.like_id==Book.id'` 多个外键的情况

多对多的关系有中间表：

` secondary = 中间表模型,` 
 
``` 
    tags = db.Table('tags',
        db.Column('tag_id', db.Integer, db.ForeignKey('tag.id')),
        db.Column('page_id', db.Integer, db.ForeignKey('page.id'))
    )
    
    class Page(db.Model):
        id = db.Column(db.Integer, primary_key=True)
        tags = db.relationship('Tag', secondary=tags,
            backref=db.backref('pages', lazy='dynamic'))
    
    class Tag(db.Model):
        id = db.Column(db.Integer, primary_key=True) 
```
` secondary=association_table,
        back_populates="children"
`   

循环一对多关系  
还是我自己写的Persion和Book关系，一个人可能写过多本书，一本Book只有一个Persion写，N个人最喜欢1个书，每个人只能有一个最喜欢的这个例子可能不大恰当，  
但是就是两个单向的一对多关系，是不能用多对多关系的，  
下面是我给出的例子
```py 
class Persion(Base):
    __tablename__ = 'persion'
    id = Column(Integer, autoincrement=True, primary_key=True)
    name = Column(String(1024))
    like_id = Column(Integer, ForeignKey('book.id'))
    books = relationship('Book', backref='auther', lazy="dynamic",
                         primaryjoin='Book.auther_id==Persion.id')
class Book(Base):
    __tablename__ = 'book'
    id = Column(Integer, autoincrement=True, primary_key=True)
    likes = relationship('Persion', backref='like', lazy="dynamic",
                         primaryjoin='Persion.like_id==Book.id')
    name = Column(String(1024))
    auther_id = Column(Integer, ForeignKey('persion.id'))

```
主要是添加` primaryjoin` 属性，说明关联的字段
在使用sqlalchemy的时候有很多属性，类似` lazy` ，` backref` ，` primaryjoin` 这样的属性，备选项很多，需要多多查询官方文档。只有使用过过才会比较熟悉

### 表的引擎和编码
```py
Table('mytable', metadata,
      Column('data', String(32)),
      mysql_engine='InnoDB',
      mysql_charset='utf8',
      mysql_key_block_size="1024"
     )
``` 

或
```py
class User(Base):
    """Users table"""
    # 表的名字
    __tablename__='users'
    __table_args__={'sqlite_autoincrement': True,'mysql_engine': 'InnoDB','mysql_charset': 'utf8'}

    # 表结构
    id=Column(Integer,primary_key=True,autoincrement=True)
```
