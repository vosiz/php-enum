# php-enum
Utility to have enum-like classes. We love ENUMS!
## Motivation
True enumeration with integers seems to be impossible to achieve in PHP (at least for me), so this is my take on it. Designed to be dynamical - not only for compile time, you can read values from DB f.e. and make dynamic enums. Built on [php-utils](https://github.com/vosiz/php-utils). Made by personal need, so feel free to use it if it fits yours. MIT - use it however you like.
## Requirements
- PHP 7.4 or newer
- Composer
- [vosiz/php-utils](https://github.com/vosiz/php-utils) 1.12 (installed by Composer)
## Bug tracker
No tracked
## Features
Everything checked in the [Roadmap](#roadmap).
## Roadmap
- [x] Enum classes with integer values
- [x] Access by key and search by value
- [x] Display name next to the key
- [x] Values added at runtime (dynamic enums)
- [x] Tests
- [ ] Flag enums (bit masks)
## Setup
```
composer require vosiz/php-enum
```
### Your own enum
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
> You can have multiple same values allocated to different keys (some classic enums do not allow it) - here it is permitted. But you can lose consistency if you base on integers (values) too much.

> [!IMPORTANT]
> Keys are unique and they are kept in CamelCase, so `dark_air` and `DarkAir` are the same key. Adding a key which is already there throws `EnumException`.

### Usage sample - int-like
Now you can simply use it for example like this. It is value based - integers.
```php
$elements = Elements::GetValues(); // all enum values - foreach usage f.e.

$armor->ResistanceType = Elements::GetEnumVal('Fire'); // allocate enum variable

if($armor->ResistanceType == Elements::GetEnumVal('Water')) { // comparation
    
    ...
}
```
`GetValues()` gives you a copy, so you can do whatever you want with it and the enum stays as it is.
### Usage sample - enum-like
If you need to track or operate with naming of enum (enum key and value in general), you should use something like this:

```php
$enum = Elements::GetEnum('Earth');

// access key or value
$key = $enum->GetKey(); // alt GetName()
$val = $enum->GetValue();

// compare - same enum class and same value
$is_water = $enum->Compare(Elements::GetEnum('Water'));

// if you know value but not name
$what_is_the_key = Elements::Find(2)->GetKey(); // Find returns enum or null
```
### Dynamic values
Values do not have to be known in `Init()`. Add them later, from DB f.e. - they land after the preset ones.

```php
Elements::AddValues(['Ether' => 4]);

$ether = Elements::GetEnum('Ether');
```
A value is an integer, or a string holding one (`'4'`), anything else throws `EnumException`.
### Useful tips
If you want to distinct 'key' from 'shown key', there is Display property. It is automatically set to key, but you can set it up in custom enum definition.
Example.

```php
class EdibleEnum extends Enum {

    public static function Init(): void {

        $vals = [
            'apple'     => 0,
            'leek'      => [100, 'pure poison'],
            'peach'     => 69,
            'pear'      => [1, 'apple relative'],

        ];
        self::AddValues($vals);
    } 
}
```

> [!IMPORTANT]
> You have to still keep first value of array as int (core functionality of enum).

More to look at in `samples/`.
### Tests
Docker is all you need, the suite runs on every supported PHP version.
```
./tests.sh
```
