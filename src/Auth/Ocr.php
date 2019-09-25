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

class Ocr extends BaseClient
{
    /**
     * 企业执照 OCR 识别
     *
     * @param array|string $files
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function bizLicense($files)
    {
        $token = $this->getToken();
        $files = is_array($files) ? $files : ['file' => $files];

        return $this->httpUploadWithToken('/zuul/open-api/api/platform/compact/upload/file', $files, [], compact('token'));
    }

    /**
     * 身份证 OCR 识别
     *
     * @param array|string $files
     * @param string $type
     *
     * @return array|\IYin\CloudSign\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function identification($files, string $type)
    {
        $token = $this->getToken();
        $files = is_array($files) ? $files : ['file' => $files];

        return $this->httpUploadWithToken('/zuul/open-api/ocr/identification', $files, [], compact('type', 'token'));
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