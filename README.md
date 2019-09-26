<h1 align="center"> 安印云签 / iyin cloud sign </h1>

<p align="center"> A iyin cloud sign SDK.</p>


## Installing

```shell
$ composer require iyin/cloud-sign:dev-master -vvv
```

## Usage

```php
use IYin\CloudSign\Application;

$config = [
    'app_id'  => 'xxxxxx',         // AppID
    'secret'  => 'xxxxxx',     // AppSecret
    'sign_type' => 'MD5',      // 默认为 MD5，支持HMAC-SHA256 和 MD5

    'log' => [
        'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path' => '/tmp/cloud-sign.log',
                'level' => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'daily',
                'path' => '/tmp/cloud-sign.log',
                'level' => 'info',
            ],
        ],
    ],

    'http' => [
        'max_retries' => 1,
        'retry_delay' => 500,
        'timeout' => 5,
        'base_uri' => 'https://cloud-sign.i-yin.com.cn',
    ],

];

$app = new Application($config);

$params = [...];
$result = $app->platform_sign->singleSign($params);

```


## License

MIT