<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>

 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Auth;

use IYin\CloudSign\Kernel\BaseClient;

class Company extends BaseClient
{
    /**
     * 企业用户注册
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function register(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/user/enterprise/register/phone', $data);
    }

    /**
     * 企业三要素校验
     *
     * @param string $enterpriseName
     * @param string $credentialsEncode
     * @param string $legalRepresentative
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function verifyEnterpriseInfo(string $enterpriseName, string $credentialsEncode, string $legalRepresentative)
    {
        $data = $this->formatQueryWithSign(compact('enterpriseName', 'credentialsEncode', 'legalRepresentative'));

        return $this->httpPostJson('/open-api/verification/interface/verifyEnterpriseInfo', $data);
    }
}