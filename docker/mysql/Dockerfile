FROM mysql:5.6

ENV MYSQL_ROOT_PASSWORD=password
COPY /sql/init.sql /docker-entrypoint-initdb.d/

EXPOSE 3306