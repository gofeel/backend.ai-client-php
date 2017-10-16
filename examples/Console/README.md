# Backend.ai Console Runner

This example shows how to run a code with php-cli interface. 


## How to use

```sh
export BACKEND_ACCESS_KEY=AKXXXXXXXXXXXXXXXXXXX
export BACKEND_SECRET_KEY=FOOBARFOOBARFOOBARFOOBARFOOBARFOOBARFOOBAR
php runner.php -k python3 -f hello_world.py
```

or you can run inline envoriment variables.

```sh
BACKEND_ACCESS_KEY=AKXXXXXXXXXXXXXXXXXXX BACKEND_SECRET_KEY=FOOBARFOOBARFOOBARFOOBARFOOBARFOOBARFOOBAR php runner.php -k python3 -f hello_world.py
```

## Options

-f/--file <argument>
     File

-k/--kernel <argument>
     Kernel type


## Note
This project uses Commando, you need to install php-mbstring package.
