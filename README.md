Installation:

1. Create a new folder and create a new file called docker-compose.yml

2. Paste the following inside the yml file:
```
version: '2'

services:
  gis:
    image: 'bitnami/symfony:1'
    ports:
      - '8000:8000'
    volumes:
      - '.:/app'
    environment:
      - SYMFONY_PROJECT_NAME=gis
      - MARIADB_HOST=mariadb
      - MARIADB_PORT_NUMBER=3306
      - MARIADB_USER=guest
      - MARIADB_PASSWORD=guest
      - MARIADB_DATABASE=gis
    depends_on:
      - mariadb
  mariadb:
    image: 'docker.io/bitnami/mariadb:10.3-debian-10'
    environment:
      - ALLOW_EMPTY_PASSWORD=yes
      - MARIADB_USER=guest
      - MARIADB_PASSWORD=guest
      - MARIADB_DATABASE=gis
```
3. open your terminal in the project folder and run: ```docker-compose up```
  - for some reason terminal is not released at the following output so just CTRL + C to make a graceful stop (bad 3p dockerfile probably):
  ```
    gis_1      | INFO  ==> Starting gosu...
  ```
  
4. ```sudo rm -R gis``` because the containter has a default application structure

5. ```git clone git@github.com:dvelopate/gis.git```

6. ```docker start <folder_name>_gis_1 <folder_name>_mariadb_1```

7. ```docker exec -it <folder_name>_gis_1 bash```

8. Once inside the container, start the install script for container dependencies
  ```
  cd gis
  ./install.sh
  ```
  
9. ```crontab -e```
  Paste this inside and save:
```
*/4 * * * * /opt/bitnami/php/bin/php /app/gis/bin/console app:sync-users >> /app/gis/var/log/app-sync-users.log 2>&1
```

10. ```service cron start```

11. Wait for 4 minutes in order to see synced data on one of application's routes

Usage:

At localhost:8000 you can find 3 routes:
```
  /user
  /post
  /post/user
```
You can pass an integer as the argument (eg. /user/1) to get a specific user. 
This is true for all 3 routes except that /post/user requires an integer as an argument

All routes also support sorting by their attributes.
You can sort by adding ```?sort={attribute}&direction={asc,desc}```
It's a basic implementation so by not complying with this query string (add both parameters), you'll get the default sort.
