<?php
/**
 * This file contains the @see \Corleone\UserSettings\UserSettings
 */

namespace Corleone\UserSettings;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use DB;

/**
 * Class UserSettings
 *
 * This class handles the user settings in an application.
 *
 * @package Corleone\UserSettings
 */
class UserSettings
{

    /**
     * The cache of the user's settings
     *
     * @var object
     */
    protected static $settings;

    protected static $got = false;

    /**
     * UserSettings constructor.
     *
     * This function is protected
     */
    protected function __construct(){}

    /**
     * UserSettings destructor
     *
     * This function is protected
     */
    protected function __destruct(){}

    /**
     * Check out if the settings got
     */
    protected static function settingsExists()
    {
        if(self::$got)
            return;
        self::getSettings();
    }

    /**
     * Get all settings of the current user from database
     *
     * If the settings column empty the default setting will load in an object from config file.
     */
    protected static function getSettings()
    {
        self::$got = true;

        $data = DB::table(config('user-settings.database.table'))
            ->select(config('user-settings.database.column'))
            ->where(config('user-settings.database.primary_key'), '=', Auth::id())
            ->get();

        $settings = json_decode($data[0]->settings);

        if(json_last_error() !== JSON_ERROR_NONE){
            self::$settings = (object) config('user-settings.settings');
            return;
        }

        self::$settings = $settings;
    }

    /**
     * This method called simply the {@link self::getSettings()} method.
     *
     * This method can be used if you want default setting for a user on create them.
     */
    public static function setDefaultSettings()
    {
        self::settingsExists();
        self::save();
    }

    /**
     * Get a setting by key
     *
     * This function will return the setting by key.
     * If the these setting not exists it will return the default value from the config file,
     * otherwise the global default value.
     *
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        self::settingsExists();
        if(isset(self::$settings->{$key}))
            return self::$settings->{$key};
        elseif(config('user-settings.settings.' . $key))
            return config('user-settings.settings.' . $key);
        else
            return null;
    }

    /**
     * Set a setting by key
     *
     * This function will check the type of setting from the config file.
     * If no default exists it will be saved.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set(string $key, $value)
    {
        self::settingsExists();
        if(config('user-settings.settings.' . $key) && gettype($value) !== gettype(config('user-settings.settings.' . $key))){
            throw new InvalidArgumentException(('The expected type is "' . gettype(config('user-settings.settings.' . $key)) . '"! "' . gettype($value) . '" was given.'));
        }

        self::$settings->{$key} = $value;
        self::save();
    }

    /**
     * Reset a setting
     *
     * It will reset the setting to the default from config file and return true.
     * Otherwise the setting will be delete and it will return false.
     *
     * @param string $key
     * @return bool
     */
    public static function reset(string $key): bool
    {
        self::settingsExists();
        if(config('user-settings.settings.' . $key)){
            self::$settings->{$key} = config('user-settings.settings.' . $key);
            self::save();
            return true;
        }
        unset(self::$settings->{$key});
        self::save();
        return false;
    }

    /**
     * Returns true if the setting exists in the setting variable else false
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::settingsExists();
        return (isset(self::$settings->{$key}));
    }

    /**
     * Returns all settings as an object
     *
     * @return object
     */
    public static function all(): object
    {
        self::settingsExists();
        return self::$settings;
    }

    /**
     * Save all settings to database
     */
    protected static function save()
    {
        DB::table(config('user-settings.database.table'))
            ->where(config('user-settings.database.primary_key'), '=', Auth::id())
            ->update([config('user-settings.database.column') => json_encode(self::$settings)]);
    }

}