kw_input
================

Contains simplification of inputs from the whole bunch of sources. Allow you
use either get and cli or server and env params as same source.

This is the mixed package - contains sever-side implementation in Python and PHP.

# PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_input": "dev-master"
    },
    "repositories": [
        {
            "type": "http",
            "url":  "https://github.com/alex-kalanis/kw_input.git"
        }
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


# PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Connect the "kw_input" into your app. When it came necessary
you can extends every library to comply your use-case; mainly your init ad reading.

# Python Installation

into your "setup.py":

```
    install_requires=[
        'kw_input',
    ]
```

# Python Usage

1.) Connect the "kw_input\inputs" into your app. When it came necessary
you can extends every library to comply your use-case; mainly your storage and
processing.
