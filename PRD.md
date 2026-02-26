# 彭城堂中医官方网站 — 产品需求文档 (PRD)

**版本：** v1.0
**日期：** 2026-02-24
**项目路径：** `/pengchengtang/`
**代码仓库：** https://github.com/sttOct20/pengchengtang.git

---

## 一、项目概述

### 品牌背景

彭城堂中医（Pengchengtang TCM）是一家以家学传承为核心的中医诊所品牌，创立于1824年，历经六代嫡传传承人，以「非遗炁针疗法」为核心诊疗技术，专注于解决常规疗法无法解决的疑难杂症。目前在北京、哈尔滨、珠海三城设有直营门诊。

### 网站定位

- 品牌官方展示网站，面向患者、潜在加盟商、中医学习者三类核心用户
- 语言：中/英双语（前端切换）
- 视觉风格：深色背景、金红配色，体现中医传统底蕴与现代审美

### 核心目标

1. 展示品牌形象与传承故事
2. 展示三家直营门诊信息
3. 提供中医健康知识内容（健康学堂）
4. 引流招商加盟咨询
5. 引导用户进入炁针在线学习平台

---

## 二、技术架构

### 前端

| 技术 | 用途 |
|------|------|
| HTML5 | 页面结构 |
| CSS3 | 样式（单文件 `css/style.css`） |
| JavaScript | 交互逻辑（`js/main.js`） |
| Google Fonts | Noto Serif SC（中文）、Cormorant Garamond（英文） |

### 后端（内容管理）

| 技术 | 用途 |
|------|------|
| PHP | 后台逻辑 |
| JSON 文件 | 数据持久化（无数据库） |
| PHP Session | 管理员身份验证 |
| Quill.js | 富文本编辑器 |

### 数据文件

| 文件路径 | 内容 |
|----------|------|
| `data/articles.json` | 所有文章数据 |
| `data/categories.json` | 自定义文章分类 |
| `assets/uploads/` | 上传的图片文件 |

### 部署

- 服务器：VPS（Linux）
- 代码管理：Git + GitHub
- 部署方式：SSH 登录服务器，`git pull` 拉取更新
- 域名：`www.pengchengtangtcm.com`（含 canonical 配置）

---

## 三、页面结构

### 公开页面（7个）

| 文件 | 页面名称 | 核心内容 |
|------|----------|----------|
| `index.html` | 首页 | Hero 全屏展示、品牌预览、门诊卡片、文章预览、加盟 CTA |
| `about.html` | 品牌介绍 | 品牌历史、传承故事、创始人介绍 |
| `clinics.html` | 门诊展示 | 三家门诊详细信息、图片展示 |
| `learning.html` | 健康学堂 | 文章列表（动态加载）、在线学习平台入口 |
| `article.html` | 文章详情 | 单篇文章完整内容（JS 动态渲染） |
| `franchise.html` | 招商加盟 | 七大赋能体系、加盟流程、联系方式 |
| `contact.html` | 联系我们 | 企业微信二维码、电话、地址 |

### 后台管理页面

| 文件 | 功能 |
|------|------|
| `admin/login.php` | 管理员登录 |
| `admin/index.php` | 文章列表（含分类筛选） |
| `admin/edit.php` | 文章创建/编辑表单 |
| `admin/save.php` | 文章保存处理 |
| `admin/delete.php` | 文章删除处理 |
| `admin/categories.php` | 分类管理（增/改/删） |
| `admin/upload.php` | 图片上传接口 |

---

## 四、页面详细规格

### 4.1 首页（index.html）

#### 导航栏（nav）
- Logo（图片 + 文字）
- 导航链接：首页、品牌介绍、门诊展示、健康学堂、招商加盟、联系我们
- 语言切换按钮（中/英）
- 移动端汉堡菜单

#### Hero 区块
- 全屏背景（深色）
- 装饰性竖线（左右各一）
- Logo 印章图片
- **副标题**（`hero-label`）：「百年中医传承，非遗炁针疗法」
  - 字体：Noto Serif SC，金色（`var(--gold)`）
  - 字号：`clamp(1.1rem, 2.8vw, 1.8rem)`
- **主标题**（`hero-title`）：「彭城堂中医」/ 英文副行
- **口号**（`hero-slogan`）：「解决常规疗法解决不了的疑难杂症」
- CTA 按钮：「了解我们」（品牌介绍页）、「招商合作」（加盟页）
- 向下滚动提示（`hero-scroll`）：「向下探索」，颜色 `rgba(255,255,255,0.75)`

#### 统计数字栏（highlights，绝对定位在 Hero 底部）
- 位置：`position: absolute; bottom: 0`，与 Hero 同屏显示
- 3列网格：
  - 百年 / 家学传承
  - 六代 / 嫡传传承人
  - 1000+ / 中医人才

#### 品牌预览区块
- 左侧：文字介绍 + 「查看品牌故事」按钮
- 右侧：装饰性「炁」字图 + 「1824年创立」徽章

#### 直营门诊区块
- 3列卡片：北京、哈尔滨、珠海
- 每卡片含：城市名、诊所名、地址、电话
- 「查看全部门诊」按钮

#### 健康学堂区块
- 在线学习平台 CTA 横幅
- 3篇文章预览卡片（静态占位）
- 「查看更多文章」按钮

#### 招商加盟 CTA 区块
- 全宽深色背景横幅
- 「了解加盟详情」按钮

#### Footer
- Logo + 品牌简介
- 快速导航链接
- 联系方式
- 在线学习入口
- 版权信息

---

### 4.2 健康学堂（learning.html）

- **在线学习平台入口**：链接至 `https://learn.pengchengtangtcm.com`
- **文章列表**：
  - 分类标签筛选（动态加载自 `data/categories.json`）
  - 文章卡片：缩略图、分类标签、标题、作者、发布日期
  - 点击跳转至 `article.html?id={文章ID}`

---

### 4.3 文章详情（article.html）

- 通过 URL 参数 `?id=` 获取文章 ID
- 从 `api/articles.php` 或直接读取 `data/articles.json` 渲染
- 显示字段：标题、分类、作者（无作者则显示「彭城堂医师团队」）、发布日期、缩略图、正文内容
- 返回列表按钮

---

### 4.4 门诊展示（clinics.html）

3家门诊详细卡片，每张卡片结构：
- 外层容器：`<div class="clinic-detail-card reveal">`
- 2列网格布局：左侧图片区域 + 右侧文字信息
- 信息字段：城市、诊所名、地址、电话
- 3家门诊：北京彭城堂、哈尔滨彭城堂、珠海彭城堂

---

## 五、内容管理后台（Admin）

### 访问权限

- 入口：`/admin/login.php`
- 密码：`pengchengtang2025`（单用户密码认证）
- 通过 PHP Session 维持登录状态

### 文章管理

#### 文章字段

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | string | 时间戳生成的唯一ID |
| `title` | string | 文章标题 |
| `category` | string | 所属分类 |
| `author` | string | 作者姓名（可选，默认显示「彭城堂医师团队」） |
| `publishedDate` | string | 发布日期（YYYY-MM-DD） |
| `thumbnail` | string | 缩略图路径 |
| `excerpt` | string | 摘要 |
| `content` | string | 正文 HTML（Quill.js 输出） |
| `status` | string | 发布状态（published/draft） |

#### 操作功能

- **列表页**（`admin/index.php`）：按分类筛选、标题搜索、编辑、删除
- **编辑页**（`admin/edit.php`）：
  - Quill.js 富文本编辑器
  - 图片上传（拖拽/点击，`admin/upload.php` 接口）
  - 分类下拉（动态加载自 `categories.json`）
  - 作者名称输入字段
  - 发布日期选择
  - **预览文章**：全屏模态框，实时渲染当前表单内容，无需保存即可预览
  - 「保存发布」按钮

### 分类管理（`admin/categories.php`）

- 查看所有分类
- 新增分类
- 重命名分类（内联表单，JS 控制显示）
- 删除分类
- 数据持久化至 `data/categories.json`
- 默认分类：炁针疗法、辨证施治、疑难杂症、健康养生、中药本草

---

## 六、设计规范

### 颜色变量

| 变量 | 颜色值 | 用途 |
|------|--------|------|
| `--gold` | `#c9a96e`（金色） | 品牌强调色、副标题 |
| `--red` | `#c41e3a`（红色） | 装饰线条 |
| 背景 | `#0d0504`（深棕黑） | 主背景色 |
| 文字 | `#f5f0e8`（暖白） | 主要文字 |

### 字体

| 变量 | 字体 | 用途 |
|------|------|------|
| `--font-zh` | Noto Serif SC | 中文内容 |
| `--font-en` | Cormorant Garamond | 英文内容、装饰性数字 |

### 响应式断点

- 移动端导航：汉堡菜单（`#hamburger`）
- 文字大小：广泛使用 `clamp()` 实现流体字号
- 网格：门诊卡片、亮点统计均在小屏幕下折叠为单列

### 动画

- 页面元素滚动显示：`.reveal` 类配合 IntersectionObserver（`js/main.js`）
- 延迟变体：`.reveal-delay-1`、`.reveal-delay-2`、`.reveal-delay-3`

---

## 七、资源文件

| 路径 | 说明 |
|------|------|
| `assets/logo.png` | 品牌Logo（印章样式） |
| `assets/weblogo.png` | 网站 Favicon |
| `assets/uploads/` | 后台上传的文章图片 |
| `css/style.css` | 全局样式表（单文件） |
| `js/main.js` | 前端交互脚本 |

### Favicon 配置

所有7个 HTML 页面均已添加：
```html
<link rel="icon" type="image/png" href="assets/weblogo.png">
```

---

## 八、SEO 配置

- 每页设置 `<meta name="description">` 和 `<meta name="keywords">`
- `<link rel="canonical">` 指向规范 URL
- 页面 `lang="zh-CN"` 设置
- 双语 `data-zh` / `data-en` 属性驱动前端切换

---

## 九、双语切换机制

- 语言切换按钮（`#langToggle`）存储语言偏好至 `localStorage`
- `js/main.js` 遍历所有含 `data-zh` / `data-en` 属性的元素，切换 `textContent`
- 支持导航栏、正文、按钮、Footer 全部文案切换

---

## 十、联系信息

| 项目 | 内容 |
|------|------|
| 北京门诊电话 | 400-086-0131 |
| 哈尔滨门诊电话 | 130-0986-0432 |
| 珠海门诊电话 | 131-9225-4098 |
| 邮箱 | 173992289@qq.com |
| 在线学习平台 | https://learn.pengchengtangtcm.com |

---

## 十一、待办事项 / 已知问题

| 状态 | 描述 |
|------|------|
| 待完善 | 健康学堂文章列表目前首页展示为静态占位卡片，需接入动态数据 |
| 待完善 | 门诊详情页图片目前为占位样式，需上传实际门诊照片 |
| 待完善 | 文章详情页 `article.html` 依赖 JS 动态渲染，SEO 爬虫抓取有限 |
| 待确认 | 在线学习平台（`learn.pengchengtangtcm.com`）为独立子域名系统 |
| 建议 | 考虑为后台增加多用户/角色权限体系 |
| 建议 | 文章可增加"草稿"状态管理，与"已发布"分开管理 |
