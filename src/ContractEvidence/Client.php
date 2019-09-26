<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\ContractEvidence;

use IYin\CloudSign\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 合同存证接口.
     *
     * @param string $compactId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function save(string $compactId)
    {
        $data = $this->formatQueryWithSign(compact('compactId'));

        return $this->httpPostJson('/open-api/webank/evidence/saveE', $data);
    }

    /**
     * 合同取证接口.
     *
     * @param string $idType
     * @param string $idNum
     * @param string $compactId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function find(string $idType, string $idNum, string $compactId)
    {
        $data = $this->formatQueryWithSign(compact('idType', 'idNum', 'compactId'));

        return $this->httpPostJson('/open-api/webank/evidence/findE', $data);
    }

}