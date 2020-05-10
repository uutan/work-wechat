
<h1 align="left">WorkWeChat</h1>

📦 企业微信开发接口SDK.

## Requirement

1. PHP >= 7.2
2. **[Composer](https://getcomposer.org/)**
3. openssl 拓展
4. fileinfo 拓展（素材管理模块需要用到）

## Installation

```shell
$ composer require "uutan/work-wechat" @dev
```

## Usage

基本使用（以企业内部开发为例）:

```php
<?php

use WorkWechat\Factory;

$options = [
    'corp_id'    => 'wx3cf0f39249eb0exxx',
    'agent_id'    => '49000091',
    'secret'    => 'xxx',
];

$app = Factory::work($options);

$server = $app->server;
$user = $app->user;

$server->serve()->send();
```


## License

MIT