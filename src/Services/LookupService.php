<?php

namespace PhpHelpers\Services;

class LookupService
{
  public function handle(string $resource)
  {
    $config = config("lookup.resources.$resource");

    if (!$config) abort(404, 'Resource not found');

    $model = $config['model'];
    $label = $config['label'];
    $query = $model::query()->select('id', "{$label} as value");

    return $query->get();
  }
}
