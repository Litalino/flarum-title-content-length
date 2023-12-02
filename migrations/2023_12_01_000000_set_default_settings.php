<?php
use Flarum\Database\Migration;


return Migration::addSettings([
    'litalino-title-length.limit' => true,
    'litalino-title-length.min' => 15,
    'litalino-title-length.max' => 180,
    'litalino-content-length.limit' => true,
    'litalino-content-length.min' => 30,
    'litalino-content-length.max' => 65000
]);