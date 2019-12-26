---
title: MySQL触发器基本的使用
date: 2018-11-20 10:54:24
tags:
  - MySQL
desc: MySQL触发器基本的使用
keywords: mysql 触发器 拓展
categories:
- 数据库

---

## 触发器
触发器是一种特殊的`存储过程`，是嵌入到mysql的一段程序，它在插入，删除或修改特定表中的数据时触发执行。


## 一、基本语法

### 1. 创建触发器：
```mysql
CREATE TRIGGER /*触发器名称*/
AFTER / BEFORE /*(触发器工作的时机)*/ 
UPDATE / DELETE / INSERT /*(触发器监听事件)*/ 
ON /*表名(触发器监听的目标表)*/ 
FOR EACH ROW /*(行级监视，mysql固定写法，oracle不同)*/
BEGIN
	/*sql语句集........（触发器执行动作，分号结尾）*/
END;
```
<!--more-->

### 2. 删除触发器：
```sql
DROP TRIGGER IF exist `trigger_name`;
```

### 3. 查询数据库触发器：
```sql
SHOW triggers;
```

### 4.触发器声明变量:

一个变量名可以由当前字符集的数字字母字符和“`_`”、“`$`”和“`.`”组成;

#### 局部变量
MySQL 中使用 ` DECLARE` 来定义一局部变量，该变量只能在 ` BEGIN … END` 复合语句中使用，并且应该定义在复合语句的开头，即其它语句之前。  

语法是：
```sql
DECLARE var_name[...]type[DEFAULT value]

```
其中，  

* ` var_name` 为变量名称，同sql语句一样，变量名不区分大小写;   
* ` type` 为mysql支持的任何数据类型;   
* ` DEFAULT` 子句提供默认值，值可以是一个表达式 (如果需要可以使用)。   

> 注: 可以同时定义多个同类型的变量，用逗号隔开，变量初始值为NULL；  

例如：
```sql
DECLARE a INT;

DECLARE b INT DEFAULT 0;
```

#### 用户变量
用户变量：相当与全局变量。 只在一个数据库中有效  
在客户端连接到数据库实例整个过程中用户变量都是有效的   
mysql中用户变量**不需要事先声明**，在用的时候直接用` @变量名` 使用就可以
```sql
/*set语句可用于向系统变量或用户变量赋值*/
SET @num = 1;
SET @num := 1;

/*也可使用select语句来定义*/
SELECT @num := 1;
SELECT @num := field_name FROM table_name WHERE 1 = 1;
```
> 注：SELECT只能用` :=` 定义 


### 5.变量的赋值
mysql触发器内，对变量赋值采用 SET 语句
语法是：
```sql
SET var_name = expr [,var_name = expr] ...
```
使用举例
```sql
DECLARE c INT; 
SET c = ( SELECT stuCount FROM class WHERE classID = new.classID );
```


> ###### Tips:   
>**行变量：**当目标表发生改变时候，变化的行可用行变量表示  
>` new` :代表目标表目标行发生改变之后的行   
>` old` :代表目标表目标行发生改变之前的行  

### 6.逻辑判断语句
```sql  
IF /*condition1*/ THEN
　　　/*do something;*/
　ELSEIF /*condition2*/ THEN
　/*do something;*/
END IF;
```

## 二、示例
### 1.触发器监听：insert
```sql
CREATE TRIGGER `trigger_name` 
AFTER INSERT 
ON `table` 
FOR EACH ROW
BEGIN
	/* 要做的SQL操作，如：
	UPDATE table1 SET field = 'abc' WHERE id = new.id;
	new 是行变量。
	*/
END;
```

### 2.触发器监听：delete
```sql
CREATE TRIGGER `trigger_name2` 
AFTER DELETE 
ON `table` 
FOR EACH ROW
BEGIN
	/* 要做的SQL操作，如：
	UPDATE table1 SET field = 'abc' WHERE id = old.id;
	old 是行变量。
	*/
END;
```

### 3.触发器监听：update
```sql
CREATE TRIGGER `trigger_name3` 
AFTER UPDATE 
ON `table` 
FOR EACH ROW
BEGIN
	/* 要做的SQL操作，如：
	UPDATE table1 SET field = 'abc' WHERE id = new.id;
	old 是行变量。
	*/
END;
```

### 4.触发器SET和IF语句的综合使用
```sql
CREATE TRIGGER `trigger_name4` 
AFTER UPDATE 
ON `table` 
FOR EACH ROW
BEGIN
	DECLARE c INT;
	SET c = 1;
	IF c < 1 THEN
		SET c = 2;
	ELSEIF c > 1 THEN
	　SET c = 4;
	END IF;
	/* 要做的SQL操作，如：
	UPDATE table1 SET field = 'abc' WHERE id = new.id;
	old 是行变量。
	*/
END;
```

> 注意：  
**①**：for each row:必须填写，保证mysql支持行级控制，oracle同时支持行级控制和语句级控制。  
**②**：如果在BEFORE或AFTER触发程序的执行过程中出现错误，将导致调用触发程序的整个语句的失败。对于事务性表，如果触发程序失败（以及由此导致的整个语句的失败），该语句所执行的所有更改将回滚。对于非事务性表，不能执行这类回滚，因而，即使语句失败，失败之前所作的任何更改依然有效。  