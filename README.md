```
composer require georgeboot/laravel-tiptap
yarn add laravel-tiptap
```

In your `app.js`:

```js
import Alpine from 'alpinejs'
import LaravelTiptap from 'laravel-tiptap' // add this
Alpine.data('tiptapEditor', LaravelTiptap) // add this
Alpine.start()
```
