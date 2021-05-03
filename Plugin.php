<?php namespace SunLab\EmojiForBlog;

use Backend;
use System\Classes\PluginBase;
use Winter\Storm\Support\Facades\Event;

/**
 * EmojiForBlog Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = [
        'SunLab.EmojiPicker'
    ];

    public function pluginDetails()
    {
        return [
            'name'        => 'EmojiBlog',
            'description' => 'sunlab.emojiforblog::lang.plugin.description',
            'author'      => 'SunLab',
            'icon'        => 'icon-smile-o',
        ];
    }

    public function boot()
    {
        $this->addSearchFieldOnBlogController();
    }

    protected function addSearchFieldOnBlogController()
    {
        Event::listen('backend.form.extendFields', static function ($widget) {
            $controller = $widget->getController();
            if (!$controller instanceof \Winter\Blog\Controllers\Posts) {
                return;
            }

            if (!$widget->model instanceof \Winter\Blog\Models\Post) {
                return;
            }

            $emojiField = [
                '_emoji_field' => [
                    'type'    => 'emojipicker'
                ]
            ];

            $tabFields = $widget->getTab('secondary')
                ->getFields();

            $lastTabsFields = array_pop($tabFields);

            $lastTabsFields = array_map(static function ($form_field) {
                return $form_field->config;
            }, $lastTabsFields);

            $lastTabName = array_first($lastTabsFields)['tab'];

            $emojiField['_emoji_field']['tab'] = $lastTabName;

            $lastTabsFields = array_merge(
                array_slice($lastTabsFields, 0, 1),
                $emojiField,
                $lastTabsFields
            );

            $widget->removeTab($lastTabName);
            $widget->addSecondaryTabFields($lastTabsFields);

            $controller->addJs('/plugins/sunlab/emojiforblog/assets/js/script.js');
            $controller->addCss('/plugins/sunlab/emojiforblog/assets/css/style.css');
        });
    }
}
