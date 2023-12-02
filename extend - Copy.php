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
        ->default('litalino-title-length.max', 120)
        ->default('litalino-content-length.limit', true)
        ->default('litalino-content-length.min', 15)
        ->default('litalino-content-length.max', 10000),

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
                  //return 'min:' . $this->settings->get('litalino-title-length.min');
                  //return 'min:15';
                if (resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.min')) {
                    return 'min:'. resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.min');
                } else {
                    return 'min:15';
                }
              }
  
              if (Str::startsWith($rule, 'max:')) {
                  //return 'max:' . $this->settings->get('litalino-title-length.max');
                  //return 'max:120';
                if (resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.max')) {
                    return 'max:'. resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-title-length.max');
                } else {
                    return 'max:120';
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
            //$length_content_max = resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.max');
            
            //$rules['content'][] = 'min:30';
            //$rules['content'][] = 'min:'. $length_content_min;
            if ($length_content_min) {
               $rules['content'][] = 'min:'. $length_content_min;
            } else {
               $rules['content'][] = 'min:30';
            }

            $rules['content'] = array_map(function(string $rule) {
                //if (Str::startsWith($rule, 'min:')) {
                //    return 'min:15';
                //}
                //if (\Flarum\Settings\SettingsRepositoryInterface::get('litalino-content-length.max')) {
                $length_content_max = resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.max');

                if (Str::startsWith($rule, 'max:')) {
                    //return 'max:10000';

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
    //Và mã này có thể được sử dụng để xác thực nội dung bài đăng thực tế:
    //[2023-12-01 17:26:25] flarum.ERROR: TypeError: Flarum\Mentions\Formatter\UnparsePostMentions::__invoke(): Argument #2 ($xml) must be of type string, null given, called in /home/flarumkhatvongsongvn/flarum.khatvongsong.vn/public_html/vendor/flarum/core/src/Foundation/ContainerUtil.php on line 30 and defined in /vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php:34
    //FIX: SychO\MovePosts\Formatter\UnparsePostMentions.php before public function __invoke($context, string $xml)
    //Add: protected $xml;
    //flarum.ERROR: TypeError: Flarum\Mentions\Formatter\UnparsePostMentions::__invoke(): Argument #2 ($xml) must be of type string, null given, called in /public_html/vendor/flarum/core/src/Foundation/ContainerUtil.php on line 30 and defined in /public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php:36
    //flarum.ERROR: TypeError: Flarum\Mentions\Formatter\UnparsePostMentions:: __invoke(): Argument #2 ($xml) must be of type string, null given, called in /public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php on line 36 and defined in /public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php:49
    //flarum.ERROR: TypeError: Flarum\Mentions\Formatter\UnparsePostMentions::updatePostMentionTags(): Argument #2 ($xml) must be of type string, null given, called in /public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php on line 36 and defined in /public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php:49
    //flarum.ERROR: TypeError: Flarum\Mentions\Formatter\UnparsePostMentions::unparsePostMentionTags(): Argument #1 ($xml) must be of type string, null given, called in /home/flakhatvongsongvn/fla.khatvongsong.vn/public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php on line 37 and defined in /home/flakhatvongsongvn/fla.khatvongsong.vn/public_html/vendor/flarum/mentions/src/Formatter/UnparsePostMentions.php:81

    /*(new Extend\Event)
        ->listen(\Flarum\Post\Event\Saving::class, function ($event) {
            // Exclude enforcement for administrator and moderator groups
            $user = $event->actor;
            if ($user instanceof \Flarum\User\User && ($user->isAdmin())) { //|| $user->isModerator())
                //return;
            }
            if (! resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.limit')) {
                return;
            }
            
            if ($event->post->isDirty('content')) {
                
                $length_content_min = resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.min');
                $length_content_max = resolve(\Flarum\Settings\SettingsRepositoryInterface::class)->get('litalino-content-length.max');

                if ($length_content_min) {
                    $min = $length_content_min;
                } else {
                    $min = '15';
                }
                if ($length_content_max) {
                    $max = $length_content_max;
                } else {
                    $max = '15';
                }

                resolve(\Illuminate\Contracts\Validation\Factory::class)->make([
                    'content' => $event->post->content,
                ], [
                    //'content' => 'min:20|max:20000',
                    'content' => 'min:'. $min .'|max:'. $max .'',
                ])->validate();
            }
        }),*/
];
