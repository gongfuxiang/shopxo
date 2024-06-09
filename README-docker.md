
## 测试
```
#打包镜像
podman build -t registry.freeb.vip/freeb/shopxo:dev -f Dockerfile .
#调试
podman run -d --name db -p 13306:3306 --env MARIADB_ROOT_PASSWORD=root123 --env MARIADB_DATABASE=shopxo  docker.io/library/mariadb:latest
podman run --rm -p 8000:80 --name shop -it -v `pwd`/:/var/www/html/ registry.freeb.vip/freeb/shopxo:dev bash
```

### Helm部署测试
```
helm template shopxo shopxo/ -n freeb > test.yaml
```

## 生产
```
podman build -t registry.freeb.vip/freeb/shopxo:latest -f Dockerfile .
```

### helm部署
