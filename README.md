# Welcome to LaraTube (WIP)

This project is a multimedia-plattform for various scenarios, primary for video and audio (via upload, link and webtorrent).

The project is completly licensed with the GPL v3.

However, it's whished on the developers side to not use this software for:

- Porn (this industry have enough money)
- Illegal or even discracing violence
- Pro army / police / fashist / racist-sites

By license, you can use the software for anything. If i (the developer) detect this on the bugtracker or in personal contact, it can lead to deny personal help or loosing contact - this is my right.

## Features

- Various audio- and video-types (including webtorrent)
- Written as one-page-app in Vue (no reloads)
- Visualisation for localAudio
- Charts
- Categories including sub-categories (infinite)
- Comments including sub-comments (infinite), including likes
- Notifications
- Autoplay
- Tags
- Various filters (type, search, tags)
- Live crop/rotate/"edit" for uploading images
- Markdown everywhere
- Media-bookmark with offline localStorage
- Intro/Outro-support (not finished yet)

## TODO-features

## Wanted features

Things i would love to implement, but where i'm either not shure how to implement it or simply doesn't have prioritised it yet.

- ActivityPub!
- More media-types (half under TODO)
  - PDF, EPUB
  - Pictures
- Breakless click through a package of various medias (like a playlist, but presentation-able)

## Requirements

Basicly extact the same as laravel

- PHP >= 7.1.3.
- OpenSSL PHP Extension.
- PDO PHP Extension.
- Mbstring PHP Extension.
- Tokenizer PHP Extension.
- XML PHP Extension.
- Ctype PHP Extension.
- JSON PHP Extension.

## Quick install

Best practice is to git clone the project. After, go into the project-directory, to run the following commands.

For debian before composer..

    apt-get install php7.3-xml php7.3-mbstring

For get all dependencies

    composer install

For secure authenification

    php artisan key:generate

Create DB

    php artisan migrate

For generate first data in the db

    php artisan db:seed -v

Make a storage-link (if avatars and uploads don't work...)

    ln -s /your-path/storage/app/public /your-path/public/

Result should be a browseable "public/public/" - if you are confused about, this is OK!    

For oauth2 (unused yet)

    php artisan passport:install
