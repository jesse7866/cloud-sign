<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>

 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign;

use IYin\CloudSign\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @author Jesse
 *
 * @property \IYin\CloudSign\Auth\Company                  $auth_company
 * @property \IYin\CloudSign\Auth\Ocr                      $ocr
 * @property \IYin\CloudSign\Auth\Person                   $auth_person
 * @property \IYin\CloudSign\Base\AccessToken              $access_token
 * @property \IYin\CloudSign\Base\Client                   $base
 * @property \IYin\CloudSign\ContractEvidence\Client       $contract_evidence
 * @property \IYin\CloudSign\ContractSign\Client           $contract_sign
 * @property \IYin\CloudSign\OpenSign\Fast                 $fast_sign
 * @property \IYin\CloudSign\OpenSign\Platform             $platform_sign
 * @property \IYin\CloudSign\Seal\Client                   $seal
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Base\ServiceProvider::class,
        ContractEvidence\ServiceProvider::class,
        ContractSign\ServiceProvider::class,
        OpenSign\ServiceProvider::class,
        Seal\ServiceProvider::class,

    ];
}
