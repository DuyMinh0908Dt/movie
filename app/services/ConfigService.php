<?php

namespace App\Services;

class ConfigService
{
    private static $GENERAL_CONFIG_PATH = 'general.config.php';

    public static function updateBanners($banners)
    {
        $config = [];
        $config['Auth'] = $GLOBALS['config']['Auth'];
        $config['Website'] = $GLOBALS['config']['Website'];
        $config['Banners'] = $banners;
        $content = "<?php\nreturn " . var_export($config, true) . ";";
        file_put_contents(self::$GENERAL_CONFIG_PATH, $content);
    }

    public static function updateWebsiteConfig($websiteConfig)
    {
        $config = [];
        $config['Auth'] = $GLOBALS['config']['Auth'];
        $config['Banners'] = $GLOBALS['config']['Banners'];
        $config['Website'] = [
            'name' => $websiteConfig['name'],
            'logo' => $websiteConfig['logo'],
            'phone' => $websiteConfig['phone'],
            'email' => $websiteConfig['email'],
            'description' => $websiteConfig['description'],
            'hold_time' => $websiteConfig['hold_time'],
        ];
        $config['Auth']['remember_time_in_days'] = $websiteConfig['remember_time_in_days'];
        $content = "<?php\nreturn " . var_export($config, true) . ";";
        file_put_contents(self::$GENERAL_CONFIG_PATH, $content);

    }

}