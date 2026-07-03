# 后端 Onboarding 项目：包裹管理 API

> 技术栈：**Lumen (PHP) + MySQL + Docker**
> 你将亲手做出一个能跑的 RESTful CRUD 接口，并理解它每一层为什么这样设计。
>
> 阅读方式：**每一节先读"概念"和"问题"，自己想一遍，再动手做"任务"。** 不要跳过思考直接抄代码——抄代码你学不到东西。

---

## 这个项目你最终会做出什么

一个管理「包裹」的后端服务，对外提供 5 个接口：

| 动作 | 方法 | URL |
|------|------|-----|
| 查所有包裹 | GET | `/parcels` |
| 查单个包裹 | GET | `/parcels/{id}` |
| 新建包裹 | POST | `/parcels` |
| 修改包裹 | PUT | `/parcels/{id}` |
| 删除包裹 | DELETE | `/parcels/{id}` |

整个系统的样子：

```
[ 客户端 ]  →  HTTP 请求  →  [ Lumen 应用 ]  →  SQL  →  [ MySQL ]
 Postman                      (你的代码)               (数据)
            ←  JSON 响应  ←                  ←  数据  ←
        ┕━━━━━━━━━━━ 全部跑在 Docker 容器里 ━━━━━━━━━━━┙
```

---

## 阶段 0 · 先建立心智模型

### 概念
- **客户端 / 服务器**：点外卖时，你是 client，餐厅是 server，菜单+服务员是 API（你能点什么、怎么点、餐厅怎么回你，都写在这份"约定"里）。
- **API**：一份"我能向你请求什么、你会怎么回我"的约定。
- **我们传的不是界面，是数据**：手机 App 从服务器拿到的是 JSON 数据，界面是手机本地画出来的。

### 想一想
> 你刷抖音时，手机和抖音服务器之间传的到底是什么？是视频画面本身，还是别的？

（答案在你脑子里有个轮廓就行，往下走会越来越清楚。）

### 任务
1. **装好全部工具** —— 详细的手把手安装步骤见下面的「阶段 0 附录 · 环境配置手把手指南」，装完务必跑一遍那里的「最终自检清单」，全部打勾。
2. 用 Postman 请求 `https://api.github.com/users/torvalds`，看看返回的 JSON 长什么样。

### ✅ 验收
- 环境自检清单全部通过。
- 你能用一句话解释 client / server / API。
- 你能指着 Postman 返回的内容说："这就是服务器回给我的**数据**。"

---

## 阶段 0 附录 · 环境配置手把手指南

> 目标：从一台**全新的电脑**，到**所有工具都装好、都验证通过**。
> 跟着一步一步来，每装完一个就跑一下"验证命令"，**看到预期输出再装下一个**。不要一口气全装完才检查。

### 你需要装的 6 样东西（总览）

| 软件 | 作用 | 一句话 |
|------|------|--------|
| 1. 包管理器 | 装其它软件用 | Mac 用 Homebrew，Windows 用 winget/Scoop |
| 2. Git | 版本控制 | 拉代码、提交代码 |
| 3. PHP 8.2 | 写后端代码的语言 | Lumen 跑在它上面 |
| 4. Composer | PHP 的包管理器 | 用来创建 Lumen 项目、装依赖 |
| 5. Docker Desktop | 容器化 | 把应用和 MySQL 打包跑起来 |
| 6. Postman + DBeaver + VS Code | 工具三件套 | 测接口 / 看数据库 / 写代码 |

> **重要说明**：阶段 5 之后，PHP 和 MySQL 其实都跑在 Docker 容器里。但前期阶段 2–4 我们要在**本机**直接用 PHP + Composer 体会基础，所以本机也得装。两边都装不冲突。

---

### 🍎 macOS 安装步骤

#### 1. 安装 Homebrew（包管理器）

打开「终端」(Terminal)，粘贴运行：
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
装完后，如果提示你要执行两行 `echo ... >> ~/.zprofile` 之类的命令，**照着提示执行**（这是把 brew 加到 PATH，Apple Silicon 芯片必须做这一步）。

**验证：**
```bash
brew --version
```
✅ 预期：看到 `Homebrew 4.x.x`。
❌ 报 `command not found`：说明 PATH 没配好，重开终端，或手动执行：
```bash
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
source ~/.zprofile
```

#### 2. 安装 Git

```bash
brew install git
```
**验证：** `git --version` → ✅ 看到 `git version 2.x`。

配置你的身份（提交代码时用）：
```bash
git config --global user.name "你的名字"
git config --global user.email "你的公司邮箱"
```

#### 3. 安装 PHP 8.2

```bash
brew install php@8.2
brew link php@8.2 --force
```
**验证：**
```bash
php -v
```
✅ 预期：第一行包含 `PHP 8.2.x`。

确认 MySQL 扩展在（后面连数据库要用）：
```bash
php -m | grep pdo_mysql
```
✅ 预期：输出 `pdo_mysql`。如果没有，brew 装的 php 通常自带；若缺，重装 `brew reinstall php@8.2`。

#### 4. 安装 Composer（PHP 包管理器）

```bash
brew install composer
```
**验证：**
```bash
composer --version
```
✅ 预期：`Composer version 2.x`。

#### 5. 安装 Docker Desktop

```bash
brew install --cask docker
```
装完后，**去启动台/应用程序里打开 Docker 这个 App**（第一次必须手动打开，它会要权限）。等右上角鲸鱼图标不再闪动、变成静止状态，表示 Docker 引擎已就绪。

**验证：**
```bash
docker --version
docker compose version
docker run hello-world
```
✅ 预期：`docker run hello-world` 打印一段 "Hello from Docker!"。
❌ 报 `Cannot connect to the Docker daemon`：说明 Docker Desktop 这个 App 没打开，去打开它，等图标变静止。

#### 6. 工具三件套

```bash
brew install --cask postman      # 接口测试
brew install --cask dbeaver-community   # 数据库客户端
brew install --cask visual-studio-code  # 代码编辑器
```

---

### 🪟 Windows 安装步骤

> 推荐用 Windows 自带的 `winget`（Win10/11 都有）。在「开始菜单」搜 **PowerShell**，**右键 → 以管理员身份运行**，然后执行下面的命令。

#### 1. Git
```powershell
winget install --id Git.Git -e
```
验证（**重新打开**一个 PowerShell 窗口）：`git --version`

#### 2. PHP 8.2
```powershell
winget install --id PHP.PHP.8.2 -e
```
> 如果 winget 里找不到，用 **Scoop**：先 `irm get.scoop.sh | iex`，再 `scoop install php`。
> 装完确认环境变量里有 php，重开终端跑 `php -v`，看到 `PHP 8.2.x` 即可。

需要手动开启扩展：找到 php 安装目录下的 `php.ini`，把这几行前面的分号 `;` 去掉：
```ini
extension=pdo_mysql
extension=mbstring
extension=openssl
```
验证：`php -m | findstr pdo_mysql` → 看到 `pdo_mysql`。

#### 3. Composer
```powershell
winget install --id Composer.Composer -e
```
验证：重开终端 → `composer --version`

#### 4. Docker Desktop
```powershell
winget install --id Docker.DockerDesktop -e
```
- 装完**重启电脑**。
- 第一次打开 Docker Desktop，它可能提示要装 **WSL2**，照它的提示点同意/安装即可。
- 等鲸鱼图标静止后验证：
```powershell
docker run hello-world
```

#### 5. 工具三件套
```powershell
winget install --id Postman.Postman -e
winget install --id dbeaver.dbeaver -e
winget install --id Microsoft.VisualStudioCode -e
```

---

### VS Code 推荐插件（Mac/Win 通用）

打开 VS Code → 左侧扩展图标（或按 `Cmd/Ctrl+Shift+X`）→ 搜索安装：
- **PHP Intelephense**（PHP 智能提示）
- **DotENV**（`.env` 文件高亮）
- **Docker**（管理容器）
- **REST Client**（或直接用 Postman 也行）

---

### ✅ 最终自检清单（全部跑一遍，全部打勾才算环境就绪）

| 命令 | 预期输出 |
|------|----------|
| `git --version` | `git version 2.x` |
| `php -v` | 第一行含 `PHP 8.2` |
| `php -m \| grep pdo_mysql`（Win 用 `findstr`） | `pdo_mysql` |
| `composer --version` | `Composer version 2.x` |
| `docker --version` | `Docker version 2x.x` |
| `docker compose version` | `Docker Compose version v2.x` |
| `docker run hello-world` | `Hello from Docker!` |

再确认这三个 App 能打开：**Postman、DBeaver、VS Code**。

全部通过 → 截图发给导师，进入阶段 1 🎉

---

### 🆘 常见报错速查

| 现象 | 原因 | 解决 |
|------|------|------|
| `command not found: brew/php/composer` | PATH 没配好 | 重开终端；Mac 确认 `~/.zprofile` 里有 brew 那行；Win 确认重开了 PowerShell |
| `Cannot connect to the Docker daemon` | Docker Desktop 没启动 | 打开 Docker Desktop App，等鲸鱼图标静止 |
| `docker run hello-world` 卡住/超时 | 镜像拉不下来（网络） | 配置镜像加速器，或挂代理后重试 |
| Win 上 Docker 提示要 WSL2 | 缺 WSL2 | 管理员 PowerShell 跑 `wsl --install`，重启 |
| `php` 能跑但连不上 MySQL | 缺 `pdo_mysql` 扩展 | 见上面各系统开启扩展的步骤 |
| 端口 3306 / 8000 被占用 | 本机已有 MySQL/其它服务占用 | 关掉占用的程序，或在 docker-compose 里改成 `3307:3306` |

> 卡住超过 30 分钟、按速查表也没解决 —— 带着"我执行了什么命令、完整报错截图"来找导师，不要只说"装不上"。

---

## 阶段 1 · 网络数据传输与 RESTful API

### 概念
**一次 HTTP 往返的结构：**
- 请求 = 方法（GET/POST/PUT/DELETE） + URL + Headers + Body
- 响应 = 状态码 + Headers + Body

**状态码（记住这三类）：**
- `2xx` 成功（200 OK、201 Created）
- `4xx` 客户端错了（400 参数错、404 找不到、422 校验不过）
- `5xx` 服务器错了（500 内部错误）

**为什么用 JSON：** 人能读、机器好解析、跨语言通用。

**RESTful 风格**：把"东西"看成**资源**，用 HTTP 方法表达动作，URL 只描述资源、不描述动作。

### 想一想
> 删除包裹，为什么用 `DELETE /parcels/123`，而不是 `GET /deleteParcel?id=123`？两种都能 work，REST 为什么选前者？
>
> 提示：方法本身（DELETE）已经表达了"删除"这个意图，URL 只需要指出"删谁"。

### 任务（这一步先不写代码）
**只用文档/纸笔**，为「包裹管理」写出完整的接口设计：
- 每个接口的：方法、URL、请求体字段、返回内容、可能的状态码。
- 这就是你的**接口文档**，后面写代码照着它实现。

### ✅ 验收
- 你能完整说出一次 HTTP 往返包含哪些部分。
- 你交出的接口设计表符合上面那张 5 行表格的 REST 规范。

---

## 阶段 2 · 数据模型与 MySQL

### 概念
- **为什么要数据库**：程序一关，内存里的数据就没了。数据库负责**持久化**（存到硬盘、随时能查）。
- **关系型数据库**：数据存在**表**里，表 = 行（一条记录） + 列（一个字段）。
- **数据建模**：从需求里找出"实体"和它的"属性"。
- **约束**：主键（`id` 自增、唯一标识一行）、唯一约束（运单号不能重复）、非空、默认值。

### 我们的 `parcels` 表设计

| 字段 | 类型 | 约束 | 说明 |
|------|------|------|------|
| id | BIGINT UNSIGNED | 主键, 自增 | 内部唯一编号 |
| tracking_no | VARCHAR(64) | 唯一, 非空 | 运单号 |
| recipient_name | VARCHAR(100) | 非空 | 收件人 |
| address | VARCHAR(255) | 非空 | 地址 |
| weight | DECIMAL(8,2) | 非空, 默认 0 | 重量(kg) |
| status | VARCHAR(20) | 非空, 默认 'pending' | 状态 |
| created_at | TIMESTAMP | 可空 | 创建时间 |
| updated_at | TIMESTAMP | 可空 | 更新时间 |

`status` 取值约定：`pending`(待揽收) / `in_transit`(运输中) / `delivered`(已签收)。

### 想一想
> 1. 状态字段你打算存 `'运输中'` 这种中文，还是存 `'in_transit'` 这种英文 code？为什么？
> 2. 如果两个快递员同时录入了同一个运单号，会怎样？数据库能帮你挡住吗？（提示：唯一约束）

### 任务（先裸手写 SQL，不碰框架）
在数据库客户端里，**手写 SQL** 完成：
1. `CREATE TABLE` 建出 `parcels` 表。
2. `INSERT` 插入 3 条包裹。
3. `SELECT * FROM parcels WHERE status = 'pending'` 查出来。
4. `UPDATE` 把其中一条改成 `delivered`。
5. `DELETE` 删掉一条。

> 目的：让你亲眼看到——后面框架做的事，本质就是帮你生成这些 SQL。

### ✅ 验收
- 你能说清每个字段为什么是这个类型、这个约束。
- 你能不查资料写出基本的增删改查 SQL。

---

## 阶段 3 · MVC 架构与 Lumen 框架

### 概念
**先想象"不分层"的痛苦**：如果把"连数据库 + 拼 SQL + 业务判断 + 输出 JSON"全塞在一个函数里，三个月后改需求你根本不敢动。

**MVC 把职责分开：**
- **Model**：管数据和业务规则（"包裹是什么、有哪些字段、能怎么变"）。
- **View**：管展示。API 场景下就是返回的 **JSON**。
- **Controller**：管调度（"收到请求 → 喊 Model 干活 → 把结果包成 JSON 响应"）。

**一个请求在 Lumen 里的流动路径（本阶段的灵魂图）：**

```
routes/web.php  →  Controller  →  Model (Eloquent)  →  MySQL
   (路由表)         (协调者)        (数据 + ORM)
                       │
                       └──→ 返回 JSON 响应给客户端
```

**Lumen 关键文件 / 目录：**
- `routes/web.php` —— 路由表，把 URL 映射到 Controller 方法
- `app/Http/Controllers/` —— 控制器
- `app/Models/` —— 模型
- `database/migrations/` —— 用代码管理表结构（可版本化、可重放的"建表脚本"）
- `.env` —— 配置（数据库地址、密码等）

**Migration 是什么**：把阶段 2 你手写的 `CREATE TABLE`，改写成代码，团队每个人 `php artisan migrate` 一下就能得到一模一样的表。可回滚、可追踪。

### 想一想
> 1. 如果业务逻辑写在 Controller 里，下次有个"定时任务"也要创建包裹，它怎么复用这段逻辑？
> 2. 为什么不直接连数据库手写 SQL 建表，要费劲写 migration？（提示：团队协作、环境一致、可回滚）

### 任务
1. 用 Composer 创建 Lumen 项目（命令见文末附录）。
2. 跑起来，浏览器访问看到 Lumen 欢迎页。
3. 把阶段 2 的建表 SQL **改写成一个 migration**。
4. （连数据库这一步我们留到阶段 5 用 Docker 一起做，这里先把代码写好。）

### ✅ 验收
- 你能对着白纸，画出"一个请求从进 Lumen 到返回 JSON，经过了路由→控制器→模型→数据库"。
- 你能解释 migration 和手写 SQL 的关系。

---

## 阶段 4 · 实现 CRUD 接口

### 概念
现在把前面所有东西串起来：路由收到请求 → 控制器处理 → 模型读写 MySQL → 返回 JSON。每个接口都要考虑：**参数校验**（用户传错了怎么办）、**正确的状态码**、**找不到资源的情况**。

### 任务
按下面的骨架填空（`// TODO` 是留给你的思考题，别直接问导师，先自己试）。

**1) 路由 `routes/web.php`**
```php
$router->group(['prefix' => 'parcels'], function () use ($router) {
    $router->get('/',        'ParcelController@index');
    $router->get('/{id}',    'ParcelController@show');
    $router->post('/',       'ParcelController@store');
    $router->put('/{id}',    'ParcelController@update');
    $router->delete('/{id}', 'ParcelController@destroy');
});
```

**2) 模型 `app/Models/Parcel.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $table = 'parcels';

    // 允许批量赋值的字段（想一想：为什么不能把 id 放进来？）
    protected $fillable = [
        'tracking_no', 'recipient_name', 'address', 'weight', 'status',
    ];
}
```

**3) 控制器 `app/Http/Controllers/ParcelController.php`**

```php
namespace App\Http\Controllers;

use App\Models\Parcel;use Illuminate\Http\Request;

class ParcelController extends Controller
{
    // GET /parcels —— 查列表
    public function index()
    {
        return response()->json(Parcel::all());
    }

    // GET /parcels/{id} —— 查单个
    public function show($id)
    {
        $parcel = Parcel::find($id);
        // TODO: 如果 $parcel 是 null（找不到），应该返回什么状态码？
        return response()->json($parcel);
    }

    // POST /parcels —— 新建
    public function store(Request $request)
    {
        // TODO: 校验 tracking_no / recipient_name / address 必填
        // 提示：$this->validate($request, [...]);
        $parcel = Parcel::create($request->all());
        // TODO: 新建成功应该返回 200 还是 201？
        return response()->json($parcel, 201);
    }

    // PUT /parcels/{id} —— 修改
    public function update(Request $request, $id)
    {
        $parcel = Parcel::find($id);
        // TODO: 找不到怎么办？
        $parcel->update($request->all());
        return response()->json($parcel);
    }

    // DELETE /parcels/{id} —— 删除
    public function destroy($id)
    {
        // TODO: 删除并返回合适的状态码（删除成功通常返回 200 或 204）
        Parcel::destroy($id);
        return response()->json(null, 204);
    }
}
```

### 想一想（必须能答上来）
- `Parcel::all()` 实际上执行了哪句 SQL？
- 用户 POST 时漏传了 `recipient_name`，你的接口现在会怎样？应该怎样？
- `find($id)` 找不到返回什么？你处理了吗？

### ✅ 验收（用 Postman 走一遍）
- POST 新建一个包裹 → 返回 201 + 新数据。
- GET 列表 → 看到刚建的。
- GET 一个不存在的 id → 返回 404（而不是报错或返回 null 还说 200）。
- PUT 修改 → 数据真的变了。
- DELETE → 再 GET 查不到了。
- 故意漏传必填字段 → 返回 422 校验错误，而不是 500。

---

## 阶段 5 · Docker 容器化

### 概念
- **痛点**："在我电脑能跑，在你电脑跑不起来"——因为大家的 PHP 版本、扩展、MySQL 版本都不一样。
- **容器**：把"应用 + 它需要的运行环境"打包成一个标准盒子，到哪台机器都一样跑。
- **镜像 (image)** 是模板，**容器 (container)** 是用模板跑起来的实例（类比：类 vs 对象）。
- **docker-compose**：一条命令同时启动多个容器（我们要：PHP 应用 + MySQL），并让它们能互相通信。

### 想一想
> 在 docker-compose 里，PHP 容器要连 MySQL，`.env` 里的数据库地址应该写 `127.0.0.1` 吗？
> 提示：容器之间通信用的是**服务名**，不是 localhost。两个容器是两台"独立的小机器"。

### 任务
在项目根目录创建以下文件：

**`docker-compose.yml`**
```yaml
services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    # 启动 Lumen 自带的开发服务器
    command: php -S 0.0.0.0:8000 -t public

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: parcel_db
      MYSQL_ROOT_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - dbdata:/var/lib/mysql

volumes:
  dbdata:
```

**`Dockerfile`**
```dockerfile
FROM php:8.2-cli
RUN apt-get update && apt-get install -y libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
```

**`.env` 里的数据库配置（关键：host 写服务名 `db`）**
```
DB_CONNECTION=mysql
DB_HOST=db          # ← 不是 127.0.0.1！这是 docker-compose 里的服务名
DB_PORT=3306
DB_DATABASE=parcel_db
DB_USERNAME=root
DB_PASSWORD=secret
```

**启动与初始化：**
```bash
docker compose up -d --build      # 启动两个容器
docker compose exec app composer install   # 装依赖
docker compose exec app php artisan migrate # 跑你阶段3写的 migration，建表
```

然后用 Postman 打 `http://localhost:8000/parcels` 重新走一遍阶段 4 的验收。

### ✅ 验收
- `docker compose up` 一条命令把应用 + 数据库都拉起来。
- migration 在容器里成功建表。
- 阶段 4 的 5 个接口在容器里全部跑通。
- 你能解释为什么 `DB_HOST` 是 `db` 而不是 `localhost`。

---

## 阶段 6 · 综合答辩（向导师讲清楚）

准备好用自己的话回答：
1. 我在 Postman 点一下 GET /parcels，到拿到 JSON，中间经过了哪些环节？（路由→控制器→模型→MySQL→JSON）
2. 为什么要分 MVC 三层？各层职责是什么？
3. migration 和手写 SQL 是什么关系？
4. 为什么要用 Docker？容器之间怎么通信？
5. 你的接口里，404 和 422 分别在什么情况下返回？

能把这 5 个问题讲明白，这个 onboarding 就算通关了。

---

## 附录：常用命令

```bash
# 创建 Lumen 项目
composer create-project --prefer-dist laravel/lumen parcel-api

# 进入容器执行 artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan migrate:rollback   # 回滚
docker compose exec app php artisan make:migration create_parcels_table

# 看容器日志 / 进容器
docker compose logs -f app
docker compose exec app bash
```

## 进阶挑战（做完上面还有余力）
- 给列表接口加**分页**和按 `status` **筛选**：`GET /parcels?status=pending&page=2`
- 把校验逻辑抽到 **Form Request** 或独立的 Service 层。
- 给运单号加**唯一性校验**，重复时返回友好的 422 而不是数据库 500。
- 写一个 **artisan 命令**复用"创建包裹"的逻辑，验证你的业务逻辑没有被困在 Controller 里。
