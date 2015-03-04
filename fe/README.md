## 兴教贷前端代码

### 程序安装
代码下载下来后，需要安装前端用的基础库及本地开发环境模块

1. 执行 `edp update` 用来下载edp相关的基础库
2. 执行 `npm i` 安装phpcgi模块，用来在本地模拟运行php程序
3. 由于指定的phpcgi模块代码有误，需要手动修改 `node_modules/node-phpcgi/main.js` 中的red -> req
4. 执行命令 `edp ws start` 启动服务器

### 前端smarty模板
在 mock/entry/ 目录下，具体可以参照demo目录书写

### 前端静态页面
在 entry/ 目录下，写好后可以修改成smarty模板

### 代码构建
执行命令 `edp build -f` 编译代码
