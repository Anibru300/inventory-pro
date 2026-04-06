FROM nginx:alpine

# Remove default config
RUN rm /etc/nginx/conf.d/default.conf

# Copy custom config
COPY default.conf /etc/nginx/conf.d/

# Create cache directories
RUN mkdir -p /var/cache/nginx

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]