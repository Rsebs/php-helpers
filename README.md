# php-helpers

Se debe de crear la configuración lookup.resources en cada proyecto Laravel que lo necesite

```php
// config/lookup.php

<?php

return [
  'resources' => [
    'categories' => [
      'model' => \App\Models\Category::class,
      'label' => 'name',
    ],
    'books' => [
      'model' => \App\Models\Book::class,
      'label' => 'title',
    ],
  ],
];
```
