# KrazePlanetLabs

## Setup Locally
```
1. Install xampp
2. clone this repo in C:\xampp\htdocs

sudo sed -i 's/Require local/Require all granted/g' /opt/lampp/etc/extra/httpd-xampp.conf

mysql -u root --socket=/opt/lampp/var/mysql/mysql.sock -e "DROP DATABASE IF EXISTS KrazePlanetLabs_DB; CREATE DATABASE KrazePlanetLabs_DB;"

mysql -u root --socket=/opt/lampp/var/mysql/mysql.sock KrazePlanetLabs_DB < <(cat /opt/lampp/htdocs/KrazePlanetLabs/sqli/*/*.sql)
```

 ## Website UI
<img width="1861" height="909" alt="image" src="https://github.com/user-attachments/assets/3e035dcb-f7e1-4b46-904a-3aeb74b49456" />
