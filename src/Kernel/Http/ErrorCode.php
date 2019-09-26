<?php

/**
 * This file is part of the iyin/cloud-sign.
 *
 * (c) jesse <jesse7866@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Kernel\Http;

trait ErrorCode
{
    public $errorCode = [
        0 => '请求成功',

        400 => '请求参数异常',
        500 => '服务器异常',

        1024 => '请求凭证不合法',
        1017 => '签名校验失败',
        1101 => '微服务返回失败',
        2001 => '请求的内容不存在',
        2002 => '密码错误次数已达上限，请稍后再试',
        2003 => '用户或密码错误',
        2004 => '账户已删除',
        2005 => '请输入正确的验证码',
        2006 => '验证码使用类型错误',
        2007 => '验证码失效，请重新获取',
        2008 => '手机号码有误，请重新输入',
        2009 => '当天验证码获取次数已到达上限，请第二天重新获取',
        2010 => '邮箱地址格式有误，请重新输入',
        2011 => '短信发送失败',
        2012 => '邮件发送失败',
        2013 => '密码长度有误，请重新输入',
        2014 => '密码格式有误，请重新输入',
        2015 => '该邮箱已注册，请直接登录',
        2016 => '账号注册失败',
        2017 => '上传合同文件格式不支持',
        2023 => '该手机号已注册，请直接登录',
        2088 => '该手机账号不存在',
        2089 => '获取验证码类型错误',
        40307 => '请求 token 有误',
        10006 => '用户信息获取失败',
        10015 => '该应用已删除',
        10014 => '该应用已经停用',

        40001 => '请求参数异常，请检查后重试',

        50001 => '服务器异常',
    ];

}