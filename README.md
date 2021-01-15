
Xiuno BBS 4.0.4 插件 - GitHub登录
------------------

此插件原作者为 midoks，由 Wr 进行二次开发优化。

### 设置 OAuth Apps
- https://github.com/settings/developers
```
在 GitHub 地址点击`New OAuth App`创建一个应用,设置应用名得到App的数据。
Callback URL 请填写：https://您的域名/github_login-index-home.htm
在Xiuno系统后台设置:
Application Name
Client ID
Client Secret
```

### 注意
```
卸载插件时，会删除GitHub与用户UID绑定的表。如果有用户是直接使用微信登录（新建用户），则会导致该用户无法再正常登录。
切记！！！
您的 Xiuno 网站必须开启 SSL（即 HTTPS 协议），否则你需要在 /route/github_login.php 的第 78 行将 https:// 改为 http://
```
