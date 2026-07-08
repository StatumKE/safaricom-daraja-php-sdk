# Safaricom Daraja API Reference (M-Pesa, IoT, & GSM DTO Contracts)

This reference guide documents every single Request DTO contract in the PHP 8.2+ Safaricom Daraja SDK. It maps SDK-facing constructor properties directly to Safaricom's wire-level API fields.

---

## 1. M-Pesa Express (`StkPushRequest`)

Used to initiate a Customer-to-Business (C2B) payment popup on a subscriber phone.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `businessShortCode` | `BusinessShortCode` | `string` | Yes | Paybill or Till shortcode (e.g. `'174379'`) |
| `password` | `Password` | `string` | Yes | Base64 encoded hash: `base64(Shortcode + Passkey + Timestamp)` |
| `timestamp` | `Timestamp` | `string` | Yes | Format: `YYYYMMDDHHMMSS` (Nairobi timezone) |
| `transactionType` | `TransactionType` | `string` | Yes | Use `'CustomerPayBillOnline'` or `'CustomerBuyGoodsOnline'` |
| `amount` | `Amount` | `int\|string`| Yes | Transaction amount to charge |
| `partyA` | `PartyA` | `string` | Yes | Subscriber phone number sending money (format: `2547XXXXXXXX`) |
| `partyB` | `PartyB` | `string` | Yes | Destination shortcode (same as `businessShortCode`) |
| `phoneNumber` | `PhoneNumber` | `string` | Yes | Subscriber phone number receiving the push (format: `2547XXXXXXXX`) |
| `callBackURL` | `CallBackURL` | `string` | Yes | HTTPS callback URL for Safaricom to POST results |
| `accountReference` | `AccountReference` | `string` | Yes | Short alphanumeric transaction reference (e.g. `'Invoice-102'`) |
| `transactionDesc` | `TransactionDesc` | `string` | Yes | Short description of the transaction |

---

## 2. M-Pesa Express Query (`StkPushQueryRequest`)

Used to query the final state of an M-Pesa Express transaction.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `businessShortCode` | `BusinessShortCode` | `string` | Yes | Organization shortcode (e.g. `'174379'`) |
| `password` | `Password` | `string` | Yes | Base64 encoded hash: `base64(Shortcode + Passkey + Timestamp)` |
| `timestamp` | `Timestamp` | `string` | Yes | Format: `YYYYMMDDHHMMSS` (Nairobi timezone) |
| `checkoutRequestID` | `CheckoutRequestID` | `string` | Yes | The unique Checkout Request ID returned by the initial STK Push |

---

## 3. C2B Payment Simulation (`C2bSimulateRequest`)

Used to test C2B callbacks in the sandbox environment.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `shortCode` | `ShortCode` | `string` | Yes | Destination Paybill or Till shortcode (e.g. `'600984'`) |
| `commandID` | `CommandID` | `string` | Yes | Use `'CustomerPayBillOnline'` or `'CustomerBuyGoodsOnline'` |
| `amount` | `Amount` | `int\|string`| Yes | Simulation transaction amount |
| `msisdn` | `Msisdn` | `int\|string`| Yes | Test customer phone number (format: `2547XXXXXXXX`) |
| `billRefNumber` | `BillRefNumber` | `?string` | No | Required for Paybills. **Must be set to `null`** for Buy Goods Till simulations. |

---

## 4. C2B URL Registration (`C2bRegisterUrlRequest`)

Used to register your confirmation and validation callback endpoints with Safaricom.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `shortCode` | `ShortCode` | `string` | Yes | Paybill or Till shortcode registering URLs |
| `responseType` | `ResponseType` | `string` | Yes | Default callback action: Use `'Completed'` or `'Cancelled'` |
| `confirmationURL` | `ConfirmationURL` | `string` | Yes | HTTPS callback URL for completed transactions. **Cannot contain the word "mpesa" in Sandbox.** |
| `validationURL` | `ValidationURL` | `string` | Yes | HTTPS validation endpoint. **Cannot contain the word "mpesa" in Sandbox.** |

---

## 5. B2B Payments (`B2bPaymentRequest`)

Used to send money from one business shortcode to another business shortcode.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `initiator` | `Initiator` | `string` | Yes | Name of the API initiator username |
| `securityCredential`| `SecurityCredential`| `string`| Yes | Encrypted Base64 security credential from public certificate |
| `commandID` | `CommandID` | `string` | Yes | e.g. `'BusinessPayBill'`, `'BusinessBuyGoods'`, `'DisburseFundsToReceiver'` |
| `senderIdentifierType`| `SenderIdentifierType`| `int` | Yes | Identifies sender (e.g. `4` for Shortcode) |
| `receiverIdentifierType`| `RecieverIdentifierType`| `int` | Yes | Identifies receiver (e.g. `4` for Shortcode) |
| `amount` | `Amount` | `int\|string`| Yes | Payout transaction amount |
| `partyA` | `PartyA` | `string` | Yes | Sending organization shortcode |
| `partyB` | `PartyB` | `string` | Yes | Receiving organization shortcode / Till |
| `accountReference` | `AccountReference` | `string` | Yes | Alphanumeric account reference |
| `remarks` | `Remarks` | `string` | Yes | Remarks / details (max 100 characters) |
| `queueTimeOutURL` | `QueueTimeOutURL` | `string` | Yes | Callback URL for transaction timeout events |
| `resultURL` | `ResultURL` | `string` | Yes | Callback URL where transaction results are POSTed |

---

## 6. B2C Payouts (`B2cPaymentRequest`)

Used to send money from an organization to a customer (e.g., salaries, promotions, payouts).

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `initiatorName` | `InitiatorName` | `string` | Yes | The username of the initiator (e.g., `'testapi'`) |
| `securityCredential`| `SecurityCredential`| `string`| Yes | Encrypted Base64 string from M-Pesa Public Cert |
| `commandID` | `CommandID` | `string` | Yes | Use `'SalaryPayment'`, `'BusinessPayment'`, or `'PromotionPayment'` |
| `amount` | `Amount` | `int\|string`| Yes | Payout transaction amount |
| `partyA` | `PartyA` | `string` | Yes | Organization shortcode sending the payment |
| `partyB` | `PartyB` | `string` | Yes | Destination subscriber phone number (format: `2547XXXXXXXX`) |
| `remarks` | `Remarks` | `string` | Yes | Remarks on the payment (max 100 characters) |
| `queueTimeOutURL` | `QueueTimeOutURL` | `string` | Yes | HTTPS callback URL triggered if the request times out |
| `resultURL` | `ResultURL` | `string` | Yes | HTTPS callback URL where payment status is POSTed |
| `occasion` | `Occasion` | `?string` | No | Optional description / metadata |

---

## 7. B2Pochi Payouts (`B2PochiPaymentRequest`)

Used to send payouts directly from an organization to an individual's Pochi La Biashara wallet.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `originatorConversationID` | `OriginatorConversationID` | `string` | Yes | Unique lookup reference ID generated by client |
| `initiatorName` | `InitiatorName` | `string` | Yes | Initiator username |
| `securityCredential`| `SecurityCredential`| `string`| Yes | Encrypted Base64 credential string |
| `commandID` | `CommandID` | `string` | Yes | Use `'BusinessPayToPochi'` |
| `amount` | `Amount` | `int\|string`| Yes | Transaction amount |
| `partyA` | `PartyA` | `string` | Yes | Organization shortcode |
| `partyB` | `PartyB` | `string` | Yes | Receiver mobile number (format: `2547XXXXXXXX`) |
| `remarks` | `Remarks` | `string` | Yes | Max 100 characters |
| `queueTimeOutURL` | `QueueTimeOutURL` | `string` | Yes | Callback URL for timeouts |
| `resultURL` | `ResultURL` | `string` | Yes | Callback URL for transaction result |
| `occasion` | `Occasion` | `?string` | No | Optional occasion string |

---

## 8. Transaction Reversal (`ReversalRequest`)

Used to reverse an M-Pesa transaction.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `initiator` | `Initiator` | `string` | Yes | Initiator username |
| `securityCredential`| `SecurityCredential`| `string`| Yes | Encrypted Base64 credential string |
| `commandID` | `CommandID` | `string` | Yes | Use `'TransactionReversal'` |
| `transactionID` | `TransactionID` | `string` | Yes | M-Pesa Transaction ID to reverse (e.g. `'OHT1234567'`) |
| `amount` | `Amount` | `int\|string`| Yes | Reversal amount |
| `receiverParty` | `ReceiverParty` | `string` | Yes | The Paybill/Shortcode that received the initial transaction |
| `receiverIdentifierType`| `RecieverIdentifierType`| `int` | Yes | Identifies receiver (e.g. `11` for Organization) |
| `resultURL` | `ResultURL` | `string` | Yes | Callback URL for reversal results |
| `queueTimeOutURL` | `QueueTimeOutURL` | `string` | Yes | Callback URL for timeouts |
| `remarks` | `Remarks` | `string` | Yes | Reversal reason (max 100 characters) |
| `occasion` | `Occasion` | `?string` | No | Optional occasion string |

---

## 9. Account Balance Query (`AccountBalanceRequest`)

Used to query the current balance of an M-Pesa shortcode.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `initiator` | `Initiator` | `string` | Yes | Initiator username |
| `securityCredential`| `SecurityCredential`| `string`| Yes | Encrypted Base64 credential string |
| `commandID` | `CommandID` | `string` | Yes | Use `'AccountBalance'` |
| `partyA` | `PartyA` | `string` | Yes | Organization shortcode to query |
| `identifierType` | `IdentifierType` | `int` | Yes | e.g. `4` for Organization Shortcode |
| `remarks` | `Remarks` | `string` | Yes | Query remarks |
| `queueTimeOutURL` | `QueueTimeOutURL` | `string` | Yes | Timeout callback URL |
| `resultURL` | `ResultURL` | `string` | Yes | Results callback URL |

---

## 10. Transaction Status Query (`TransactionStatusQueryRequest`)

Used to query the status of a specific M-Pesa transaction.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `initiator` | `Initiator` | `string` | Yes | Initiator username |
| `securityCredential`| `SecurityCredential`| `string`| Yes | Encrypted Base64 credential string |
| `commandID` | `CommandID` | `string` | Yes | Use `'TransactionStatusQuery'` |
| `transactionID` | `TransactionID` | `string` | Yes | Transaction ID to query (e.g. `'OHT1234567'`) |
| `partyA` | `PartyA` | `string` | Yes | The shortcode involved in the transaction |
| `identifierType` | `IdentifierType` | `int` | Yes | e.g. `4` for Organization Shortcode |
| `remarks` | `Remarks` | `string` | Yes | Query remarks |
| `queueTimeOutURL` | `QueueTimeOutURL` | `string` | Yes | Timeout callback URL |
| `resultURL` | `ResultURL` | `string` | Yes | Results callback URL |
| `occasion` | `Occasion` | `?string` | No | Optional occasion string |

---

## 11. B2B Hakikisha (`B2bHakikishaRequest`)

Used to lookup and verify organization metadata (name, tariff) before executing a transaction.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `identifierType` | `IdentifierType` | `string` | Yes | Use `'4'` for Organization Shortcode, or `'1'` for MSISDN |
| `identifier` | `Identifier` | `string` | Yes | Organization shortcode or phone number being queried |

---

## 12. Mobile Number Validation / KYC (`MobileNumberValidationRequest`)

Used to verify if a mobile number matches a specific National ID or Passport.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `requestRefID` | `requestRefID` | `string` | Yes | Unique lookup reference ID generated by client |
| `shortCode` | `shortCode` | `string` | Yes | Organization shortcode initiating the lookup |
| `msisdn` | `msisdn` | `string` | Yes | Customer phone number to query (format: `2547XXXXXXXX`) |
| `idType` | `idType` | `string` | Yes | Use `'01'` (National ID), `'02'` (Military ID), or `'05'` (Passport) |
| `idNumber` | `idNumber` | `string` | Yes | Alphanumeric document registration number |

---

## 13. Standing Order External (`StandingOrderExternalRequest`)

Used to create a standing order payment from a customer wallet to a business Paybill.

| SDK Parameter | Wire Key | Type | Required | Description / Constraints |
| :--- | :--- | :--- | :--- | :--- |
| `standingOrderName` | `StandingOrderName` | `string` | Yes | Name of the standing order |
| `businessShortCode` | `BusinessShortCode` | `string` | Yes | Destination shortcode Paybill |
| `transactionType` | `TransactionType` | `string` | Yes | Use `'Standing Order Customer Pay Bill'` |
| `amount` | `Amount` | `int` | Yes | Amount to charge recurringly |
| `partyA` | `PartyA` | `string` | Yes | Customer mobile number (format: `2547XXXXXXXX`) |
| `receiverPartyIdentifierType`| `ReceiverPartyIdentifierType`| `string`| Yes | e.g. `'4'` for Shortcode |
| `callBackURL` | `CallBackURL` | `string` | Yes | Callback URL for standing order setup results |
| `accountReference` | `AccountReference` | `string` | Yes | Alphanumeric account reference |
| `transactionDesc` | `TransactionDesc` | `string` | Yes | Alphanumeric description |
| `frequency` | `Frequency` | `string` | Yes | Use `'Daily'`, `'Weekly'`, `'Monthly'`, `'Quarterly'`, `'BiAnnual'`, or `'Yearly'` |
| `startDate` | `StartDate` | `string` | Yes | Format: `YYYY-MM-DD` |
| `endDate` | `EndDate` | `string` | Yes | Format: `YYYY-MM-DD` |

---

## 14. Single-Field Utility DTOs

These DTO constructors take only a single required property.

### A. IMSI / Age On Network DTOs
- **DTOs**: `ImsiCheckAtiRequest`, `ImsiLookupRequest`, `AgeOnNetworkRequest`, `SwapCheckAtiRequest`
- **Parameter**: `customerNumber` (`string`, required) -> Matches the subscriber phone number (`2547XXXXXXXX`).

### B. Pull Transaction DTOs
- **DTO**: `PullRegisterRequest`
  - Parameters: `shortCode` (`string`), `requestType` (`string`), `nominatedNumber` (`string`), `callBackURL` (`string`).
- **DTO**: `PullQueryRequest`
  - Parameters: `shortCode` (`string`), `startDate` (`string`), `endDate` (`string`), `offsetValue` (`string`).

---

## 15. SIM Portal DTOs

Used for IoT SIM and SMS thread management via the Safaricom SIM Portal APIs.

| DTO Class | Required Parameters | Description |
| :--- | :--- | :--- |
| `SearchMessagesRequest` | `searchValue`, `vpnGroup`, `username` | Search SIM thread messages. |
| `FilterMessagesRequest` | `startDate`, `endDate`, `status`, `vpnGroup`, `username` | Filter message threads by date/status. |
| `DeleteMessageThreadRequest`| `threadId`, `vpnGroup`, `username` | Delete a message thread. |
| `GetAllMessagesRequest` | `vpnGroup`, `username` | Get all messages. |
| `SendSingleMessageRequest` | `msisdn`, `message`, `vpnGroup`, `username` | Send a single message to a SIM. |
| `DeleteMessageRequest` | `messageId`, `vpnGroup`, `username` | Delete a single message by ID. |
| `AllSimsRequest` | `vpnGroup` | List all SIMs in group. |
| `QueryLifecycleStatusRequest`| `msisdn`, `vpnGroup`, `username` | Check SIM active/suspended state. |
| `QueryCustomerInfoRequest` | `msisdn`, `vpnGroup`, `username` | Retrieve customer details. |
| `SimActivationRequest` | `msisdn`, `vpnGroup`, `username` | Request SIM activation. |
| `GetActivationTrendsRequest`| `startDate`, `endDate`, `vpnGroup`, `username` | Get SIM activation trends. |
| `RenameAssetRequest` | `msisdn`, `newName`, `vpnGroup`, `username` | Rename an active SIM asset. |
| `GetLocationInfoRequest` | `msisdn`, `vpnGroup`, `username` | Request SIM location info. |
| `SuspendUnsuspendSubRequest`| `msisdn`, `action`, `vpnGroup`, `username` | Suspend or unsuspend a SIM subscription. |
| `SwapCheckAtiRequest` | `customerNumber` | SWAP Check ATI check. |

---

## 16. Response Inspection (`ApiResponse`)

All SDK helper methods return an instance of `Statum\Safaricom\Daraja\Http\ApiResponse`.

| Method | Return Type | Description |
| :--- | :--- | :--- |
| `json()` | `array` | Returns the decoded JSON response payload as an array. **Throws `ApiException`** if the response body is empty or contains invalid JSON. |
| `decoded()` | `?array` | Returns the decoded JSON payload as an array, or `null` if the body is empty or contains invalid JSON. Does not throw an exception. |
| `statusCode()` | `int` | Returns the HTTP status code returned by the Safaricom gateway (e.g. `200`, `400`, `500`). |
| `headers()` | `array` | Returns an array of HTTP headers returned in the response (keyed by header name). |
| `body()` | `string` | Returns the raw, unparsed response body string. |
| `response()` | `ResponseInterface` | Returns the underlying PSR-7 `Psr\Http\Message\ResponseInterface` object for custom inspection. |

---

## 17. SDK Exception Reference

All package-specific exceptions extend the base `Statum\Safaricom\Daraja\Exception\SafaricomException` class.

### A. `ConfigurationException`
* **When it occurs**: Staggered during local client bootstrapping or DTO instantiation.
* **Common triggers**:
  * Missing `consumerKey` or `consumerSecret` in `SafaricomConfig`.
  * Passing negative values for timeouts.
  * Empty or missing required strings in DTO validation checks (e.g. empty `shortCode`).

### B. `TransportException`
* **When it occurs**: Staggered during network transmission.
* **Common triggers**:
  * DNS resolution failures.
  * Network timeouts.
  * SSL/TLS handshake failures when contacting Safaricom's servers.

### C. `ApiException`
* **When it occurs**: Staggered when Safaricom returns an error status code (>= 400) or malformed response.
* **Common triggers**:
  * Invalid OAuth credentials (HTTP 400).
  * Wrong initiator passwords or bad security credentials (HTTP 500).
  * Incapsula firewall blocks (HTTP 403).
* **Key Method**:
  * `response()`: Returns the underlying `ApiResponse` object (or `null`). Use this to extract detailed wire-level error payloads:
    ```php
    try {
        $response = $client->stkPush($request);
    } catch (ApiException $e) {
        $apiResponse = $e->response();
        if ($apiResponse !== null) {
            $errorCode = $apiResponse->json()['errorCode'] ?? 'Unknown';
            $errorMessage = $apiResponse->json()['errorMessage'] ?? 'Unknown';
        }
    }
    ```
