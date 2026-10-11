Label: ready-for-agent
Type: feature
Status: resolved

# Gradle Template Maven Consumer 规格

## Problem Statement

脚本模板各语言分支仍通过旧 Extension ID、WebResource URL/version/property 或本地 artifact cache 定位语言和组件 Extension。不同语言分支因此携带多套资源配置，不能统一消费 Maven publication，也不能清楚区分普通脚本 classpath library 与需安装的 Runtime Extension。

## Solution

把所有受支持语言分支迁移到 Maven GAV consumer model：语言 Extension 由 GAV 与 language entry ID 选择，普通脚本 classpath 库使用独立的 GAV 声明，Component 与 Build Plugin 使用 Extension GAV。保留各分支既有运行/build/rerun 工作流，不改变脚本 UI 或脚本逻辑，只更新消费配置与必要的 Test Control fixtures。

## User Stories

1. 作为脚本开发者，我希望所有语言模板都以 Maven GAV 定位 Language Extension，以便语言分支使用统一制品来源。
2. 作为脚本开发者，我希望 Component 与 Build Plugin Extension 均能以 Maven GAV 声明，以便不再手动维护 APK URL/property。
3. 作为脚本开发者，我希望普通 classpath JAR 与 Extension dependency 使用不同声明方式，以便普通库不会被误安装为 Extension。
4. 作为 Java/Janino 或 JavaScript/Rhino 开发者，我希望脚本 Builder API 使用显式 GAV 参数，以便不需要实现 Kotlin Function1 callback。
5. 作为模板维护者，我希望分支的 build/rerun 和 Test Control 使用当前合同，以便设备测试覆盖真实消费路径。

## Implementation Decisions

- 范围包含模板中现有的 Java、JavaScript、TypeScript、Groovy、Lua、PHP、Python、Ruby 等受支持分支；不因为本轮代表性设备测试选 Java/JavaScript 而省略其他分支的配置迁移。
- Language Extension 配置必须包含 Maven groupId、artifactId、version selector 和 language entry ID。模板不再写入 Extension ID 或 URL/property 作为 artifact locator。
- 普通 Maven classpath libraries、Component Extension 与 Build Plugin Extension 分属不同依赖类别；普通 library 只加入脚本 classpath，Extension 类别由宿主安装/激活并使用 manifest entry mapping。
- 模板调用宿主跨语言 BuildScriptConfig/ProjectDescriptor API 时使用 `compileOnly(groupId, artifactId, version)`、`requireComponent(groupId, artifactId, version)` 与 `requirePlugin(groupId, artifactId, version)`；Gradle configuration DSL 遵循 m8test-gradle-plugin 自身 contract。
- 不改变各语言脚本源代码、UI、变量名、入口布局或既有 rerun 任务语义，除迁移旧 artifact metadata 所必需的构建配置外不做脚本重写。
- Maven 版本选择、仓库与 POM transitives 遵循 Gradle/Maven consumer behavior，不在模板内实现下载、缓存、URL 解析或版本比较。

## Testing Decisions

- 每个受支持语言模板至少验证配置可由对应 Gradle consumer 插件解析，且现有构建/运行任务仍可到达 Development Kit；不得只检查 README 或字符串替换。
- Java/Janino 与 JavaScript/Rhino 通过现有 Test Control 在设备上验证同一公开行为：普通 classpath library 可被脚本调用、Component/Build Plugin 按 manifest entry 解析，解析错误可观察且不会误安装普通 JAR。
- 其余语言复用现有 `rerun<Language>`/build 设备路径作配置消费验证；不迁移或新增无关的 Compose/WebView UI 测试。
- 复用每个语言模板现有 Gradle tasks、Test Control 文件测试与 m8test-gradle-plugin TestKit consumer fixture；测试串行运行，不并发启动 Gradle。

## Out of Scope

- Development Kit Maven Resolver、Extension Manager Service、仓库 UI/凭据存储和 APK 安装策略。
- m8test-gradle-plugin publication/configuration 实现；该仓库拥有自己的 spec、ADR 与 TestKit。
- 改写任一脚本语言的业务代码、UI 或未受 Maven artifact 定位影响的测试。
- 最终用户跨仓库开发手册；实现和验收完成后归 m8test-docs。
- 保留旧 Extension ID/WebResource artifact 配置或双格式兼容；本项目未发布，直接采用当前合同。

## Further Notes

- 模板是 Maven consumer，不应维护 Extension 制品索引或下载服务器。
- 本仓库的 README 属于语言模板使用指南；实现迁移时只更新与实际变化直接相关的模板配置说明，最终完整工作流指南仍集中到 m8test-docs。
