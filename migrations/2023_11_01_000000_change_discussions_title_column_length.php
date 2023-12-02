<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    /*'up' => function (Builder $schema) {
        $schema->table('discussions', function (Blueprint $table) {
            $table->string('title', 1000)->change();
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('discussions', function (Blueprint $table) {
            $table->string('title', 200)->change();
        });
    },*/
    /*'up' => function (Builder $schema) {
        $schema->table('discussions', function (Blueprint $table) {
            $table->string('content', 30)->change();
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('discussions', function (Blueprint $table) {
            $table->string('content', 65000)->change();
        });
    }*/
];


return Migration::addSettings([
    'litalino-title-length.limit' => true,
    'litalino-title-length.min' => 15,
    'litalino-title-length.max' => 180,
    'litalino-content-length.limit' => true,
    'litalino-content-length.min' => 30,
    'litalino-content-length.max' => 65000
]);
