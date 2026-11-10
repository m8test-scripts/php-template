# PHP 模板脚本项目 i18n

Status: resolved
Triage: ready-for-agent

## Problem Statement

PHP 模板入口的计数标签和按钮只有固定中文文案，也没有标出界面来自 PHP 模板。项目没有英文/中文字符串目录范例，复制模板的开发者无法直接使用宿主 locale 驱动的脚本 i18n。

## Solution

为 PHP 模板添加英文和中文 BCP 47 字符串目录；入口 Compose UI 通过 `$stringResources` 本地化计数标签、按钮和按钮下方的语言标识。catalog 同时提供本地化项目描述，中文 catalog 覆盖项目名称。README 说明资源目录、PHP 闭包捕获和宿主 locale 行为。

## Implementation Decisions

- 本次范围只包括 PHP 模板仓库；宿主的 locale 匹配与 fallback 逻辑不变。
- 字符串目录位于 `php/res/strings/`，文件名使用 `en.json` 和 `zh.json`。
- PHP 脚本通过 `$stringResources` 读取 StringResources；在 Compose 回调闭包中通过 `use` 显式捕获。
- 英文 catalog 提供描述和 UI 文案，但不覆盖项目名称；中文 catalog 提供中文项目名称、描述和 UI 文案。
- 语言专名保持 `PHP`；完整语言标识文案作为静态字符串翻译。
- 在 192.168.31.207 上用 `rerunPhp` 验证英文和中文界面、按钮下方的语言标识及点击计数。
- 不运行静态检查。

## Out of Scope

- 修改宿主、Gradle 插件、PHP Extension 或其他模板仓库。
- 新增 locale 选择、格式化 API、插值机制或额外语言目录。
