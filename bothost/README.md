# bothost

独立于 PHPDTS 本体的 BOT 宿主程序。它运行在服务器 A，通过 HTTP 长连接托管多个目标 PHPDTS 站点的 `bot/revbotservice.php` 进程。

## 设计要点

- **不改 PHPDTS 主体代码**：只新增 `bothost/`。
- **不依赖目标机 shell**：不需要在目标机执行 `bot_enable.sh`。
- **多站点**：一个 bothost 可同时接入多个 PHPDTS 站点。
- **多 bot 并发**：每个站点可设多个 worker（等价多 BOT 守护连接）。
- **自动恢复**：连接中断/超时会自动重连。

## 使用方式

1. 准备配置：

```bash
cp bothost/config.example.json bothost/config.json
# 修改 bothost/config.json
```

2. 启动：

```bash
python bothost/main.py -c bothost/config.json
```

3. 停止：

- `Ctrl+C`，或发送 `SIGTERM`。

## 配置说明

- `report_interval_sec`：状态汇总打印间隔。
- `targets[]`：目标站点列表。
  - `name`：站点名。
  - `revbotservice_url`：目标站点的 `.../bot/revbotservice.php` 完整 URL。
  - `workers`：并发 worker 数（通常对应可并发 bot 初始化/行动进程数）。
  - `connect_timeout_sec`：连接超时。
  - `read_timeout_sec`：读取超时，超时会断开并重连。
  - `restart_delay_sec`：重连等待秒数。
  - `disable_env_proxy`：是否禁用环境变量中的 HTTP/HTTPS 代理（默认 true，建议保持）。
  - `insecure_skip_tls_verify`：是否跳过 TLS 证书校验（默认 false，仅测试环境临时使用）。
  - `headers`：额外请求头。
  - `query`：附加查询参数。可加入 `respawn_chance`（0-100）控制 BOT 死亡后的随机补位概率。

## 注意事项

1. `revbotservice.php` 是长生命周期脚本，受目标 PHP 运行时参数影响（如 `max_execution_time`）。
2. 若目标前置代理（Nginx/CDN）对长连接有限制，需要放宽超时。
3. bothost 仅负责远程托管与状态监测；BOT 行为逻辑仍由 PHPDTS 原生 `revbot` 代码执行。


## 故障诊断

- 状态中 `err` 持续增长时，查看汇总下方 `last_error`，可直接看到最近一次 HTTP/网络错误详情。
- 若出现类似 `Tunnel connection failed: 403 Forbidden`，通常是环境代理劫持导致，请确认 `disable_env_proxy=true`。
- 若出现证书错误，可先确认站点证书链；仅在临时测试中可设 `insecure_skip_tls_verify=true`。

- 若 `last_error` 中包含 `include(...common.inc.php): failed to open stream` 等报错，通常是目标站 `bot/revbotservice.php` 在 Web 环境下工作目录不正确；本仓库已修复为基于脚本目录计算 GAME_ROOT。

- 当目标端输出“已死亡；已加入重生队列 / 不加入重生队列”时，bothost 会将该 worker 标记为 `bot_dead_queued` / `bot_dead_retired`，并等待连接退出后自动重连。
