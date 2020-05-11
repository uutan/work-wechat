<?php
/*
 * This file is part of the uutan/work-wechat.
 *
 * (c) uutan <uutan@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WorkWeChat\Kernel\Contracts;

use Psr\Http\Message\RequestInterface;

/**
 * Interface AccessTokenInterface
 * @package WorkWeChat\Kernel\Contracts
 */
interface AccessTokenInterface
{

    /**
     * @return array
     */
    public function getToken(): array;

    /**
     * @return $this
     */
    public function refresh(): self;

    /**
     * @param RequestInterface $request
     * @param array $requestOptions
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface;


}