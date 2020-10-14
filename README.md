Installation:

1. Create a new folder and create a new file called docker-compose.yml
2. Paste the following inside the yml file:
```
version: '2'

services:
  gis:
    image: 'docker.io/dvelopate/gis-api:dev'
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
3. open your terminal in the project folder and run: docker-compose up
  - for some reason terminal is not released at the following output so just CTRL + C to make a graceful stop:
  ```
    gis_1      | INFO  ==> Starting gosu...
  ```
4. ``` cd gis ```
5. git clone git@github.com:dvelopate/gis.git
6. chown -R 
