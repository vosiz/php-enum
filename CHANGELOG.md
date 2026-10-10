# Change log
## Current version
### 2.4.0 - Big beautiful fix
- Init — once per enum, values added before first read keep the preset ones
- GetValues — returns a copy
- Find — works before first read
- Compare — enum type matters
- AddValues — integer values only, existing key throws EnumException
- enum class name taken as is
- constructor is protected
- requires php-utils 1.12
- tests — PHPUnit, tests.sh
- samples moved to samples/

## History
### 2.3.0 - Collection
- using Dictionaries

### 2.2.1 - ToString
- more friendly __toString override

### 2.2.0 - Composer-autoload
- core is loaded

### 2.1.0 - Fixes
- some refactor
- display name option (instead of key)
- some fixes

### 2.0.0 - Fixes
- reworked, not so-int based

### 1.0.1 - Fixes
- composer fix
- updated README
- dependency update

### 1.0.0 - First release
- basic interface to work with enums