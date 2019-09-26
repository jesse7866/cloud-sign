<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\OpenSign;

use IYin\CloudSign\Kernel\BaseClient;
use IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException;

class Platform extends BaseClient
{
    /**
     * 单文件单页-单签章-签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function singleSign(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/singleSign', $data);
    }

    /**
     * 单文件多页-单签章-坐标签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function batchSignCoordinate(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/batchSignCoordinate', $data);
    }

    /**
     * 单文件骑缝-单签章-坐标签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function perforationSign(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/perforationSign', $data);
    }

    /**
     * 单文件单页-多签章-签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function singleSignMore(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/singleSignMore', $data);
    }

    /**
     * 单文件多页-多签章-签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function batchSignCoordinateMore(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/batchSignCoordinateMore', $data);
    }

    /**
     * 多文件单页-单签章-签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function batchFileSign(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/batchFileSign', $data);
    }

    /**
     * 多文件多页-单签章-签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function batchFileBathSignCoordinate(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/batchFileBathSignCoordinate', $data);
    }

    /**
     * 多文件骑缝-单签章-签章接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function batchFilePerforationSignCoordinate(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/batchFilePerforationSignCoordinate', $data);
    }
}