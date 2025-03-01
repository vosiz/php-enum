# PHP Enum(erations)
Utility to have enum-like classes. We love ENUMS!
True enumeration with integers seems to be impossible to achieve (at least for me). 
Designed to be dynamical (not only for compile time - read values from DB f.e. and make dynamic enums)

## Bug report
None taken

## Installation
### Composer
Install with
```
composer install vosiz/php-enum
```

Update with (dependencies/required)
```
composer update
```

## Usage
Implement your own enums!
Lets say, you have a game and want elemental magic defined other way than with defines or constants.
You want to do something like this:

```php
use Vosiz\Enums\Enum;

class Elements extends Enum {

    public static function Init(): void {

        $vals = [
            'Fire' => 0,
            'Water' => 1,
            'Air' => 2,
            'Earth' => 3,
        ];
        self::AddValues($vals);
    } 
}
```

> [!IMPORTANT]
> You can multiple same values allocated to diferent keys (as some classic enum does not allow it) - here it is permitted. But you can lose consistency if you base on integers (values) too much.

### Usage sample - int-like
Now you can simply use it for example like this. It is value based - integers.
```php
$elements = Elements::GetValues(); // all enum values - foreach usage f.e.

$armor->ResistanceType = Elements::GetEnumVal('Fire'); // allocate enum variable

if($armor->ResistanceType == Elements::GetEnumVal('Water');) { // comparation
    
    ...
}
```

### Usage sample - enum-like
If you need to track or operate with naming of enum (enum key and value in general), you should use something like this:

```php
$enum = Elements::GetEnum('Earth');

// access key or value
$key = $enum->GetKey(); // alt GetName()
$val = $enum->GetValue();

// compare
$is_water = $enum->Compare(Elements::GetEnum('Water'));

// i fyou know value but not name
$what_is_the_key = Elements::Find('Air'); 

```