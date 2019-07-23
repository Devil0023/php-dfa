# guda/phpdfa

一个基于DFA实现的关键词检测包。


## 安装
```
composer require guda/php-dfa
```

## 使用

### 初始化词库文件

```
$path = __DIR__;
$raw = $path.'/storage/logs/raw';  // 词库目录
$map = $path.'/storage/logs/map';  // 映射文件目录

(new InitWordsMap($raw, $map))->initMap();

```

### 检测文本

```
$path = __DIR__;
$raw = $path.'/storage/logs/raw';  // 词库目录
$map = $path.'/storage/logs/map';  // 映射文件目录

$obj = (new CheckWords($raw, $map));
$obj->setMap('illegal');           // 词库名称（取自词库文件名）
var_dump($obj->check('我是测试字段'));

```
