<?php
/**
 * Created by PhpStorm.
 * User: Corleone
 * Date: 17.02.18
 * Time: 14:57
 */

namespace Corleone\UserSettings;

use Illuminate\Support\Facades\Facade;

class UserSettingsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'user-settings';
    }
}