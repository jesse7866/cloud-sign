<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>

 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Base;

use IYin\CloudSign\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取 Token
     *
     * @return array
     */
    public function token()
    {
        return $this->accessToken->getToken(true);
    }

    /**
     * 文档验真接口
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * 
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function verifySignFile(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/application/verify/sign/file', $data);
    }
}