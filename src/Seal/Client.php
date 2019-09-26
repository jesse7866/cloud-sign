<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Seal;

use IYin\CloudSign\Kernel\BaseClient;
use IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException;

class Client extends BaseClient
{
    /**
     * 根据企业信息生成章模接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function generateSealPic(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/pic', $data);
    }

    /**
     * 下载印章图片.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function downloadSealPic(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/sealPic/download', $data);
    }

    /**
     * 下载签名图片.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function downloadSignPic(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/signPic/download', $data);
    }

    /**
     * 查询用户合同平台剩余次数.
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function userSurplusOfContract()
    {
        $data = $this->formatQueryWithSign();

        return $this->httpGet('/open-api/api/seal/user/accountSstatistics', [], $data);
    }

    /**
     * 获取云签名和云印章列表.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function getCloudSignAndSealList(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/enterprise', $data);
    }

    /**
     * 创建云签名和云印章时上传文件.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function uploadCloudSignAndSealFile(array $data)
    {
        $headers = ['token' => $this->accessToken->getAccessToken()];
        $data['base64'] = false === strpos($data['base64'], 'data:image') ?
            'data:image/png;base64,'.$data['base64'] : $data['base64'];

        return $this->request('/zuul/open-api/api/seal/file/upload', 'POST', ['json' => $data, 'headers' => $headers]);
    }

    /**
     * 创建云签名.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function createCloudSign(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/save/cloud/signature', $data);
    }

    /**
     * 创建个人私章.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function createPersonalSeal(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/createPersonalSeal/download', $data);
    }

    /**
     * 创建云印章.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function createCloudSeal(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/seal/save/cloud/info', $data);
    }
}