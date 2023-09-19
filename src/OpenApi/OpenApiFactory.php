<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ArrayObject;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        /**@var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);

        $openApi = $openApi->withSecurity([['bearerAuth' => []]]);

        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Credentials'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'test@gmai.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '1234',
                ],
            ],
        ]);

        $schemas['Token'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'refresh_token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);

        $schemas['RefreshToken'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'refresh_token' => [
                    'type' => 'string',
                ],
            ],
        ]);

        $responses = [
            '200' => [
                'description' => 'JWT and refresh token generated.',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/Token',
                        ],
                    ],
                ],
            ],
        ];

        $credentials = new ArrayObject([
            'application/json' => [
                'schema' => [
                    '$ref' => '#/components/schemas/Credentials',
                ],
            ],
        ]);

        $refresh_token = new ArrayObject([
            'application/json' => [
                'schema' => [
                    '$ref' => '#/components/schemas/RefreshToken',
                ],
            ],
        ]);

        $loginRequestBody = new RequestBody('Credentials', $credentials);

        $login = new Operation('postApiLogin', ['Auth'], $responses, 'Generate tokens.', '', null, [], $loginRequestBody);

        $loginItem = new PathItem('Login', null, null, null, null, $login);

        $refreshTokenRequestBody = new RequestBody('Refresh token', $refresh_token);

        $refreshToken = new Operation('postApiRefreshToken', ['Auth'], $responses, 'Refresh JWT.', '', null, [], $refreshTokenRequestBody);

        $refreshTokenItem = new PathItem('Refresh Token', null, null, null, null, $refreshToken);

        $openApi->getPaths()->addPath('/api/login', $loginItem);

        $openApi->getPaths()->addPath('/api/token/refresh', $refreshTokenItem);

        return $openApi;
    }
}
