# 01: 本地化 PHP 脚本模板

**What to build:** 为 PHP 模板增加英文和中文字符串目录，并让项目元数据与示例界面跟随 Development Kit 语言显示。

**Blocked by:** None

**Status:** resolved
**Type:** task

- [x] 增加英文与中文 BCP 47 字符串目录，提供项目描述和界面文案。
- [x] 入口 PHP 脚本通过 `$stringResources` 获取计数标签、按钮文本和语言标识。
- [x] 在按钮下方显示本地化的 `PHP` 语言名称。
- [x] 在闭包中正确捕获字符串资源对象。
- [x] 中文目录覆盖项目名称，英文目录保留 `appName` 配置回退值。
- [x] README 说明字符串目录和闭包中的 API 用法。
- [x] 使用 `rerunPhp` 在指定设备验证英文、中文 UI 和按钮交互。
