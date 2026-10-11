# 01 — 迁移各语言模板的 Maven 消费配置

**What to build:** 脚本项目在所有受支持语言模板中都能通过 Maven 坐标选择语言、普通 classpath 库和 Runtime Extension，同时保留原有 build/rerun 流程。

**Blocked by:** `m8test-contracts 01 — 迁移 Maven 坐标协议`、`m8test-gradle-plugin 02 — 通过 Maven 坐标声明 Extension 编译依赖`、`m8test-android 03 — 通过 Maven Resolver 安装和更新 Runtime Extension`。

**Status:** ready-for-agent

- [ ] 所有受支持语言分支使用 Language Extension GAV 与 language entry ID，不再通过旧 Extension ID、WebResource URL/property 或本地 APK/JAR cache 定位制品。
- [ ] 模板区分普通 Maven classpath library 与 Component/Build Plugin Extension；前者不触发 Extension 安装或激活。
- [ ] 保留各语言既有脚本源码、UI、变量和 rerun/build 语义；只迁移 Maven 消费配置及必要的 Test Control fixture。
- [ ] 每个分支验证新 consumer 配置可解析，并且现有构建/运行路径仍可到达 Development Kit。
- [ ] Java/Janino 与 JavaScript/Rhino 通过设备 Test Control 验证普通库可调用、Extension entry 映射正确及解析失败可观察；其他语言复用其既有 rerun/build 验证路径。
