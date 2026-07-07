<?php

declare(strict_types=1);

namespace Statum\Safaricom\Daraja\Client;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Statum\Safaricom\Daraja\Contract\RequestDtoInterface;
use Statum\Safaricom\Daraja\Config\SafaricomConfig;
use Statum\Safaricom\Daraja\Dto\Request\AgeOnNetworkRequest;
use Statum\Safaricom\Daraja\Dto\Request\ImsiCheckAtiRequest;
use Statum\Safaricom\Daraja\Dto\Request\ImsiLookupRequest;
use Statum\Safaricom\Daraja\Dto\Request\SwapCheckAtiRequest;
use Statum\Safaricom\Daraja\Exception\ApiException;
use Statum\Safaricom\Daraja\Exception\TransportException;
use Statum\Safaricom\Daraja\Http\AccessToken;
use Statum\Safaricom\Daraja\Http\ApiResponse;

final class SafaricomClient
{
    private ?AccessToken $accessToken = null;

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly SafaricomConfig $config
    ) {
    }

    public static function create(SafaricomConfig $config, ?ClientInterface $httpClient = null): self
    {
        $httpClient ??= new Client([
            'base_uri' => $config->environment->baseUri(),
            'timeout' => $config->timeout,
            'connect_timeout' => $config->connectTimeout,
        ]);

        return new self($httpClient, $config);
    }

    public function accessToken(bool $forceRefresh = false): AccessToken
    {
        if ($forceRefresh || $this->accessToken === null || $this->accessToken->isExpired()) {
            $response = $this->request(
                'GET',
                Endpoints::OAUTH_TOKEN,
                query: ['grant_type' => 'client_credentials'],
                bearer: false,
                auth: [$this->config->consumerKey, $this->config->consumerSecret]
            );

            $data = $response->json();

            if (!isset($data['access_token'], $data['expires_in'])) {
                throw ApiException::invalidResponse('OAuth response did not contain access_token and expires_in.', $response);
            }

            $expiresIn = (int) $data['expires_in'];
            $this->accessToken = new AccessToken(
                (string) $data['access_token'],
                $expiresIn,
                new DateTimeImmutable(sprintf('+%d seconds', $expiresIn))
            );
        }

        return $this->accessToken;
    }

    /**
     * @param RequestDtoInterface|array<string, mixed> $payload
     * @param array<string, mixed> $query
     * @param array<string, string> $headers
     * @param array{0:string,1:string}|null $auth
     */
    public function request(
        string $method,
        string $path,
        RequestDtoInterface|array $payload = [],
        array $query = [],
        array $headers = [],
        bool $bearer = true,
        ?array $auth = null
    ): ApiResponse {
        $requestHeaders = array_merge(
            ['Accept' => 'application/json'],
            $this->config->defaultHeaders,
            $headers
        );

        if ($bearer) {
            $requestHeaders['Authorization'] = $this->accessToken()->authorizationHeader();
        }

        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => $requestHeaders,
        ];

        if ($query !== []) {
            $options[RequestOptions::QUERY] = $query;
        }

        $normalizedPayload = $this->normalizePayload($payload);

        if ($normalizedPayload !== []) {
            $options[RequestOptions::JSON] = $normalizedPayload;
        }

        if ($auth !== null) {
            $options[RequestOptions::AUTH] = $auth;
        }

        try {
            $response = $this->httpClient->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $exception) {
            throw new TransportException(
                sprintf('Failed to send %s request to "%s".', strtoupper($method), $path),
                0,
                $exception
            );
        }

        $apiResponse = ApiResponse::fromResponse($response);

        if ($apiResponse->statusCode() >= 400) {
            throw ApiException::httpError($apiResponse);
        }

        return $apiResponse;
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, string> $headers
     */
    public function get(string $path, array $query = [], array $headers = [], bool $bearer = true): ApiResponse
    {
        return $this->request('GET', $path, [], $query, $headers, $bearer);
    }

    /**
     * @param RequestDtoInterface|array<string, mixed> $payload
     * @param array<string, mixed> $query
     * @param array<string, string> $headers
     */
    public function post(string $path, RequestDtoInterface|array $payload = [], array $query = [], array $headers = [], bool $bearer = true): ApiResponse
    {
        return $this->request('POST', $path, $payload, $query, $headers, $bearer);
    }

    public function stkPush(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::STK_PUSH, $payload);
    }

    public function stkPushQuery(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::STK_PUSH_QUERY, $payload);
    }

    public function c2bSimulate(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::C2B_SIMULATE, $payload);
    }

    public function c2bRegisterUrl(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::C2B_REGISTER_URL, $payload);
    }

    public function b2bPaymentRequest(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::B2B_PAYMENT, $payload);
    }

    public function b2cPaymentRequest(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::B2C_PAYMENT, $payload);
    }

    public function b2PochiPaymentRequest(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::B2C_PAYMENT, $payload);
    }

    public function reversalRequest(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::REVERSAL, $payload);
    }

    public function accountBalanceQuery(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::ACCOUNT_BALANCE, $payload);
    }

    public function transactionStatusQuery(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::TRANSACTION_STATUS, $payload);
    }

    public function imsiCheckAtiV1(ImsiCheckAtiRequest $payload): ApiResponse
    {
        return $this->post(Endpoints::IMSI_V1_CHECK_ATI, $payload);
    }

    public function imsiCheckAtiV2(ImsiLookupRequest $payload): ApiResponse
    {
        return $this->post(Endpoints::IMSI_V2_CHECK_ATI, $payload);
    }

    public function ageOnNetwork(AgeOnNetworkRequest $payload): ApiResponse
    {
        return $this->post(Endpoints::IMPLICIT_CHECK_ATI, $payload);
    }

    public function pullRegister(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::PULL_REGISTER, $payload);
    }

    public function pullQuery(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::PULL_QUERY, $payload);
    }

    public function b2bHakikisha(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SFC_VERIFY, $payload);
    }

    public function mobileNumberValidation(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::MOB_NUMBER_VALIDATION, $payload);
    }

    public function standingOrderExternal(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::STANDING_ORDER_EXTERNAL, $payload);
    }

    public function searchMessages(RequestDtoInterface $payload, int $pageNo = 1, int $pageSize = 5): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_SEARCH_MESSAGES, $payload, [
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
        ]);
    }

    public function filterMessages(RequestDtoInterface $payload, int $pageNo = 1, int $pageSize = 10): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_FILTER_MESSAGES, $payload, [
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
        ]);
    }

    public function deleteMessageThread(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_DELETE_THREAD, $payload);
    }

    public function getAllMessages(RequestDtoInterface $payload, int $pageNo = 1, int $pageSize = 10): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_GET_ALL_MESSAGES, $payload, [
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
        ]);
    }

    public function sendSingleMessage(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_SEND_SINGLE_MESSAGE, $payload);
    }

    public function deleteMessage(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_DELETE_MESSAGE, $payload);
    }

    public function allSims(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_ALL_SIMS, $payload);
    }

    public function queryLifecycleStatus(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_QUERY_LIFECYCLE, $payload);
    }

    public function queryCustomerInfo(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_QUERY_CUSTOMER_INFO, $payload);
    }

    public function simActivation(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_SIM_ACTIVATION, $payload);
    }

    public function getActivationTrends(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_ACTIVATION_TRENDS, $payload);
    }

    public function renameAsset(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_RENAME_ASSET, $payload);
    }

    public function getLocationInfo(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_GET_LOCATION_INFO, $payload);
    }

    public function suspendUnsuspendSub(RequestDtoInterface $payload): ApiResponse
    {
        return $this->post(Endpoints::SIMPORTAL_SUSPEND_UNSUSPEND, $payload);
    }

    public function swapCheckAti(SwapCheckAtiRequest $payload): ApiResponse
    {
        return $this->post(Endpoints::IMSI_V2_CHECK_ATI, $payload);
    }

    public function refreshAccessToken(): AccessToken
    {
        return $this->accessToken(true);
    }

    /**
     * @param RequestDtoInterface|array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function normalizePayload(RequestDtoInterface|array $payload): array
    {
        if ($payload instanceof RequestDtoInterface) {
            return $payload->toArray();
        }

        return $payload;
    }
}
