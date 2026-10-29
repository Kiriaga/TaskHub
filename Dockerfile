FROM php:8.2-apache

# Copia todos los archivos de tu proyecto a la carpeta pública del servidor web
COPY . /var/www/html/

# Exponer el puerto 80 para que sea accesible en la web
EXPOSE 80
