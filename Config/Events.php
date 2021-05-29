<?php

use BasicApp\Site\SiteEvents;
use BasicApp\System\SystemEvents;
use BasicApp\Helpers\CliHelper;
use BasicApp\Themes\CleanBlog\SiteTheme;

if (class_exists(SystemEvents::class))
{
    SystemEvents::onUpdate(function()
    {
        $themeDir = FCPATH . 'themes' . DIRECTORY_SEPARATOR . 'startbootstrap-clean-blog';

        if (is_dir($themeDir))
        {
            return;
        }

        CliHelper::downloadToFile(
            'https://github.com/StartBootstrap/startbootstrap-clean-blog/archive/refs/tags/v5.0.10.zip', 
            $themeDir . '.zip'
        );

        CliHelper::zipExtractTo($themeDir . '.zip', $themeDir);

        CliHelper::delete($themeDir . '.zip');

        $dirs = ['css', 'img', 'js', 'scss', 'vendor'];

        foreach($dirs as $dir)
        {
            CliHelper::copy(
                $themeDir . DIRECTORY_SEPARATOR . 'startbootstrap-clean-blog-5.0.10' . DIRECTORY_SEPARATOR . $dir, 
                $themeDir . DIRECTORY_SEPARATOR . $dir
            );
        }

        CliHelper::delete($themeDir . DIRECTORY_SEPARATOR . 'startbootstrap-clean-blog-5.0.10');

        CliHelper::copy(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'custom.css', 
            $themeDir . DIRECTORY_SEPARATOR . 'custom.css'
        );
    });
}

if (class_exists(SiteEvents::class))
{
    SiteEvents::onThemes(function($event)
    {
        $event->result[SiteTheme::class] = 'Start Bootstrap - Clean Blog';
    });
}