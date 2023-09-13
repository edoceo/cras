# A Calendar and Todo App

Cras is a simple, user centric, Calendaring and Todo web-application.

## Integration

Cras can subscribe to multiple calendaring systems, to manage events.
Cras can publish events to calendars (if they support it) or publish your free/busy.

## Usage

Cras is intended to replace tools like Apple/Google/Microsoft ("AGM") products.
Cras is also intended to replace tools like Calendly and YCBM.

## Installation

Checkout this code somewhere on your server.
Update the Apache configuration using the template provided.

Typically, the configuration is `Include`d into the Apache configs (eg: symlink into /etc/apache2/conf.d/).
And then expose Cras through an Alias (eg: `Alias /cras /opt/edoceo/app/cras/webroot`).

## WebShare Target

I've added this starting in webroot/share-incoming.
I have a mainfest.json -- it looks OK -- I think I have the pages right.
But the Share Target doesn't appear on my Android :(
I don't see the browser loading the manifest .json file either tho


## Docs

https://web.dev/web-share-target/
https://web-share.glitch.me/


## Manifest / Display

See https://web.dev/add-manifest/ -- and check out the options there.

* **fullscreen** --
* **standalone** -- no UI chrome
* **minimal-ui** -- small header at top, menu options on right side
* **browser** --


https://web.dev/offline-fallback-page/

https://web.dev/installable-manifest/


## Debugging

Open Chrome Developer Tools
Choose the Application Tab
Inspect Manifest, Local Storage, Cookies, etc.


## Speech Recognition

* https://developer.chrome.com/blog/voice-driven-web-apps-introduction-to-the-web-speech-api/
* https://github.com/googlearchive/webplatform-samples/blob/master/webspeechdemo/webspeechdemo.html#L110
* https://www.google.com/intl/en/chrome/demos/speech.html
* https://www.audero.it/demo/web-speech-api-demo.html
* https://zenorocha.github.io/voice-elements/
