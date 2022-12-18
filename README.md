Laravel Sequence Number Generator

Requirements

| Laravel | PHP |
| ------ |-----|
| 9.x | 8.x |

Install package via Composer

```sh
composer require wahyudwikrisnanto/sequence-number-generator 
```

Migrate the migrations
```sh
php artisan migrate 
```

Publish the config file
```sh
php artisan vendor:publish --provider="WahyuDwiKrisnanto\SequenceNumberGenerator\SequenceNumberGeneratorServiceProvider" --tag="config"
```

## Usage
```sh
use WahyuDwiKrisnanto\SequenceNumberGenerator\Facades\SequenceGenerator;

$builder = new SequenceNumberBuilder

# These options below only used once, 
# if you want to set the custom default options
# in every sequence generation you can set at the config file.

# Prefix of the sequence number
$builder->prefix('INV');

# Digits of sequence number
# Any digits left will be filled with 0
$builder->digits(4)

# Separator between prefix and sequence number
$builder->prefixSequenceSeparator('-')

# Start of the sequence number
$builder->start(1);

# Number of sequence number that will be skipped.
$builder->skip(5);

# The new generated sequence number will not count as last sequence number
# which means that the next sequence number will not based on 
# this generated sequence number
$builder->ignoreUpdate()

# The output will be string
# Ex. INV-0001
$builder->generate();
```

## License

MIT
