FROM nginx

COPY servers/nginx /etc/nginx/conf.d/

CMD envsubst '$VHOST $VHOST_PORT $API_HOST $API_PORT $APPLICATION_ENV \
    $VHOST_NODE $VHOST_NODE_PORT $NODE_HOST $NODE_PORT $VHOST_GUI' < \
    /etc/nginx/conf.d/chaplin.template > /etc/nginx/conf.d/default.conf && \
    nginx -g 'daemon off;'
