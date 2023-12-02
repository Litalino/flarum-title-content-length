<?php

/*
 * This file is part of litalino/flarum-title-content-length.
 *
 * Copyright (c) 2023 Litalino.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Litalino\TitleContentLength;

use Flarum\Extend;
use Flarum\Discussion\DiscussionValidator;
use Flarum\Post\PostValidator;
//use Flarum\Post\Event\Saving;
use Illuminate\Validation\Validator;
use Illuminate\Support\Str;
//use Flarum\Settings\SettingsRepositoryInterface;

return [

    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\Settings())
        //title default: 200 - Content default: 65535
        ->default('litalino-title-length.limit', true)
        ->default('litalino-title-length.min', 15)
        ->default('litalino-title-length.max', 180)
        ->default('litalino-content-length.limit', true)
        ->default('litalino-content-length.min', 30)
        ->default('litalino-content-length.max', 65000),

    /**
     * Check Characters Title Min Max
     */
    (new Extend\Validator(DiscussionValidator::class))
        ->configure(function ($flarumValidator, Validator $validator) {
  
          $rules = $validator->getRules();
    
          if (!array_key_exists('title', $rules)) {
              return;
          }

          if (! resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.limit')) {
              return;
          }
  
          $rules['title'][] = 'unique:discussions,title';
          $rules['title'] = array_map(function (string $rule) {
              if (Str::startsWith($rule, 'min:')) {

                if (resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.min')) {
                    return 'min:'. resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.min');
                } else {
                    return 'min:15';
                }
              }
  
              if (Str::startsWith($rule, 'max:')) {

                if (resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.max')) {
                    return 'max:'. resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.max');
                } else {
                    return 'max:180';
                }
              }
  
              return $rule;
          }, $rules['title']);
    
          $validator->setRules($rules);
      }),

      /**
     * Check Characters Content Min Max
     */
    (new Extend\Validator(PostValidator::class))
        ->configure(function ($flarumValidator, Validator $validator) {
            $rules = $validator->getRules();

            if (!array_key_exists('content', $rules)) {
                return;
            }

            if (! resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.limit')) {
                return;
            }
            $length_content_min = resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.min');
            
            if ($length_content_min) {
               $rules['content'][] = 'min:'. $length_content_min;
            } else {
               $rules['content'][] = 'min:30';
            }

            $rules['content'] = array_map(function(string $rule) {
                
                $length_content_max = resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.max');

                if (Str::startsWith($rule, 'max:')) {

                    if ($length_content_max) {
                        return 'max:'. $length_content_max;
                    } else {
                        return 'max:65000';
                    }
                }

                return $rule;
            }, $rules['content']);

            //$validator->sometimes('content', 'min:5', $rulescontent  );

            $validator->setRules($rules);
        }),
    
];
