# E-Campus-Electricity
易校园 账号所属大学寝室电费余额获取

# 安装
```
composer require foskytech/e-campus-electricity
```
# 开始
* 使用模拟器或者手机安装 ***易校园*** 和 ***HttpCanary***
* 登录后开始抓包，随便点个东西进去，并在 ***HttpCanary*** 抓到的包中查看参数
* **shiroJID** 在cookie里，格式：shiroJID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
* **ymId** 在请求参数中，是一串数字

# 使用
参照 [Example](https://github.com/thephpleague/oauth2-server/blob/master/test/example.php) 

# License

This package is released under the MIT License. See the bundled [LICENSE](https://github.com/FoskyTech/E-Campus-Electricity/blob/main/LICENSE) file for details.