FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP=1000
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html/collection_service_app

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# -------------------------------------------------
# System dependencies
# -------------------------------------------------
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && apt-get update \
    && apt-get install -y \
        gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 \
        libcap2-bin libpng-dev python2 dnsutils librsvg2-bin \
    # PHP repository
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' \
        | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" \
        > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    # PHP 8.3 and extensions
    && apt-get install -y \
        php8.3-cli php8.3-dev \
        php8.3-pgsql php8.3-sqlite3 php8.3-gd php8.3-imagick \
        php8.3-curl php8.3-imap php8.3-mysql php8.3-mbstring \
        php8.3-xml php8.3-zip php8.3-bcmath php8.3-soap \
        php8.3-intl php8.3-readline php8.3-ldap \
        php8.3-msgpack php8.3-igbinary php8.3-redis php8.3-swoole \
        php8.3-memcached php8.3-pcov php8.3-xdebug \
    # Composer
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    # Node & Yarn
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" \
        > /etc/apt/sources.list.d/yarn.list \
    # Postgres client
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" \
        > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn mysql-client postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.3



RUN groupadd --force -g $WWWGROUP sail2
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1338 sail2

COPY ./server/docker/start-container /usr/local/bin/start-container
COPY ./server/docker/supervisord2.conf /etc/supervisor/conf.d/supervisord2.conf
COPY ./server/docker/php.ini /etc/php/8.3/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

# -------------------------------------------------
# Dependencies first (better caching)
# -------------------------------------------------
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader

# -------------------------------------------------
# Copy application files
# -------------------------------------------------
COPY . .

# Permissions (only writable dirs)
RUN chmod -R 777 storage bootstrap

EXPOSE 80

ENTRYPOINT ["start-container"]
