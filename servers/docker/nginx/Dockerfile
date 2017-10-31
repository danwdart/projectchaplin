FROM nginx

COPY servers/nginx/chaplin.template /etc/nginx/conf.d/

CMD envsubst < /etc/nginx/conf.d/chaplin.template > /etc/nginx/conf.d/default.conf && nginx -g 'daemon off
