# Meetzy Base - PHP Backend
FROM ### as meetzy_base

USER meetzy

# Copy the entire project (including artisan)
COPY --chown=meetzy:meetzy . .

RUN cp .env.staging .env

# Install PHP dependencies and ensure vendor is created
RUN composer install --prefer-dist && ls -la vendor

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf", "-n"]

# Meetzy Frontend - Node-based Web Frontend
FROM ### as meetzy_frontend

COPY --from=meetzy_base /opt/meetzy /opt/meetzy

WORKDIR /opt/meetzy

# Install Node dependencies and ensure node_modules is created
RUN npm install && npm run build && ls -la node_modules

# Backend Image (Final Stage)
FROM meetzy_base as meetzy_backend

COPY --chown=meetzy:meetzy .docker/supervisor/ /etc/supervisor/

USER meetzy

COPY --from=meetzy_base --chown=meetzy:meetzy /opt/meetzy /opt/meetzy
COPY --from=meetzy_frontend --chown=meetzy:meetzy /opt/meetzy/public /opt/meetzy/public
COPY --from=meetzy_frontend --chown=meetzy:meetzy /opt/meetzy/node_modules /opt/meetzy/node_modules
COPY --from=composer:latest --chown=meetzy:meetzy /usr/bin/composer /usr/local/bin/composer

RUN php artisan event:cache && php artisan route:cache && php artisan view:cache

# Web Server Image (Final Stage)
FROM nginx:stable-alpine as meetzy_web_server

WORKDIR /opt/meetzy

COPY .docker/nginx/default.conf.template /etc/nginx/templates/default.conf.template

COPY --from=meetzy_frontend /opt/meetzy/public /opt/meetzy/public
