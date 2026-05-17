<?php

namespace Hexatex\LaravelMenu\Menu\Requests\Admin;

use Hexatex\LaravelMisc\IndexRequest as BaseIndexRequest;

class IndexRequest extends BaseIndexRequest
{
    protected $maxRowsConfig = 'menu.max-rows-per-page';
}
