<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Auth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['auth_company'] = function ($app) {
            return new Company($app);
        };

        $app['ocr'] = function ($app) {
            return new Ocr($app);
        };

        $app['auth_person'] = function ($app) {
            return new Person($app);
        };
    }
}