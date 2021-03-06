<?php
/**
 * Copyright 2016 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *  http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Amazon\Payment\Gateway\Http\Client;


/**
 * Class Client
 * Amazon Pay authorization gateway client
 */
class AuthorizeClient extends AbstractClient
{

    /**
     * @inheritdoc
     */
    protected function process(array $data)
    {
        $response = $this->adapter->authorize($data, false);

        if (!$response['attempts'] && $response['auth_mode'] != 'synchronous' && isset($response['response_code'])
            && $response['response_code'] == 'TransactionTimedOut') {
            $response = $this->adapter->authorize($data, false, $attempts = 1);
        }

        return $response;
    }
}
