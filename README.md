# 常磐大逃杀 / ACFUN Battle Royale

A PHP-based browser game emulating the Battle Royale genre. Players compete in a deathmatch arena with weapons, items, skills, and PvP combat. Features include room-based matches, a fantasy side-world (幻想世界), replay recording, and a full administration panel.

> **Version:** GE942 ～TORONTO  
> **Tech:** PHP 7.x + MySQL 5.7+ / 8.x + vanilla JavaScript

---

## Quick Start

### Option 1: Docker

```bash
git clone https://github.com/amarillonmc/phpdts.git
cd phpdts
docker-compose up -d
```

This starts PHP-FPM, Nginx on **:8080**, MariaDB on **:3306**, and phpMyAdmin on **:8081**.

Then open `http://localhost:8080/install.php` and follow the installation wizard.

### Option 2: Manual Setup

**Requirements:**
- PHP 7.3+ (tested up to 8.2)
- MySQL 5.7+ / MariaDB 10+
- PHP extensions: `mysqli` or `pdo_mysql`, `mbstring`, `json`, `session`, `curl`

```bash
# 1. Clone
git clone https://github.com/amarillonmc/phpdts.git
cd phpdts

# 2. Copy and edit config
cp config.inc.php.sample config.inc.php
# Edit: set $dbhost, $dbuser, $dbpw, $dbname, $gamefounder

# 3. Start MySQL (if not running)
mysqld --defaults-file=/path/to/my.ini &

# 4. Start PHP dev server
php -S localhost:8080 -t .

# 5. Open browser → install
# http://localhost:8080/install.php
```

The installer will create all database tables and set up the initial admin account. After installation, delete `install.php` and `install/` for security.

### Option 3: Windows with phpStudy

If using [phpStudy](https://www.xp.cn/) (recommended for Windows):

1. Place the project folder under phpStudy's `WWW/` directory
2. Use phpStudy panel to start MySQL 8.0
3. Open `http://localhost:8080/install.php` in browser
4. Or use the built-in PHP dev server:
   ```
   php -S localhost:8080 -t .
   ```

---

## Database Setup

The project ships with complete SQL schema files:

| File | Purpose |
|------|---------|
| `gamedata/sql/all.sql` | Complete schema (all 25+ tables) |
| `gamedata/sql/players.sql` | Player table only |
| `install/bra.sql` | Minimal install schema |

**Quick import** (skip the web installer):
```bash
mysql -u root -p acdts3 < gamedata/sql/all.sql
mysql -u root -p acdts3 -e "INSERT INTO bra_game (gamenum, gamevars) VALUES (0, '');"
```

> **Note:** MySQL 8.0 strict mode may require `SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION';` before import.

---

## Configuration

### `config.inc.php` — Main Configuration

| Variable | Description | Default |
|----------|-------------|---------|
| `$dbhost` | Database host | `localhost` |
| `$dbuser` | Database user | `root` |
| `$dbpw` | Database password | *(empty)* |
| `$dbname` | Database name | `acdts3` |
| `$tablepre` | Table prefix | `bra_` |
| `$database` | DB driver (`mysqli` / `pdo` / `mysql`) | `mysqli` |
| `$gamefounder` | Super admin username(s), `\|` separated | `Amarillo_NMC` |
| `$gameurl` | Game URL | `http://localhost:8080/` |
| `$title` | Game title | `大 逃 杀` |
| `$moveut` | Timezone offset (hours from UTC) | `8` |

### Admin Privileges

After registration, grant admin via database:
```sql
UPDATE bra_users SET groupid = 9 WHERE username = 'your_name';
```

| groupid | Level |
|---------|-------|
| ≤0 | Banned |
| 1 | Normal player (default) |
| 2+ | Admin (access admin panel) |
| 6+ | Can operate during active games |
| 9 | Can manage other admins |
| `$gamefounder` | Super admin (always full access) |

---

## Management Panel (Python)

The project includes a Python Flask-based management dashboard for service control, database browsing with inline editing, SQL console, and file browsing.

```bash
# Install Python dependencies
pip install flask pymysql

# Start the manager
python manager/app.py
# Opens at http://localhost:5099/

# Or use the convenience scripts
manager/start.bat   # Start all services (Windows)
manager/stop.bat    # Stop all services (Windows)
```

**Features:**
- **Dashboard** — One-click start/stop for PHP + MySQL, service status, version info
- **Database** — Tree-view table browser, double-click to edit cells inline, save/undo/delete per row
- **SQL Console** — Write and execute SELECT queries, `Ctrl+Enter` to run
- **Files** — Browse project files with content preview
- **Theme** — Dark/light mode toggle

---

## Project Structure

```
phpdts/
├── index.php              # Home page / room lobby
├── game.php               # Main game interface
├── admin.php              # Administration panel
├── register.php           # Account registration
├── login.php              # Login
├── valid.php              # Game command processor
├── api.php                # AJAX API endpoints
├── command.php            # Command definitions
├── alive.php              # Alive players list
├── winner.php             # Winner history
├── rank.php               # Player rankings
├── map.php                # Battlefield map
├── news.php               # Game news/progress
├── help.php               # Game help/manual
│
├── config.inc.php         # Database & game configuration
├── config.inc.php.sample  # Configuration template
│
├── include/               # Core PHP libraries
│   ├── common.inc.php     # Bootstrap (loaded by every page)
│   ├── global.func.php    # Global utility functions
│   ├── game.func.php      # Core game mechanics
│   ├── state.func.php     # Game state machine
│   ├── system.func.php    # System operations
│   ├── db_mysqli.class.php # MySQLi database driver
│   ├── db_pdo.class.php   # PDO database driver
│   ├── db_mysql.class.php # Legacy MySQL driver
│   ├── game/              # Game logic sub-modules
│   └── admin/             # Admin panel functions
│
├── gamedata/              # Runtime data & configs
│   ├── sql/               # SQL schema files
│   ├── cache/             # Cached configuration files
│   ├── gameinfo.php       # Game state (round number, timing)
│   ├── system.php         # System settings
│   ├── resources.php      # Item/equipment definitions
│   └── templates/         # Compiled template cache
│
├── templates/             # Frontend templates
│   ├── default/           # Default theme
│   └── nouveau/           # Alternative theme
│
├── bot/                   # Bot daemon for NPCs & automation
├── img/                   # Static images
├── install/               # Installation wizard
├── manager/               # Python management dashboard
├── nginx/                 # Nginx config templates
├── doc/                   # Documentation & changelogs
│
├── Dockerfile             # PHP-FPM container
└── docker-compose.yml     # Full stack (PHP + Nginx + MariaDB)
```

---

## Bot Daemon

The bot daemon manages NPC players and automated game events:

```bash
# Linux / macOS
bash ./bot/bot_enable.sh
nohup bash ./bot/bot_enable.sh &   # Run in background

# Windows
# Use the PHP CLI directly:
php bot/bot_main.php
```

---

## Nginx Configuration

For production deployment, use the config in `nginx/` directory. Key points:
- Root must be the project directory
- PHP routing uses the built-in `.php` entry points
- Static files served directly

Example nginx config is provided in `nginx/default.conf`.

---

## Troubleshooting

### "Table doesn't exist" errors
The install SQL only creates core tables. Import the full schema:
```bash
mysql -u root -p dbname < gamedata/sql/all.sql
```

### MySQL 8.0 import errors
Old SQL syntax (`TYPE=MyISAM`) is not supported. Fix with:
```bash
sed -e 's/) TYPE=/ ) ENGINE=/g' all.sql > all_fixed.sql
mysql -u root -p dbname < all_fixed.sql
```

### PHP 8.x compatibility
The codebase is tested on PHP 7.3–8.2. If you encounter deprecation warnings on PHP 8.1+, set:
```ini
error_reporting = E_ALL & ~E_DEPRECATED
```

### Cannot connect to database
Check that `config.inc.php` exists and has correct credentials. Copy from sample:
```bash
cp config.inc.php.sample config.inc.php
```

### Port already in use
The PHP dev server defaults to port 8080. Change with:
```bash
php -S localhost:8081 -t .
```
Then update `$gameurl` in `config.inc.php` accordingly.

---

## Development

See [AGENTS.md](AGENTS.md) for detailed coding conventions, patterns, and API documentation.

- **PHP syntax check:** `php -l filename.php`
- **Config regeneration:** `composer dump-autoload` (if using composer)
- **Database driver:** Configurable via `$database` — supports `mysqli`, `pdo`, `mysql`
- **Template system:** Custom `{var}` syntax in `.htm` files

---

## Credits

Based on the original **Bra** engine by [loongyou.com](http://loongyou.com).  
Developed and maintained by the ACFUN Battle Royale community.

---

## License

This project is open source. See [LICENSE](LICENSE) for details.
