<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Base;

use IYin\CloudSign\Kernel\AccessToken as BaseAccessToken;

class AccessToken extends BaseAccessToken
{
    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'grant_type' => 'client_credentials',
            'scope' => 'read',
            'client_id' => $this->app['config']['app_id'],
            'client_secret' => $this->app['config']['secret'],
        ];
    }

    public function getEndpoint(): string
    {
        return $this->app['config']['http']['base_uri'].'/open-api/auth/token';
    }
}
