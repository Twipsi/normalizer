# Normalizer
 A very simple class to normalize and slugify a string or url.

## Install
```
composer require twipsi/normalizer
```

## Usage

To just clean out html tags and line/tab spaces.
```php
$string = "Hel  lo <br><div>Wor
ld</div>";

Normalizer::normalizeString($string);

Output: "Hello World"
```

You can also set the transliterate attribute to transliterate.
```php
$string = "Hél  lö <br><div>Wőr
ld</div>";

Normalizer::normalizeString($string, true);

Output: "Hello World"
```

To slugify a string using default "\\/_|+ -" characters as separation identifiers.
```php
$string = "H\\él/ló|W_ö+r l-d";

Normalizer::slugifyString($string);

Output: "h-el-lo-w-o-r-l-d"
```

You can also specify your own separator.
```php
$string = "H\\él/ló|W_ö+r l-d";

Normalizer::slugifyString($string, '_');

Output: "h_el_lo_w_o_r_l_d"
```

To slugify a string using default "_|+ -" characters as separation identifiers.
```php
$string = "https:://test.com/Tést page/Sep|ar_ate+Thís?örg=éáő";
Normalizer::slugifyPath($string);

Output: "https:://test.com/test-page/sep-ar-ate-this?org=eao"
```

You can also specify your own separator.
```php
$string = "https:://test.com/Tést page/Sep|ar_ate+Thís?örg=éáő";

Normalizer::slugifyPath($string, '_');

Output: "https:://test.com/test_page/sep_ar_ate_this?org=eao"
```
