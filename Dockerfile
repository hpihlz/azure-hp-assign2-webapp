# Dockerfile
FROM nginx:alpine

# Replace default site with a minimal one
RUN rm -f /etc/nginx/conf.d/default.conf && \
    printf '%s\n' \
    "server {" \
    "  listen 80;" \
    "  server_name _;" \
    "  root /usr/share/nginx/html;" \
    "  index index.html;" \
    "  location / { try_files \$uri /index.html; }" \
    "}" \
    > /etc/nginx/conf.d/site.conf

# Copy your static files
COPY html/ /usr/share/nginx/html/

EXPOSE 80

# Healthcheck (optional)
HEALTHCHECK --interval=30s --timeout=3s CMD wget -q -O - http://127.0.0.1:80/ || exit 1
