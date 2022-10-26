# Important notice
I did not find too much time and interest to continue much on this software. Right now, I'm working with different frameworks and will not necessarely end-up in a video-plattform.

Of course, anyone can fork and continue with the project. The general security-flows should be ok, but I write this notice because Github alerted me about library-vulnerabilities.

Therefore, be aware that there can vulnerabilities come up! I will update them this time, but the project does not get the needed attention.

There's however a usecase which can make sense: for internal systems. I would need to write a way to import medias with a command. Could be interesting as a short task - feel free to do a PR/MR. 

# Welcome to LaraTube (WIP)

In general, this project is on Gitlab: https://gitlab.com/hersche/LaraTube

This project is a multimedia-plattform for various scenarios, primary for video and audio (via upload, link and webtorrent).

The project is completly licensed with the GPL v3.

However, it's whished on the developers side to not use this software for:

- Porn (this industry have enough money)
- Illegal or even discracing violence
- Pro army / police / fashist / racist-sites

By license, you can use the software for anything. If i (the developer) detect this on the bugtracker or in personal contact, it can lead to deny personal help or loosing contact - this is my right.
(this step was motivated by the prior project)

## Documentation

Manuals for users, admins and devs can be found here: https://gitlab.com/hersche/LaraTube/wikis/home

For the quick-setup-text, go here: https://gitlab.com/hersche/LaraTube/wikis/Setup-LaraTube

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

- Implement Vuetify to replace VueSax and VueBootstrap
- Make use of baseUrl - right now, subdirectories are not useable for install

## Wanted features

Things i would love to implement, but where i'm either not shure how to implement it or simply doesn't have prioritised it yet.

- ActivityPub!
- More media-types (half under TODO)
  - PDF, EPUB
  - Pictures
- Breakless click through a package of various medias (like a playlist, but presentation-able)
