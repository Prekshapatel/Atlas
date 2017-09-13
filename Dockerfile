FROM php:5.6-apache

RUN a2enmod rewrite proxy proxy_http proxy_html headers

ENV http_proxy http://awslprxp01.casino.internal:3128/
ENV https_proxy http://awslprxp01.casino.internal:3128/

# install the PHP extensions we need
RUN apt-get update && apt-get install -y libpng12-dev libjpeg-dev libpq-dev libmcrypt-dev zlib1g-dev openssh-server supervisor vim mysql-client libmemcached-dev memcached git cntlm \
  && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
  && docker-php-ext-install mcrypt gd mbstring pdo pdo_mysql pdo_pgsql zip sockets mysqli

RUN pecl install igbinary \
  && pecl install memcached-2.2.0 \
  && pecl install xdebug \
  && docker-php-ext-enable memcached xdebug

# Install needed php extensions: ldap
RUN \
  apt-get update && \
  apt-get install libldap2-dev -y && \
  rm -rf /var/lib/apt/lists/* && \
  docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
  docker-php-ext-install ldap

RUN mkdir -p /var/lock/apache2 /var/run/memcached /var/run/apache2 /var/run/sshd /var/log/supervisor

# install nodejs
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - && apt-get install -y nodejs

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www

RUN pear config-set http_proxy "${http_proxy}"

RUN mkdir -p /var/www/html

# Add drupal and set permissions
RUN useradd drupal -m -d /var/www/sites -s /bin/bash \
  && usermod -u 130515 drupal && groupmod -g 130515 drupal \
  && usermod -u 48 www-data   && groupmod -g 48 www-data   \
  && usermod -a -G www-data drupal \
  && chown -R drupal:drupal /var/www/sites

# drupal bashrc
COPY ./_bashrc /var/www/sites/.bashrc
RUN chown drupal:drupal /var/www/sites/.bashrc

# bashrc
COPY ./_bashrc /root/.bashrc

# ssh
RUN mkdir /root/.ssh
COPY ./_ssh/id_rsa /root/.ssh/
COPY ./_ssh/authorized_keys /root/.ssh/
COPY ./_ssh/config /root/.ssh/
RUN chmod 400 /root/.ssh/*

RUN mkdir -p /var/www/sites/.ssh
COPY ./_ssh/authorized_keys /var/www/sites/.ssh/
COPY ./_ssh/config /var/www/sites/.ssh/
RUN chown -R drupal:drupal /var/www/sites/.ssh

# config files
COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./config/php.ini /usr/local/etc/php/conf.d
COPY ./config/envvars /etc/apache2/

# PHP MY ADMIN

RUN mkdir /var/www/log

ENV COMPOSER_ALLOW_SUPERUSER 1

# composer
RUN cd /tmp && \
       curl -s https://getcomposer.org/installer > composer-setup.php && \
       php composer-setup.php && \
       php -r "unlink('composer-setup.php');" && \
       mv /tmp/composer.phar /usr/local/bin/composer

# composer path
ENV PATH=$PATH:/root/.composer/vendor/bin

#RUN npm config set proxy $http_proxy && npm config set https-proxy $http_proxy && npm install yarn -g
RUN npm install yarn -g
RUN composer global require drush/drush

CMD /usr/bin/supervisord
