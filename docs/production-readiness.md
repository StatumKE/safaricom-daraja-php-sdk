# Production Readiness Checklist

Verification date: 2026-07-07

## Source Inputs

- Safaricom public APIs portal: https://developer.safaricom.co.ke/apis
- Safaricom public Daraja API pages under `https://developer.safaricom.co.ke/apis/{api}`
- Supplied Safaricom Postman collection: `/Users/statum/Downloads/Safaricom APIs.postman_collection.json`
- Composer package schema: https://getcomposer.org/doc/04-schema.md
- Laravel package discovery documentation: https://laravel.com/docs/packages
- Guzzle request testing documentation: https://docs.guzzlephp.org/en/stable/testing.html
- PHPUnit supported versions: https://phpunit.de/supported-versions.html

The public Safaricom pages are the project source of truth for API availability. The supplied collection is the source used for exact endpoint paths and request payload keys because it contains the concrete request examples.

## Contract Coverage

- OAuth token generation is implemented and tested with HTTP Basic auth.
- All endpoints in the supplied Safaricom collection have DTO coverage.
- DTO `toArray()` output is tested against the exact Safaricom payload keys from the collection.
- Client helper methods are typed to their concrete request DTOs.
- Client helper routing is tested against the exact endpoint paths from the collection.
- Query string handling is tested for SIM portal paginated endpoints.
- IMSI v2 Lookup is routed to `/imsi-lookup/v1/checkATI`.
- SWAP CheckATI is routed to `/imsi/v2/checkATI`.

## Package Readiness

- PHP requirement is `^8.2`.
- HTTP transport uses Guzzle 7.
- Package autoloading uses PSR-4.
- The SDK core is framework-agnostic.
- Laravel support is optional through service provider auto-discovery.
- Laravel config uses `SAFARICOM_*` environment variables by default.
- GitHub Actions validates PHP `8.2`, `8.3`, `8.4`, and `8.5`.
- Packagist metadata is included in `composer.json`.
- Public docs include field-level endpoint reference and copy-paste endpoint examples.

## Required Release Gates

Run these before tagging a release:

```bash
composer validate --strict --no-check-publish
composer test
composer analyse
composer audit
git diff --check
```

## Production Deployment Requirements

These are operational requirements outside the SDK repository:

- Confirm the Safaricom app has production credentials for the target product.
- Confirm callbacks are HTTPS and publicly reachable by Safaricom.
- Confirm each callback endpoint logs raw payloads, validates source expectations, and is idempotent.
- Confirm queue timeout and result callback URLs are monitored.
- Confirm initiator passwords and certificates are stored in a secrets manager, not source control.
- Confirm the production passkey, shortcode, till/paybill, and command IDs are approved for the merchant.
- Run at least one Safaricom sandbox test per endpoint used by the application.
- Run production smoke tests only after Safaricom go-live approval.

## Current Status

The package is ready for application integration and sandbox verification.

Production use requires live Safaricom credentials, approved products, and callback validation in the consuming application.
