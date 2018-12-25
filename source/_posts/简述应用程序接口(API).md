---
title: 简述应用程序接口(API)
date: 2017-05-20 20:07:31
tags:
  - php 
desc: 简述应用程序接口(API)
keywords: PHP API 接口 
categories:
- PHP

---
写下这个文章的原因是,我发现身边一些的朋友可能是因为之前没有接触过API,以至于对API有些不解和抵触.
因为微信开发/地图定位/各种大数据的信息查询,让接口(api)成为了一个非常常用的工具.这篇文章是我自己的一些理解,由于本人也是个小白,肯定有很多地方不到位,希望能指出.首先,我们从API是什么开始说.
## 1.API是什么?
>**维基百科的解释是:**
>应用程序接口 (**A**pplication **P**rogramming **I**nterface 简称:API)为：“‘电脑[操作系统](https://zh.wikipedia.org/wiki/%E6%93%8D%E4%BD%9C%E7%B3%BB%E7%BB%9F)（Operating system）’或‘[程序库](https://zh.wikipedia.org/wiki/%E5%87%BD%E5%BC%8F%E5%BA%AB)’提供给应用程序调用使用的代码”。其主要目的是让应用程序开发人员得以调用一组[例程](https://zh.wikipedia.org/wiki/%E5%87%BD%E6%95%B0_(%E8%AE%A1%E7%AE%97%E6%9C%BA%E7%A7%91%E5%AD%A6))功能，而无须考虑其底层的源代码为何、或理解其内部工作机制的细节。API本身是[抽象](https://zh.wikipedia.org/w/index.php?title=%E6%8A%BD%E8%B1%A1_(%E8%A8%88%E7%AE%97%E6%A9%9F%E7%A7%91%E5%AD%B8)&action=edit&redlink=1)的，它仅定义了一个[接口](https://zh.wikipedia.org/wiki/%E4%BB%8B%E9%9D%A2_(%E9%9B%BB%E8%85%A6%E7%A7%91%E5%AD%B8))，而不涉及应用程序在实际实现过程中的具体操作。

## 2.简单举例
简而言之接口就是一个抽象的,不需要考虑内部细节的东西.你拿来用就可以了.这么讲可能抽象了一些,我们举个例子:
以百度地图IP定位的API为例:
**服务地址:**
``http://api.map.baidu.com/location/ip``

**接口参数:**
![百度地图接口参数](http://upload-images.jianshu.io/upload_images/2229907-54cc1480dbcdce86.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

**返回结果:**

```
{
   address: "CN|北京|北京|None|CHINANET|1|None", #地址 
   content: #详细内容 
       { address: "北京市", #简要地址 
         address_detail: #详细地址信息 
            { city: "北京市", #城市 
            city_code: 131, #百度城市代码 
            district: "", #区县 
            province: "北京市", #省份 
            street: "", #街道 
            street_number: "" #门址 
            }, point: #百度经纬度坐标值 
            { x: "116.39564504", y: "39.92998578" 
            }
       },
     status: 0 #返回状态码 
}
```
<u>那么,服务地址/接口参数/返回结果分别是什么意思呢?</u>
我们一一来理解:
**服务地址:** 即我们需要数据请求的页面地址.

**请求参数** 有的时候我们不只从接口上取值,我们还需要用一种特殊的方式告诉服务器,我们需要什么数据,你给我们需要的就可以了,别瞎给.这种方式通常是URL传参的形式.比如百度的这个api就可以这样传.
``http://api.map.baidu.com/location/ip?ak=E4805d16520de693a3fe707cdc962045&ip=202.198.16.3&coor=bd09ll``
通过url,我们告诉百度,我们的ak(access key)是:E48....62045,我们要定位的ip是:202.198.16.3,coor是:bd09ll(告诉百度我们需要经纬坐标).
这就是一中最最常用的使用API的方式.

**返回结果** 上面我们通过URL传参的方式告诉了百度服务器,我们需要的是IP为202.198.16.3的位置信息,而且需要经纬坐标值(coor=bd09ll),并且我们的Ak值是对的,这时候,百度就会输出一个结果在页面里,通常是JSON字符串的形式.如:
```
{ 
  address: "CN|吉林|长春|None|CERNET|1|None", 
  content: { 
    address: "吉林省长春市", 
    address_detail: { 
      city: "长春市", 
      city_code: 53, 
      district: "", 
      province: "吉林省", 
      street: "", 
      street_number: "" 
    }, 
    point: { 
      x: "125.31364243", y: "43.89833761" 
    } 
  }, status: 0 
}
```
我们可以通过读取url页面的形式来获取返回的json字符串.再应用到我们的项目中.
***上述就是一个最简单的API使用,也是最本质/常用的一种.(传值和取值)***

---
从上面的例子中,我们就可以知道,为什么API不需要考虑程序的内部细节了吧?其实它就好像一个封装好的电池,我们放到卡槽里用就行了,不需要去了解内部结构.

## 3.写一个简单的API
对于大型的API内部封装的算法是非常复杂的.但它的原理并不难,我们自己就可以尝试着写上一个小小的API.这里我给个例子:
```php
<?php
#假设存为index.php到根目录
header ('content-type:text/html;charset=utf-8');
$con = mysql_connect("localhost","root","root");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db('chaxun',$con);
	$token=isset($_GET['token'])?$_GET['token']:"1";
if ($token==123) {
	$sql="SELECT * FROM data;";
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
    $rst[]=$row;
	}
	$rst_json = json_encode($rst);
	echo $rst_json;
}else{
	echo "token错误!";
}
mysql_close();
?>
```
上面的例子,
我们API服务地址就是:``http://localhost/index.php``
接口参数我们需要传一个``token=123``
返回结果就是一个查询数据库的结果,转换的json字符串.
完整的url拼起来就是:http://localhost/index.php?token=123
看吧,其实写个接口就这么简单.
好困,睡了,明天再来补完整.