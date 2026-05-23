#!/usr/bin/env python3
"""phpdts Game Management Dashboard"""

import os
import re
import subprocess
import time
import socket
from pathlib import Path
from datetime import datetime

import pymysql
from flask import Flask, jsonify, render_template, request

app = Flask(__name__)

GAME_ROOT = Path(__file__).resolve().parent.parent
PHP_EXE = r"D:\phpstudy_pro\Extensions\php\php7.3.4nts\php.exe"
MYSQL_DIR = r"D:\phpstudy_pro\Extensions\MySQL8.0.12"
MYSQL_EXE = os.path.join(MYSQL_DIR, "bin", "mysqld.exe")
MYSQL_CLI = os.path.join(MYSQL_DIR, "bin", "mysql.exe")
MYSQL_INI = os.path.join(MYSQL_DIR, "my.ini")
GAME_HOST = "localhost"
GAME_PORT = 8080
MANAGER_PORT = 5099


# ── helpers ──────────────────────────────────────────────

def read_config():
    info = {}
    cf = GAME_ROOT / "config.inc.php"
    if cf.exists():
        c = cf.read_text(encoding="utf-8", errors="ignore")
        for k in ["dbhost", "dbuser", "dbpw", "dbname", "tablepre",
                   "gamefounder", "gameurl", "title", "database", "charset"]:
            m = re.search(rf"\${k}\s*=\s*['\"](.*?)['\"]", c)
            if m:
                info[k] = m.group(1)
    return info


def get_db():
    cfg = read_config()
    return pymysql.connect(
        host=cfg.get("dbhost", "localhost"),
        user=cfg.get("dbuser", "root"),
        password=cfg.get("dbpw", ""),
        database=cfg.get("dbname", "acdts3"),
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
    )


def check_port(port):
    for addr in ("127.0.0.1", "::1"):
        try:
            af = socket.AF_INET if addr == "127.0.0.1" else socket.AF_INET6
            s = socket.socket(af, socket.SOCK_STREAM)
            s.settimeout(1)
            s.connect((addr, port))
            s.close()
            return True
        except Exception:
            pass
    return False


def get_pids(name):
    try:
        r = subprocess.run(
            ['tasklist', '/FI', f'IMAGENAME eq {name}', '/FO', 'CSV', '/NH'],
            capture_output=True, text=True, timeout=5
        )
        pids = []
        if name in r.stdout:
            for line in r.stdout.strip().split("\n"):
                parts = line.replace('"', '').split(",")
                if len(parts) >= 2 and parts[1].strip().isdigit():
                    pids.append(parts[1].strip())
        return pids
    except Exception:
        return []


def get_php_version():
    try:
        r = subprocess.run([PHP_EXE, "-v"], capture_output=True, text=True, timeout=5)
        return r.stdout.split("\n")[0] if r.returncode == 0 else "unknown"
    except Exception:
        return "not installed"


def get_mysql_version():
    try:
        r = subprocess.run([MYSQL_CLI, "--version"], capture_output=True, text=True, timeout=5)
        return r.stdout.strip() if r.returncode == 0 else "unknown"
    except Exception:
        return "not installed"


# ── dashboard ────────────────────────────────────────────

@app.route("/")
def index():
    return render_template("index.html")


@app.route("/api/status")
def api_status():
    php_running = check_port(GAME_PORT)
    mysql_running = check_port(3306)
    cfg = read_config()

    total_size = 0
    file_count = 0
    for f in GAME_ROOT.rglob("*"):
        if f.is_file() and ".git" not in str(f) and f.name != "BRLOGO.jpg":
            try:
                total_size += f.stat().st_size
                file_count += 1
            except OSError:
                pass

    return jsonify({
        "php": {
            "running": php_running,
            "version": get_php_version(),
            "port": GAME_PORT,
            "pids": get_pids("php.exe"),
        },
        "mysql": {
            "running": mysql_running,
            "version": get_mysql_version(),
            "port": 3306,
            "pids": get_pids("mysqld.exe"),
        },
        "game": {
            "title": cfg.get("title", "大 逃 杀"),
            "founder": cfg.get("gamefounder", ""),
            "url": f"http://{GAME_HOST}:{GAME_PORT}/",
            "database": cfg.get("database", "mysqli"),
            "charset": cfg.get("charset", "utf-8"),
            "db_name": cfg.get("dbname", ""),
            "db_host": cfg.get("dbhost", ""),
        },
        "files": {"count": file_count, "size_mb": round(total_size / (1024 * 1024), 2)},
        "server_time": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
    })


# ── service control ──────────────────────────────────────

@app.route("/api/php/start", methods=["POST"])
def api_php_start():
    if check_port(GAME_PORT):
        return jsonify({"ok": False, "msg": "PHP already running on port %d" % GAME_PORT})
    try:
        subprocess.Popen(
            [PHP_EXE, "-S", f"{GAME_HOST}:{GAME_PORT}", "-t", str(GAME_ROOT)],
            cwd=str(GAME_ROOT), creationflags=subprocess.CREATE_NO_WINDOW
        )
        time.sleep(1.5)
        ok = check_port(GAME_PORT)
        return jsonify({"ok": ok, "msg": "PHP started" if ok else "PHP start failed"})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/php/stop", methods=["POST"])
def api_php_stop():
    pids = get_pids("php.exe")
    if not pids:
        return jsonify({"ok": False, "msg": "No PHP process found"})
    for pid in pids:
        subprocess.run(['taskkill', '/F', '/PID', pid], capture_output=True, text=True)
    time.sleep(1)
    ok = not check_port(GAME_PORT)
    return jsonify({"ok": ok, "msg": "PHP stopped" if ok else "PHP may still be running"})


@app.route("/api/mysql/start", methods=["POST"])
def api_mysql_start():
    if check_port(3306):
        return jsonify({"ok": False, "msg": "MySQL already running on port 3306"})
    try:
        subprocess.Popen(
            [MYSQL_EXE, f"--defaults-file={MYSQL_INI}", "--console"],
            creationflags=subprocess.CREATE_NO_WINDOW
        )
        time.sleep(3)
        ok = check_port(3306)
        return jsonify({"ok": ok, "msg": "MySQL started" if ok else "MySQL start failed"})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/mysql/stop", methods=["POST"])
def api_mysql_stop():
    pids = get_pids("mysqld.exe")
    if not pids:
        return jsonify({"ok": False, "msg": "No MySQL process found"})
    for pid in pids:
        subprocess.run(['taskkill', '/F', '/PID', pid], capture_output=True, text=True)
    time.sleep(2)
    ok = not check_port(3306)
    return jsonify({"ok": ok, "msg": "MySQL stopped" if ok else "MySQL may still be running"})


# ── one-click game control ───────────────────────────────

@app.route("/api/game/start", methods=["POST"])
def api_game_start():
    msgs = []
    all_ok = True

    if not check_port(3306):
        try:
            subprocess.Popen(
                [MYSQL_EXE, f"--defaults-file={MYSQL_INI}", "--console"],
                creationflags=subprocess.CREATE_NO_WINDOW
            )
            time.sleep(3)
        except Exception as e:
            msgs.append(f"MySQL: {e}")
            all_ok = False
    if check_port(3306):
        msgs.append("MySQL: running")
    else:
        msgs.append("MySQL: failed")
        all_ok = False

    if not check_port(GAME_PORT):
        try:
            subprocess.Popen(
                [PHP_EXE, "-S", f"{GAME_HOST}:{GAME_PORT}", "-t", str(GAME_ROOT)],
                cwd=str(GAME_ROOT), creationflags=subprocess.CREATE_NO_WINDOW
            )
            time.sleep(1.5)
        except Exception as e:
            msgs.append(f"PHP: {e}")
            all_ok = False
    if check_port(GAME_PORT):
        msgs.append("PHP: running")
    else:
        msgs.append("PHP: failed")
        all_ok = False

    return jsonify({"ok": all_ok, "msg": " | ".join(msgs)})


@app.route("/api/game/stop", methods=["POST"])
def api_game_stop():
    msgs = []
    php_pids = get_pids("php.exe")
    if php_pids:
        for pid in php_pids:
            subprocess.run(['taskkill', '/F', '/PID', pid], capture_output=True, text=True)
        time.sleep(1)
        msgs.append("PHP: stopped" if not check_port(GAME_PORT) else "PHP: still running")
    else:
        msgs.append("PHP: no process")

    mysql_pids = get_pids("mysqld.exe")
    if mysql_pids:
        for pid in mysql_pids:
            subprocess.run(['taskkill', '/F', '/PID', pid], capture_output=True, text=True)
        time.sleep(2)
        msgs.append("MySQL: stopped" if not check_port(3306) else "MySQL: still running")
    else:
        msgs.append("MySQL: no process")

    return jsonify({"ok": True, "msg": " | ".join(msgs)})


# ── database browser ─────────────────────────────────────

@app.route("/api/db/tables")
def api_db_tables():
    try:
        db = get_db()
        with db.cursor() as cur:
            cur.execute("SHOW TABLES")
            tables = [list(row.values())[0] for row in cur.fetchall()]
        db.close()
        return jsonify({"ok": True, "tables": tables})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/describe")
def api_db_describe():
    table = request.args.get("table", "")
    if not table:
        return jsonify({"ok": False, "msg": "table required"})
    try:
        db = get_db()
        with db.cursor() as cur:
            cur.execute(f"DESCRIBE `{table}`")
            cols = cur.fetchall()
            cur.execute(f"SHOW CREATE TABLE `{table}`")
            create = cur.fetchone()
        db.close()
        return jsonify({
            "ok": True,
            "columns": cols,
            "create_sql": create.get("Create Table", "") if create else "",
        })
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/data")
def api_db_data():
    table = request.args.get("table", "")
    page = int(request.args.get("page", 1))
    limit = int(request.args.get("limit", 50))
    offset = (page - 1) * limit
    if not table:
        return jsonify({"ok": False, "msg": "table required"})
    try:
        db = get_db()
        with db.cursor() as cur:
            cur.execute(f"SELECT COUNT(*) AS _cnt FROM `{table}`")
            total = cur.fetchone()["_cnt"]
            cur.execute(f"SELECT * FROM `{table}` LIMIT {limit} OFFSET {offset}")
            rows = cur.fetchall()
            raw_cols = [d[0] for d in cur.description]
            seen = {}
            cols = []
            for c in raw_cols:
                if c in seen:
                    seen[c] += 1
                    cols.append(f"{c}_{seen[c]}")
                else:
                    seen[c] = 0
                    cols.append(c)
            # get primary keys
            cfg = read_config()
            db_name = cfg.get("dbname", "acdts3")
            cur.execute(f"""
                SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s
                AND CONSTRAINT_NAME = 'PRIMARY'
                ORDER BY ORDINAL_POSITION
            """, (db_name, table))
            pk_cols = [r["COLUMN_NAME"] for r in cur.fetchall()]
        db.close()
        return jsonify({
            "ok": True, "columns": cols, "rows": rows,
            "total": total, "page": page,
            "pages": max(1, (total + limit - 1) // limit), "limit": limit,
            "pk": pk_cols,
        })
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/update", methods=["POST"])
def api_db_update():
    data = request.get_json(force=True)
    table = data.get("table", "")
    pk = data.get("pk", {})
    updates = data.get("data", {})
    if not table or not pk or not updates:
        return jsonify({"ok": False, "msg": "table, pk, and data required"})
    try:
        db = get_db()
        with db.cursor() as cur:
            set_clause = ", ".join(f"`{k}` = %s" for k in updates)
            where_clause = " AND ".join(
                (f"`{k}` = %s" if v is not None else f"`{k}` IS NULL") for k, v in pk.items()
            )
            params = list(updates.values()) + [v for v in pk.values() if v is not None]
            sql = f"UPDATE `{table}` SET {set_clause} WHERE {where_clause} LIMIT 1"
            cur.execute(sql, params)
            db.commit()
        db.close()
        return jsonify({"ok": True, "msg": "Updated"})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/insert", methods=["POST"])
def api_db_insert():
    data = request.get_json(force=True)
    table = data.get("table", "")
    values = data.get("data", {})
    if not table or not values:
        return jsonify({"ok": False, "msg": "table and data required"})
    try:
        db = get_db()
        with db.cursor() as cur:
            cols = ", ".join(f"`{k}`" for k in values)
            placeholders = ", ".join("%s" for _ in values)
            sql = f"INSERT INTO `{table}` ({cols}) VALUES ({placeholders})"
            cur.execute(sql, list(values.values()))
            db.commit()
        db.close()
        return jsonify({"ok": True, "msg": "Inserted"})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/delete", methods=["POST"])
def api_db_delete():
    data = request.get_json(force=True)
    table = data.get("table", "")
    pk = data.get("pk", {})
    if not table or not pk:
        return jsonify({"ok": False, "msg": "table and pk required"})
    try:
        db = get_db()
        with db.cursor() as cur:
            where_clause = " AND ".join(
                (f"`{k}` = %s" if v is not None else f"`{k}` IS NULL") for k, v in pk.items()
            )
            params = [v for v in pk.values() if v is not None]
            sql = f"DELETE FROM `{table}` WHERE {where_clause} LIMIT 1"
            cur.execute(sql, params)
            db.commit()
        db.close()
        return jsonify({"ok": True, "msg": "Deleted"})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/tree")
def api_db_tree():
    try:
        cfg = read_config()
        db_name = cfg.get("dbname", "acdts3")
        db = get_db()
        with db.cursor() as cur:
            cur.execute("SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s ORDER BY TABLE_NAME", (db_name,))
            tables = [{"name": r["TABLE_NAME"], "rows": r["TABLE_ROWS"] or 0} for r in cur.fetchall()]
        db.close()
        return jsonify({"ok": True, "database": db_name, "tables": tables})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


@app.route("/api/db/sql", methods=["POST"])
def api_db_sql():
    data = request.get_json(force=True)
    sql = (data.get("sql") or "").strip()
    if not sql:
        return jsonify({"ok": False, "msg": "empty SQL"})

    # only allow SELECT for safety unless query starts with certain safe words
    upper = sql.upper()
    if not (upper.startswith("SELECT") or upper.startswith("SHOW")
            or upper.startswith("DESCRIBE") or upper.startswith("EXPLAIN")):
        return jsonify({"ok": False, "msg": "Only SELECT/SHOW/DESCRIBE/EXPLAIN allowed"})

    try:
        db = get_db()
        with db.cursor() as cur:
            cur.execute(sql)
            rows = cur.fetchall()
            cols = [d[0] for d in cur.description] if cur.description else []
        db.close()
        return jsonify({"ok": True, "columns": cols, "rows": rows, "rowcount": len(rows)})
    except Exception as e:
        return jsonify({"ok": False, "msg": str(e)})


# ── file browser ─────────────────────────────────────────

@app.route("/api/files")
def api_files():
    subpath = request.args.get("path", "")
    target = (GAME_ROOT / subpath).resolve()
    if not str(target).startswith(str(GAME_ROOT)):
        return jsonify({"ok": False, "msg": "Access denied"}), 403
    if not target.exists():
        return jsonify({"ok": False, "msg": "Path not found"}), 404

    if target.is_file():
        try:
            content = target.read_text(encoding="utf-8", errors="ignore")
            return jsonify({
                "ok": True, "type": "file",
                "name": target.name, "path": subpath,
                "size": target.stat().st_size,
                "content": content[:100000],
            })
        except Exception as e:
            return jsonify({"ok": False, "msg": str(e)})

    items = []
    skip = {".git", "__pycache__", ".cursor", "BRLOGO.jpg", "favicon.ico",
            ".gitattributes", ".gitignore", "manager"}
    try:
        for entry in sorted(target.iterdir(), key=lambda x: (not x.is_dir(), x.name.lower())):
            if entry.name in skip or entry.name.startswith("."):
                continue
            rel = str(entry.relative_to(GAME_ROOT)).replace("\\", "/")
            try:
                st = entry.stat()
                sz = st.st_size if entry.is_file() else 0
                mt = datetime.fromtimestamp(st.st_mtime).strftime("%Y-%m-%d %H:%M")
            except OSError:
                sz = 0; mt = ""
            items.append({
                "name": entry.name, "path": rel,
                "is_dir": entry.is_dir(), "size": sz, "mtime": mt,
            })
    except PermissionError:
        pass

    return jsonify({"ok": True, "type": "dir", "path": subpath or "/", "items": items})


# ── main ─────────────────────────────────────────────────

if __name__ == "__main__":
    print(f"phpdts Manager → http://{GAME_HOST}:{MANAGER_PORT}")
    app.run(host=GAME_HOST, port=MANAGER_PORT, debug=False)
