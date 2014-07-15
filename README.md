php-pzq-api
===========

PHP API for PZQ, a persistent store daemon by Mikko Koppanen which uses the ZeroMQ messaging socket library.

[![Latest stable version](https://poser.pugx.org/himedia/php-pzq-api/v/stable.png "Latest stable version")](https://packagist.org/packages/himedia/php-pzq-api)

This is an implementation from the [php-api](https://github.com/mkoppanen/pzq/blob/master/php-api/PZQClient.php) provided by [@mkoppanen](https://github.com/mkoppanen).

## Installing via Composer

The recommended way to install PHP PZQ API is through
[Composer](http://getcomposer.org).

```bash
### Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, update your project's composer.json file to include:

```javascript
{
    "require": {
        "himedia/php-pzq-api": "1.*"
    }
}
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Usage

### Consumer usage

```php
$context = new \ZMQContext();
$consumer = new \HIM\PZQ\Consumer($context, "tcp://127.0.0.1:11132");

$ids = array();
for ($i = 0; $i < 10000; $i++) {
    $message = $consumer->consume();
    $consumer->ack($message);
    echo "Consumed {$i}" . PHP_EOL;
}

sleep (5);
```

### Producer usage

```php
$context = new \ZMQContext();
$producer = new \HIM\PZQ\Producer("tcp://127.0.0.1:11131");
$producer->setIgnoreAck(false);

for ($i = 0; $i < 10000; $i++) {
    $message = new \HIM\PZQ\Message();
    $message->setId("id-{$i}");
    $message->setMessage("id-{$i}");
    //echo "Produced id-{$i}" . PHP_EOL;

    $producer->produce($message, 10000);
}
```

### Monitor usage

```php
$m = new \HIM\PZQ\Monitor("ipc:///tmp/pzq-monitor");
var_dump($m->getStats());
```

## Copyrights & licensing
Licensed under the GNU Lesser General Public License v3 (LGPL version 3).
See [LICENSE](LICENSE) file for details.

## Change log
See [CHANGELOG](CHANGELOG.md) file for details.

## Git branching model
The git branching model used for development is the one described and assisted by `twgit` tool: [https://github.com/Twenga/twgit](https://github.com/Twenga/twgit).
