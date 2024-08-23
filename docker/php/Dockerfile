FROM php:8.3-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    ghostscript \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN apt-get update && apt-get install -y \
    libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/* \
    && pecl install imagick \
	&& docker-php-ext-enable imagick


RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

CMD ["php-fpm"]