<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\ContractSign;

use IYin\CloudSign\Kernel\BaseClient;
use IYin\CloudSign\Kernel\Support\Arr;
use IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException;
use IYin\CloudSign\Kernel\Http\StreamResponse;

class Client extends BaseClient
{
    /**
     * 合同文件上传接口.
     *
     * @param array|string $files
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function uploadFile($files)
    {
        $token = $this->getToken();
        $files = is_array($files) ? $files : ['file' => $files];

        return $this->httpUploadWithToken('/zuul/open-api/api/platform/compact/upload/file', $files, [], compact('token'));
    }

    /**
     * 合同附件上传接口.
     *
     * @param array|string $files
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function uploadAccessory($files)
    {
        $token = $this->getToken();
        $files = is_array($files) ? $files : ['file' => $files];

        return $this->httpUploadWithToken('/zuul/open-api/api/platform/compact/upload/accessory', $files, [], compact('token'));
    }

    /**
     * 发起合同接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function launch(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/api/platform/compact/publicInfo', $data);
    }

    /**
     * 合同详情接口.
     *
     * @param string $compactId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function detail(string $compactId)
    {
        $data = $this->formatQueryWithSign(compact('compactId'));

        return $this->httpGet('/open-api/api/platform/compact/detail', compact('compactId'), $data);
    }

    /**
     * 合同文件下载接口.
     *
     * @param string $compactId
     *
     * @return \IYin\CloudSign\Kernel\Http\Response
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function download(string $compactId)
    {
        $data = [
            'headers' => ['Content-Type' => 'application/octet-stream;'],
            'json' => $this->formatQueryWithSign(compact('compactId')),
        ];
        $response = $this->requestRaw('/open-api/api/platform/compact/package/download', 'POST', $data);

        return StreamResponse::buildFromPsrResponse($response);
    }

    /**
     * 合同文件转为图片接口.
     *
     * @param string $fileCode
     * @param string $pageNo
     *
     * @return \IYin\CloudSign\Kernel\Http\Response
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function convertImage(string $fileCode, string $pageNo)
    {
        $data = [
            'headers' => ['Content-Type' => 'image/jpg'],
            'query' => compact('fileCode', 'pageNo'),
            'json' => $this->formatQueryWithSign(compact('fileCode', 'pageNo')),
        ];

        $response = $this->requestRaw('/open-api/api/platform/compact/file/page', 'GET', $data);

        return StreamResponse::buildFromPsrResponse($response);
    }

    /**
     * 合同催签接口.
     *
     * @param string $compactId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function urgeSign(string $compactId)
    {
        $data = $this->formatQueryWithSign(compact('compactId'));

        return $this->httpPostJson('/open-api/api/platform/info/urge/sign', $data);
    }

    /**
     * 合同签署套餐余额次数校验接口.
     *
     * @param string $compactId
     * @param int    $needTimes
     * @param string $operate
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function checkChooseConsumer(string $compactId, int $needTimes, string $operate)
    {
        $data = $this->formatQueryWithSign(compact('compactId', 'needTimes', 'operate'));

        return $this->httpPostJson('/open-api/contract/check/choose/consumer', $data);
    }

    /**
     * 合同签署接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function sign(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/contract/signature/field', $data);
    }

    /**
     * 合同平台列表查询接口.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function list(array $data = [])
    {
        $query = [
            'pageSize' => Arr::get($data, 'pageSize', 10),
            'pageNum' => Arr::get($data, 'pageNum', 1),
        ];
        $data = $this->formatQueryWithSign($data);

        return $this->httpGet('/open-api/api/platform/compact/page/info', $query, $data);
    }

    /**
     * 文件转换 PDF 接口.
     *
     * @param array|string $files
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function fileToPdf($files)
    {
        $token = $this->getToken();
        $files = is_array($files) ? $files : ['file' => $files];

        return $this->httpUploadWithToken('/zuul/open-api/file/upload/topdf', $files, [], compact('token'));
    }

    /**
     * 生成待签署合同短链接.
     *
     * @param string $compactId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function createShortLinkForWaitSign(string $compactId)
    {
        $data = $this->formatQueryWithSign(compact('compactId'));

        return $this->httpPostJson('/open-api/common/sms/shortlink/waitsign/'.$compactId, $data);
    }

    /**
     * 发送待签署短链接短信通知.
     *
     * @param string $contractId
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function createShortLinkForSms(string $contractId)
    {
        $data = $this->formatQueryWithSign(compact('contractId'));

        return $this->httpPostJson('/open-api/common/sms/sentContractMsg', $data);
    }

    /**
     * 关键字查找签署坐标.
     *
     * @param array $data
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function getKeywordCoordinate(array $data)
    {
        $data = $this->formatQueryWithSign($data);

        return $this->httpPostJson('/open-api/sign/tool/getKeywordCoordinate', $data);
    }

    /**
     * 重新发起签约.
     *
     * @param string $compactId
     * @param string $isOriginal
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function relaunchSign(string $compactId, string $isOriginal = '0')
    {
        $data = $this->formatQueryWithSign(compact('compactId', 'isOriginal'));

        return $this->httpPostJson('/open-api/contract/basic/info/detail/'.$compactId, $data);
    }

    /**
     * 合同撤销.
     *
     * @param string $contractId
     * @param string $remark
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function revocation(string $contractId, string $remark)
    {
        $data = $this->formatQueryWithSign(compact('contractId', 'remark'));

        return $this->httpPostJson('/open-api/contract/info/status/revocation', $data);
    }

    /**
     * 合同拒签.
     *
     * @param string $contractId
     * @param string $remark
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function refuse(string $contractId, string $remark)
    {
        $data = $this->formatQueryWithSign(compact('contractId', 'remark'));

        return $this->httpPostJson('/open-api/contract/info/status/refuse', $data);
    }

    protected function getToken(&$data = [])
    {
        if (isset($data['token']) && !empty($data['token'])) {
            $token = $data['token'];
            unset($data['token']);
        } else {
            $token = $this->accessToken->getAccessToken();
        }

        return $token;
    }

}