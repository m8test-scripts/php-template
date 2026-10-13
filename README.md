# [M8Test](https://dev-docs.m8test.com) Php Gradle 模板

> 如有疑问，请加入我们的官方 QQ 群：[749248182](https://qm.qq.com/q/jVVADJCAI8) 或 QQ
> 频道：[m8testofficial](https://pd.qq.com/s/8pidhywcy)

---

## 📦 Gradle 任务说明

运行 `./gradlew -q tasks --all` 查看本项目注册的开发任务。

### 任务分组

1. **`m8test-build`**  
   M8Test构建相关任务组，包含`buildPhp`（构建Php项目）、`runPhp`（运行项目）等任务。

2. **`m8test-code-completion`**  
   M8Test代码补全相关任务组，包含`generatePhpAllCodeCompletionFiles` （生成所有需要的代码补全文件）,
   `generatePhpGlobalVariables`（生成全局变量代码补全文件）等任务。

3. **`m8test-development-environment`**
   M8Test开发环境相关任务组，包含`installPhpDevelopmentEnvironment`（安装 PHP 开发环境）、`installPhpExtension`（安装 PHP Language Extension）等任务。

4. **`m8test-download`**  
   M8Test资源下载相关任务组，包含`downloadPhpCodeTemplate`（下载示例代码模板）、`downloadPhpDocs`（下载文档）等任务。

> 初次准备使用 `installPhpDevelopmentEnvironment`；日常迭代优先使用 `runPhp`。

### 常用任务

| 任务名称                                                 | 任务分组                     | 功能描述                                                                                     |
|------------------------------------------------------|--------------------------|------------------------------------------------------------------------------------------|
| `generatePhpAllCodeCompletionFiles`                  | `m8test-code-completion` | 生成所有Php代码补全文件，会执行所有的 `generatePhpCodeXXXCodeCompletionFiles` 任务, 编写脚本之前执行一次此任务即可有代码提示功能。 |
| `generatePhpGlobalVariables`                         | `m8test-code-completion` | 生成Php全局变量代码补全文件，提供IDE代码提示功能。需连接安卓设备，建议编写代码前执行一次，依赖更新后需重新执行。                              |
| `generatePhpNormalAndroidCodeCompletionFiles`        | `m8test-code-completion` | 生成 Android API 的 Php 代码提示文件。成功后，Php 代码中将有 Android API 的代码提示。                             |
| `generatePhpNormalDevelopmentKitCodeCompletionFiles` | `m8test-code-completion` | 生成 M8Test API 的 Php 代码提示文件。成功后，Php 代码中将有 M8Test API 的代码提示。                               |
| `generatePhpComponentXXXCodeCompletionFiles`         | `m8test-code-completion` | 生成 XXX 组件 API 的 Php 代码提示文件。成功后，Php 代码中将有 XXX 组件 API 的代码提示。                               |
| `buildPhp`                                           | `m8test-build`           | 构建M8Test Php脚本项目源码（不执行），构建结果位于`build/project`目录。                                         |
| `runPhp`                                             | `m8test-build`           | 构建并将项目推送到已连接的安卓设备上运行。                                                                    |
| `buildPhpApk`                                        | `m8test-build`           | 将M8Test Php脚本项目打包成APK文件。                                                                 |
| `installPhpDevelopmentEnvironment`                  | `m8test-development-environment` | 准备脚本开发所需的 Runtime、Language Extension、文档和模板资源。 |
| `checkPhpHostTool`                                    | `m8test-development-environment` | 检查本机 PHP 工具是否可用。 |
| `downloadPhpExtension`                                | `m8test-download`        | 下载 PHP Language Extension APK。 |
| `installPhpExtension`                                 | `m8test-development-environment` | 将 PHP Language Extension 安装到 Development Kit。 |
| `installPhpComponents`                                | `m8test-development-environment` | 安装项目配置中声明的组件 Extension。 |
| `verifyScriptProject`                                 | `m8test-development-environment` | 执行当前脚本项目测试脚本。 |
| `verifyPhpTestControl`                                | `m8test-development-environment` | 运行 Test Control smoke 集。 |

---

## 🚀 执行任务的方法

### 方法一：使用快捷搜索

1. 双击 `Ctrl` 键，打开搜索框。
2. 输入 `gradle 任务名`，例如 `gradle -q runPhp`，然后按回车执行。

   ![方法一示例](images/1.png)

### 方法二：通过 Gradle 面板

1. 点击 Gradle 图标。
2. 依次展开 `php` > `Tasks` > `m8test`。
3. 双击需要执行的任务。

   ![方法二示例](images/2.png)

### 方法三：使用终端命令

1. 按下快捷键 `Alt + F12` 打开终端。
2. 输入命令 `./gradlew -q 任务名`，例如 `./gradlew -q runPhp`，然后按回车执行。

   ![方法三示例](images/3.png)

---

## 宿主或 Gradle 插件更新时联调

- Gradle 插件有改动时，先在 `Gradle 插件` 仓库执行 `./gradlew -q publishAllToMavenLocal`，再确认本项目使用对应的 `m8testVersion`。
- 宿主有未发布改动时，先构建 Development Kit APK，并在本机将 `DEVKIT_APK` 设为相对于本 PHP 项目根目录的 APK 路径。
- 运行脚本时通过 `-Pm8testDevelopmentKitApk="$DEVKIT_APK"` 使用该 APK；不需要把宿主文件路径写入此模板。

```shell
./gradlew -q runPhp -Pm8testDevelopmentKitApk="$DEVKIT_APK"
```

## ⚙️ 配置脚本项目

脚本项目的配置位于 `php/build.gradle.kts` 文件中。您可以根据需要修改设备的 IP、ADB 端口等参数。该文件中包含详细的代码注释，供参考。

---

## 🛠️ 项目开发流程

1. **生成代码提示文件**  
   执行 `generatePhpAllCodeCompletionFiles` 任务，生成 Php 代码补全文件，提供代码提示功能。

2. **连接日志服务**

   按下快捷键 `Alt + T`，依次选择 `M8Test` > `连接日志服务`。

3. **编写并运行脚本**

   编写代码并保存后，执行 `runPhp` 任务，即可在安卓设备上运行脚本项目。确保安卓设备已开启 ADB 调试。运行日志可在
   M8Test 日志面板中查看。

   `runPhp` 会先生成并推送 `build/project`（BuildProjectSource），等待 BuildScript 成功后，再启动独立的 PHP ProjectScript。模板使用标准 PHP Extension ID `com.m8test.extension.language.php` 和 capability ID `com.m8test.capability.language.php`。

4. **BuildProjectSource 与 ProjectScript**

   PHP 的 `settings.php`、`init.build.php` 和 `build.php` 属于 BuildScript 阶段。BuildScript 成功后生成最终 SPA，再由独立 ProjectScript 执行 `init`、入口和 side。每次 `rerunPhp` 都会创建新的 Quercus Engine、Env 和 Script 生命周期。

5. **打包Apk**
   所有的脚本开发工作都完整后, 如果你需要打包成独立的apk可以执行 `buildPhpApk` 任务。
