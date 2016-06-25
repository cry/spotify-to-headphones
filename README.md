# Spotify → Headphones

[![Build Status](https://travis-ci.org/carey-li/spotify-to-headphones.svg?branch=develop)](https://travis-ci.org/carey-li/spotify-to-headphones)

#### This project is in very early development. Expect bugs.
#### As of now, this must be placed at the root web directory, i.e. https://s2h.domain/ or https://localhost:3300/

Spotify to Headphones allows you to view all of your spotify playlists and send albums directly to Headphones for download, all in one place.

![Banner](https://carey.li/s2h_banner.png?cache=1)

## Features

- Load songs from Spotify playlists and send them to Headphones
- Play 30 second preview directly
- Show which songs have been queued for download

## Setup

*Requirements*: PHP 5.5+, sqlite3 extension

- Clone this repo, i.e. `git clone https://github.com/carey-li/spotify-to-headphones.git`
- Place the files in the webroot, i.e. `/var/www`, `/srv`
- Run `composer install` to grab dependencies, install composer from [here](https://getcomposer.org/download/) if you don't have it.
- Modify your webserver config to enable directory rewriting, i.e.

~~~

        location / {
            try_files $uri $uri/ /index.php?$args;
        }

~~~

- Ensure the directory is writable by the webserver user, i.e. `www-data`
    - `chown -R www-data:www-data /srv/s2h`

## Usage

- Navigate to the webroot, S2H will run through a first run

