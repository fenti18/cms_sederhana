# CMS Sederhana

A simple Content Management System built with PHP and AdminLTE.

## Features

- User authentication
- Post management (CRUD)
- Category management
- User management
- Responsive AdminLTE interface
- DataTables integration

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (for dependencies)

## Installation

1. Clone this repository to your web server directory:
```bash
git clone https://github.com/yourusername/cms_sederhana.git
cd cms_sederhana
```

2. Create a new MySQL database and import the database structure:
```bash
mysql -u root -p < database.sql
```

3. Configure the database connection in `config/database.php`:
```php
$host = 'localhost';
$dbname = 'cms_sederhana';
$username = 'your_username';
$password = 'your_password';
```

4. Install AdminLTE dependencies:
   - Download AdminLTE from https://adminlte.io/
   - Extract the contents to the project directory
   - Make sure the following directories exist:
     - `plugins/`
     - `dist/`

5. Set proper permissions:
```bash
chmod 755 -R .
chmod 777 -R uploads/
```

## Default Login

- Username: admin
- Password: admin123

## Directory Structure

```
cms_sederhana/
├── config/
│   ├── database.php
│   └── functions.php
├── includes/
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
├── pages/
│   ├── dashboard.php
│   ├── posts.php
│   ├── categories.php
│   └── users.php
├── plugins/
├── dist/
├── index.php
├── login.php
├── logout.php
└── database.sql
```

## Security

- All user inputs are sanitized
- Passwords are hashed using PHP's password_hash()
- SQL injection prevention using prepared statements
- XSS prevention using htmlspecialchars()

## License

This project is licensed under the MIT License - see the LICENSE file for details. 