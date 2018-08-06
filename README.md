# Welcome to LaraTube

This project aims to be a multimedia-plattform for various scenarios, primary for video and audio.

The project is completly licensed with the GPL v3.

However, it's whished on the developers side to not use this software for:

- Porn (this industry have enough money)
- Illegal or even discracing violence
- Pro army / police / fashist / racist-sites

By license, you can use the software. If i (the developer) detect this on the bugtracker or in personal contact, it can lead to deny personal help or loosing contact - this is my right.

Since this project has the idea to cover my freelancer-existence commercialy, there are two points:

- The project's code is free and using a widly used framework, making it easy to install
- Concrete docs are free as well, so you can install it yourself, with a little knowledge about linux and server-admin-related stuff
- If SQL, PHP, Apache 2 and Nginx are absolute a new thing for you, there are tons of free tutorials. You can find them with most search-engines. But remember: system-administrator can be a full job.
- If you don't like to learn all of those things, you can hire me and i can help you quite quick. If you have no expirience, this can be usefull for the begin. This is the first commercial.
- If you need specific customations which are not possible in the core or a function that is allowed to go opensource, but you need it very fast, you can ask me too, i can do it as a job.

The point is: The project is free, but any issues about "how to install mysql", "what's ssh" and similiar unrelated stuff will be closed imidietly. It's possible to do the setup yourself and you can contact me for hire.

Phu - enough of that, but this was what hunted me in the last project and a partial reason for start this.

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

For secure authenification

    php artisan key:generate

For generate first data in the db

    php artisan db:seed

For oauth2

    php artisan passport:install
