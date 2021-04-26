### 启动
``` bash
$ docker-compose up # 启动全部服务
```

### 服务说明
&emsp;所有服务共6个，分别为

#### 1.1 mysql服务
&emsp;为后端提供数据持久化服务

#### 1.2 redis服务
&emsp;为后台端口提供队列和缓存服务

#### 1.3 hyperf服务
&emsp;这是个名为hyperf的框架，只为前端小程序提供`websocket`服务。这个服务依赖于
`mysql`服务，`redis`服务和`broker`服务。`websocket`的数据接口为:
打开 https://websocketking.com/
并把 `hyperf/api.conf`的内容导入到`local Storage`的`SocketKing`的键值中。
刷新下.
对外服务端口:
      - 9602 // websocket

#### 1.4 broker服务
&emsp;这个服务提供`mqtt`硬件连接服务，并通过`webhook`方式通知后端,
 注意`webhook`是一个插件，默认不启动， 需要手动启动(也可以自己想想怎么自启)， 方式为:
1 访问`broker`管理台(18083端口)
2 找到插件配置并启动
对外服务端口:
      - 1883:1883  // mqtt 服务
      - 8081:8081
      - 8083:8083
      - 8883:8883
      - 8084:8084
      - 18083:18083 // web管理后台服务

#### 1.5 http-api 服务
&emsp; 这个提供`rest-full`接口服务，用于支持管理后台，接口配置文件放在`http-api/http-api-postman-export-conf.json`, 把这个文件导入到`postman`中或访问
[在线文档](线文档`https://documenter.getpostman.com/view/4497939/TzJsfxxQ)  
对外服务端口:
- 3000

#### 1.6 admin-template 服务
&emsp;这个是管理后台模板  
对外服务端口:
- 9527

### nginx 配置
端口不好记，用域名方式好管理

#### 前端和设备配置
``` nginx 
server
{
    listen 80;
	listen 443 ssl http2;
    server_name a1206.42.huizhouyiren.com;
    index index.php index.html index.htm default.php default.htm default.html;
    root /www/wwwroot/a1206.42.huizhouyiren.com;
    
    #SSL-START SSL相关配置，请勿删除或修改下一行带注释的404规则
    #error_page 404/404.html;
    #HTTP_TO_HTTPS_START
    if ($server_port !~ 443){
        rewrite ^(/.*)$ https://$host$1 permanent;
    }
    #HTTP_TO_HTTPS_END
    ssl_certificate    /www/server/panel/vhost/cert/a1206.42.huizhouyiren.com/fullchain.pem;
    ssl_certificate_key    /www/server/panel/vhost/cert/a1206.42.huizhouyiren.com/privkey.pem;
    ssl_protocols TLSv1.1 TLSv1.2 TLSv1.3;
    ssl_ciphers EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    add_header Strict-Transport-Security "max-age=31536000";
    error_page 497  https://$host$request_uri;

    #SSL-END
    
    #ERROR-PAGE-START  错误页配置，可以注释、删除或修改
    #error_page 404 /404.html;
    #error_page 502 /502.html;
    #ERROR-PAGE-END
    
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include enable-php-00.conf;
    #PHP-INFO-END
    
    #REWRITE-START URL重写规则引用,修改后将导致面板设置的伪静态规则失效
    include /www/server/panel/vhost/rewrite/a1206.42.huizhouyiren.com.conf;
    #REWRITE-END
   #websocket 服务
    location /websocket {
	    proxy_pass http://192.168.0.47:9602/;
	    proxy_http_version 1.1;
	    proxy_set_header Upgrade $http_upgrade;
	    proxy_set_header Connection "Upgrade";
	    proxy_set_header Remote_addr $remote_addr;
	    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
    location /api {
	    proxy_pass http://192.168.0.47:3000/api;
	    proxy_http_version 1.1;
	    proxy_set_header Upgrade $http_upgrade;
	    proxy_set_header Remote_addr $remote_addr;
	    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
    location / {
	    proxy_pass http://192.168.0.47:9604/;
	    proxy_http_version 1.1;
	    proxy_set_header Upgrade $http_upgrade;
	    proxy_set_header Connection "Upgrade";
	    proxy_set_header Remote_addr $remote_addr;
	    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
    
    
    #禁止访问的文件或目录
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    {
        return 404;
    }
    
    #一键申请SSL证书验证目录相关设置
    location ~ \.well-known{
        allow all;
    }
    
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
        error_log /dev/null;
        access_log off;
    }
    
    location ~ .*\.(js|css)?$
    {
        expires      12h;
        error_log /dev/null;
        access_log off; 
    }
    access_log  /www/wwwlogs/a1206.42.huizhouyiren.com.log;
    error_log  /www/wwwlogs/a1206.42.huizhouyiren.com.error.log;
}
```

#### 后台管理nginx配置
``` nginx 
#PROXY-START/
location ~* \.(php|jsp|cgi|asp|aspx)$
{
	proxy_pass http://192.168.0.47:9527;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header REMOTE-HOST $remote_addr;
}
location /
{
    proxy_pass http://192.168.0.47:9527;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header REMOTE-HOST $remote_addr;
    
    add_header X-Cache $upstream_cache_status;
    
    #Set Nginx Cache
    
    	add_header Cache-Control no-cache;
    expires 12h;
}

#PROXY-END/
```
* 注: 看转发配置就行了
