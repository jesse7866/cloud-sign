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

use IYin\CloudSign\Kernel\BaseClient;

class Person extends BaseClient
{
    /**
     * 获取短信验证码.
     *
     * @param string      $phone
     * @param string      $useType
     * @param string|null $accountId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function getSmsCode(string $phone, string $useType, $accountId = '')
    {
        $data = $this->formatQueryWithSign(compact('phone', 'useType', 'accountId'));

        return $this->httpPostJson('/open-api/api/user/sms/not-login-verification/code', $data);
    }

    /**
     * 用户注册手机或邮箱有效性校验.
     *
     * @param string $phoneOrEmail
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function getUserByPhoneOrEmail(string $phoneOrEmail)
    {
        $data = $this->formatQueryWithSign(compact('phoneOrEmail'));

        return $this->httpPostJson('/open-api/api/user/getUserByPhoneOrEmail', $data);
    }

    /**
     * 个人用户注册.
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

        return $this->httpPostJson('/open-api/api/user/person/register', $data);
    }

    /**
     * 个人两要素校验.
     *
     * @param string $name
     * @param string $idNumber
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function verifyNameAndIdCard(string $name, string $idNumber)
    {
        $data = $this->formatQueryWithSign(compact('name', 'idNumber'));

        return $this->httpPostJson('/open-api/verification/interface/identity/card/information', $data);
    }
}