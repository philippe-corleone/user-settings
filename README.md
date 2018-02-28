# user-settings

This package is a Laravel 5 package. It handles the user settings for a logged in user.

> This is my first public released Laravel package. If you have some nice ideas to expand and/or optimize it than post it or fork it!
> I'm always happy to learn something new.

## Requirements

  - Laravel >=5
  - PHP >=7

## Installation
1. At first, add the package to your `composer.json`:
```
composer require "corleone/user-settings"
```
2. Open your `config/app.php` and add the folloing line to your aliases:
```
'UserSettings' => \Corleone\UserSettings\UserSettings::class,
```
3. Publish the migration and the `config/user-sattings.php` file with the following command using your terminal:
```
php artisan vendor:publish
php artisan user-settings:migration
```

4. Run `php artisan migrate` to add the settings column to your users table. (Note: If you customize this file, copy this to youR `database/migrations` directory and do this, than run this command)

5. In your `config/user-settings.php` you can see a twodimensional array, which you can configurate now:
```PHP
'database' => [
    'table' => 'users',
    'column' => 'settings',
    'primary_key' => 'id'
],
'settings' => [
    // insert here your settings
]
```

## Configuration of the `config/user-settings.php` file

Set `table`, `column` and `primary_key` to match your user table. The `primary_key` should be the users id.

In the `settings` array you can set your default values for the specific settings, e.g.:
```PHP
'database' => [
    'table' => 'users',
    'column' => 'settings',
    'primary_key' => 'id'
],
'settings' => [
    'display_time' => 7,
    'be_logged_in' => false,
    'background_color' => 'black',
    'what_else' => '1.2'
]
```

## Usage

You can use the `UserSettings` class in controllers and views. The settings will be stored as json into your settings column.
> Note: If you want to use it in a controller or class you have to set 
> `use Corleone\UserSettings\UserSettings;`

### Set a setting
This method will set (if the setting is defined in your `config/user-settings.php` file) or add (if the default is not defined) a setting to the json.
```PHP
UserSettings::set(string [setting], [value]);
```
> Note: Set method will match the data typ from the `config/user-settings.php` value, if they exists. So you can better develop your application.

### Set default settings
This method will set the default settings from your `config/user-settings.php` file to the users table.
```PHP
UserSettings::setDefaultSettings();
```

### Reset a Setting
This method will reset a setting to the default from your `config/user-settings.php` file, if they exists. Otherwise it will delete it simlpy from the json.
```PHP
UserSettings::reset(string [setting]): bool;
```
This method will return `true` if the setting was set to default from the `config/user-settings.php` file and `false` if the setting was simply deleted.

> Note: If you want to reset all settings of a user, use the `PHP UserSettings::SetDefaultSettings()`

### Get a setting
This method will return the value of a setting.
```PHP
UserSettings::get(string [setting]);
```

### Get all settings
This method will return all settings as an object.
```PHP
UserSettings::all(): object;
```

### Ask if a user have a specific setting
This method will return `true` if a user has a setting otherwise it will return `false`.
```PHP
UserSettings::has(string [setting]): bool;
```

### Save settings
All settings will saved automatically.

## License
This package is free software distributed under the terms of the MIT license.
