<?php

return [
    'default-rows-per-page' => env('MENU_ITEM_DEFAULT_ROWS_PER_PAGE', env('MENU_DEFAULT_ROWS_PER_PAGE', 20)),
    'max-rows-per-page' => env('MENU_ITEM_MAX_ROWS_PER_PAGE', env('MENU_MAX_ROWS_PER_PAGE', 500)),
];
