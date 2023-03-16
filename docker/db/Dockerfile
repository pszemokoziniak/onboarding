FROM mariadb:10.1
# All scripts in docker-entrypoint-initdb.d/ are automatically
# executed during container startup
 
COPY ./scripts/*.* /docker-entrypoint-initdb.d/
 