### 启动
``` bash
$ docker-compose up # 启动全部服务
$ docker-compose exec mqtt php bin/migration.php migrate # 迁移数据库
```

### 接口文档
打开 https://websocketking.com/
并把 `api.conf`的内容导入到`local Storage`的`SocketKing`的键值中。
刷新下.


