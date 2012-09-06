# phpish\template

Capture output of and compose plain old PHP templates.




## Require and use

```php
<?php

	require 'vendor/phpish/template/template.php';
	use phpish\template;

	...
?>
```



## Function Reference


### render

string __render__ ( string _$template_ [, array _$template_vars_ [, string _$template_dir_ ]] )




### compose

string __compose__ ( string _$template_ , array _$template_vars_ [, $... ] )




### basedir

string __basedir__ ( [ string $dir] )




### default_basedir

string __default_basedir__ ( void )




### filename

string __filename__ ( string _$template_ [, string _$template_dir_ ] )