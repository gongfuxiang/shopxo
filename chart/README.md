### 更新插件

```
helm dependency update shopxo
```

### 输出部署模板

```
helm template -f shopxo/values-test.yaml shopxo shopxo/ -n freeb > test.yaml
```