# Stage 1: Base Nginx image
FROM nginx:alpine

# Copy custom nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Copy frontend source code
COPY . /usr/share/nginx/html

# Expose port
EXPOSE 80