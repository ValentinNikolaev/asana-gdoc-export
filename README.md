![GitHub last commit](https://img.shields.io/github/last-commit/ValentinNikolaev/asana-gdoc-export)
![visitors](https://visitor-badge.laobi.icu/badge?page_id=ValentinNikolaev.asana-gdoc-export)

# Notes
Master branch is for test only. Check v2 branch for updates.

# ToDo
Replace with Guzzle


# Usage
1. Composer install
2. Copy config-sample.php to config.php. Make needed changes
3. Chande permissions at credentials and tmp folders to 777
4. Run connect.php from browser. Connect your google account with script
5. Run cli.php via Cli.
6. Run doc_list.php from browser to get latest report list

# Connect another account
1. Run php cli.php -d from Cli
2. Run connect.php from browser.

# Cli options

Usage: cli.php [options] [operands]

Options:

  -r, --refresh_token     Refresh token if exists
  
  -d, --remove            Remove credentials to authorize new user
  
  -v, --version           Display version information

# Send drafts
1. Run doc_list.php from browser to get latest report list

