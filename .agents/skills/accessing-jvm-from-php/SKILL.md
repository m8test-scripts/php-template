---
name: accessing-jvm-from-php
description: 从 M8Test Quercus PHP 脚本访问 JVM 类型、调用成员、传递 callable 或使用宿主 wrappers 时使用。
---

# 从 PHP 访问 JVM

此模板使用 Quercus 与生成的 Java 类映射。Java 类通过 `m8test_java` 命名空间及 PHP `use` 语法引用；实例方法用 `->`，静态成员用 `::`。先确认依赖可由当前脚本 classloader 加载。公共 wrapper 语义见 `using-host-values-and-wrappers`。

```php
use m8test_java\java\lang\StringBuilder;
$builder = new StringBuilder("M8Test");
$builder->append(" PHP");
if ($builder->toString() !== "M8Test PHP") throw new RuntimeException();

$raw = $arrays->intArrayOf(3, 1, 2);
$values = $objectWrappers->wrapIntArray($raw);
$values->set(0, 7);
if ($values->getSize() !== 3 || !$values->contains(7)) throw new RuntimeException();
```

PHP scalar、array、null 和 callable 不自动等同 JVM primitive、容器、nullable 或 SAM。根据目标签名使用宿主工厂并在 PHP/Quercus runtime 验证 callback 与类型转换；不要套用其他语言规则。
