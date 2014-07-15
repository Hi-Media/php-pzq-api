php-pzq-api
===========

PHP API for PZQ, a persistent store daemon by Mikko Koppanen which uses the ZeroMQ messaging socket library.

### Installing via Composer

The recommended way to install PHP PZQ API is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, update your project's composer.json file to include:

```javascript
{
    "require": {
        "hi-media/php-pzq-api": "~0.1.1"
    }
}
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

### Usage

# Consumer usage

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

# Producer usage

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

# Monitor usage

```php
$m = new \HIM\PZQ\Monitor("ipc:///tmp/pzq-monitor");
var_dump($m->getStats());
```
