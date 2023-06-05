FROM nginx:alpine
COPY docker/performance-dev_nginx/nginx.conf /etc/nginx/nginx.conf
COPY assets/ /var/www/html/assets/
COPY js/ /var/www/html/js/
COPY php/ /var/www/html/php/
COPY index.html /var/www/html/index.html
COPY docker/performance-dev_php/mongo_connect.php /var/www/html/php/mongo_connect.php
COPY docker/performance-dev_php/helloworld.php /var/www/html/php/helloworld.php


