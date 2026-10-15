---
name: accessing-jvm-from-php
description: 当 PHP 脚本需要访问 JVM 类、调用 Java/Kotlin 成员或传递函数 callback 时使用。
---

# 从 PHP 访问 JVM

PHP 在 Quercus 中执行。Java 类使用 m8test_java 命名空间的 use 形式，Gradle 插件会将其转换为 importJavaClass；实例方法用 ->，静态成员用 ::。

## 类与对象

```php
use m8test_java\java\lang\StringBuilder;
$builder = new StringBuilder("M8Test");
$builder->append(" PHP");
if ($builder->toString() !== "M8Test PHP") throw new RuntimeException("StringBuilder");
```

先确认依赖已加载到当前脚本 classloader。声明文件或代码补全存在不等于运行时类可见。方法重载、静态字段、嵌套类、异常等边界按目标语言的生成代码和实际设备行为验证。

## 函数 callback

PHP function callable 可传入函数式参数；通过 use 捕获外部变量。PHP 闭包到 JVM SAM/Kotlin 函数类型的参数与返回映射需按实际目标签名验证。

## 函数 callback 示例

PHP closure 可以按目标 Kotlin 函数类型传入 callback。模板 Test Control 通过 Logger.withContext 实际验证 callback 被调用并获得参数：

```php
$called = false;
$logger->withContext(function ($context) use (&$called) { $called = $context !== null; });
if (!$called) throw new RuntimeException("callback");
```

## 类型转换边界

PHP scalar、array、null 与 JVM primitive、boxed、数组和集合有各自转换边界。目标要求 JVM 容器时使用宿主工厂，不能因 PHP array 可迭代就假设类型兼容。

逐项记录已测试的 primitive/boxed、字符串、nullable、数组、集合、varargs、重载和异常行为。未经执行验证的转换写为待验证，不根据其他脚本语言推断。

## 验证

本模板的 php-template-jvm-interop Test Control 场景会实际调用 JVM StringBuilder，并验证宿主数组工厂和 wrapper。运行当前项目支持的选中 Test Control 任务并检查 summary 中该 requestId 为 passed；若当前模板没有注册相应任务，使用模板设备运行任务并确认成功日志。Gradle 使用 -q。
