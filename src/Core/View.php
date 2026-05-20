<?php

namespace App\Core;

use Smarty;

class View
{
    private static ?Smarty $smartyInstance = null;

    public static function render(string $template, array $data = []): void
    {
        if (self::$smartyInstance === null) {
            self::$smartyInstance = new Smarty();
            
            self::$smartyInstance->setTemplateDir(__DIR__ . '/../../templates');
            self::$smartyInstance->setCompileDir(__DIR__ . '/../../templates_c');
        }

        foreach ($data as $key => $value) {
            self::$smartyInstance->assign($key, $value);
        }

        self::$smartyInstance->display($template);
    }
}