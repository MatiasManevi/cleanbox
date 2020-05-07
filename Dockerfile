
# FROM ubuntu:17.10
FROM byrnedo/nginx-php7-fpm

# ENV DEBIAN_FRONTEND noninteractive

# RUN apt-get update -y
# RUN apt-get install software-properties-common --no-install-recommends -y
# RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

# RUN apt-get update -y

# RUN apt-get install --no-install-recommends -y \
# python \
# build-essential \
# git \
# ca-certificates \
# curl \
# mcrypt \
# php7.1 \
# php7.1-mbstring \
# php7.1-mysql \
# php7.1-xml \
# php7.1-intl \
# php7.1-mbstring  \
# php7.1-cli \
# php7.1-gd \
# php7.1-curl \
# php7.1-sqlite3 \
# php7.1-fpm \
# php7.1-zip \
# php7.1-soap \
# nginx \
# supervisor

# Composer
# RUN curl -sS https://getcomposer.org/installer | php
# RUN mv composer.phar /usr/local/bin/composer

# NodeJS + bower + grunt
# RUN mkdir /nodejs && curl http://nodejs.org/dist/v0.12.2/node-v0.12.2-linux-x64.tar.gz | tar xvzf - -C /nodejs --strip-components=1
# ENV PATH $PATH:/nodejs/bin
# RUN npm install -g bower grunt-cli

#RUN apt-get install --no-install-recommends -y ruby 
#RUN gem install sass

# Allow shell for www-data (to make composer commands)
RUN sed -i -e 's/\/var\/www:\/usr\/sbin\/nologin/\/var\/www:\/bin\/bash/' /etc/passwd

# UMASK par defaut
RUN sed -i -e 's/^UMASK *[0-9]*.*/UMASK    002/' /etc/login.defs
RUN sed -i -e '/^ENV_PATH/ s/$/:\/nodejs\/bin/' /etc/login.defs

# CONF PHP-FPM
RUN sed -i "s/^listen\s*=.*$/listen = 127.0.0.1:9000/" /etc/php/7.1/fpm/pool.d/www.conf && \
	echo 'php_flag[display_errors] = on' >> /etc/php/7.1/fpm/pool.d/www.conf && \
	echo 'php_admin_value[error_log] = /var/log/fpm-php.www.log'  >> /etc/php/7.1/fpm/pool.d/www.conf && \
	echo 'php_admin_flag[log_errors] = on'   >> /etc/php/7.1/fpm/pool.d/www.conf

RUN sed -i "s/display_errors = .*/display_errors = stderr/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/memory_limit = .*/memory_limit = 2048M/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/;date.timezone.*/date.timezone = America\/Argentina\/Buenos_Aires/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/max_execution_time = .*/max_execution_time = 300/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/max_input_time = .*/max_input_time = 300/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/post_max_size = .*/post_max_size = 32M/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/upload_max_filesize = .*/upload_max_filesize = 32M/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/;cgi.fix_pathinfo.*/cgi.fix_pathinfo=0/" /etc/php/7.1/fpm/php.ini

# CONF PHP-CLI
RUN sed -i "s/;date.timezone.*/date.timezone = America\/Argentina\/Buenos_Aires/" /etc/php/7.1/cli/php.ini

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 5000

# SUPERVISOR
ADD supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Script pour démarrer session shell www-data
#ADD start-www-data-session.sh /www-data.sh

RUN mkdir -p /run/php
WORKDIR /var/www

#RUN chown -R www-data:www-data /var/www/

#ADD composer-install.sh .
#CMD ./composer-install.sh

CMD ["/usr/bin/supervisord", "--nodaemon", "-c", "/etc/supervisor/supervisord.conf"]
