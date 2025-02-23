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
> You can multiple same values allocated to diferent keys (as some classic enum does not allow it) - here it is permited.

Now you can simply use it for example like this.
```php
$elements = Elements::GetValues(); // all enum values - foreach usage f.e.

$armor->ResistanceType = Elements::GetEnum('Fire'); // allocate enum variable

if($armor->ResistanceType == Elements::GetEnum('Water');) { // comparation
    
    ...
}
```
