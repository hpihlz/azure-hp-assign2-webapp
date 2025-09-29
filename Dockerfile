FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx supervisor curl \
  && docker-php-ext-install pdo pdo_mysql

# Non-root user and runtime dirs
RUN adduser -D -H -u 1000 app \
  && mkdir -p /run/nginx /var/log/supervisor /run/php \
  && chown -R app:app /run/nginx /var/log/supervisor /run/php

# Configs
COPY deploy/nginx/default.conf /etc/nginx/http.d/default.conf
COPY deploy/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY deploy/supervisor/supervisord.conf /etc/supervisord.conf

# App files
COPY app/ /var/www/html/
RUN chown -R app:app /var/www/html \
  && find /var/www/html -type d -exec chmod 0755 {} \; \
  && find /var/www/html -type f -exec chmod 0644 {} \;

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s CMD curl -fsS http://127.0.0.1:8080/ || exit 1

USER app
CMD ["/usr/bin/supervisord","-c","/etc/supervisord.conf"]
