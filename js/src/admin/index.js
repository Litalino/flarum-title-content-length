import app from 'flarum/admin/app';

app.initializers.add('litalino/flarum-title-content-length', () => {
  app.extensionData
    .for('litalino-title-content-length')
    /**
     * TITLE
     */
    .registerSetting({
      setting: 'litalino-title-length.limit',
      label: app.translator.trans('litalino-title-content-length.admin.settings.limit_title_label'),
      help: app.translator.trans('litalino-title-content-length.admin.settings.limit_title_help'),
      type: 'boolean',
    })
    .registerSetting({
      setting: 'litalino-title-length.min',
      label: app.translator.trans('litalino-title-content-length.admin.settings.min_title_label'),
      help: app.translator.trans('litalino-title-content-length.admin.settings.min_title_help'),
      type: 'number',
    })
    .registerSetting({
      setting: 'litalino-title-length.max',
      label: app.translator.trans('litalino-title-content-length.admin.settings.max_title_label'),
      help: app.translator.trans('litalino-title-content-length.admin.settings.max_title_help'),
      type: 'number',
    })
    /**
     * CONTENT
     */
    .registerSetting({
      setting: 'litalino-content-length.limit',
      label: app.translator.trans('litalino-title-content-length.admin.settings.limit_content_label'),
      help: app.translator.trans('litalino-title-content-length.admin.settings.limit_content_help'),
      type: 'boolean',
    })
    .registerSetting({
      setting: 'litalino-content-length.min',
      label: app.translator.trans('litalino-title-content-length.admin.settings.min_content_label'),
      help: app.translator.trans('litalino-title-content-length.admin.settings.min_content_help'),
      type: 'number',
    })
    .registerSetting({
      setting: 'litalino-content-length.max',
      label: app.translator.trans('litalino-title-content-length.admin.settings.max_content_label'),
      help: app.translator.trans('litalino-title-content-length.admin.settings.max_content_help'),
      type: 'number',
    });
});
