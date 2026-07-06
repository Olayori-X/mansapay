# MansaPay

MansaPay is the backend for a campaign-based fundraising/collection platform. It's the API layer behind a payments product — it handles user onboarding, campaign (payment link) management, and moving money in and out via card, bank transfer, and payout rails.

Built with **Laravel 11**, **Sanctum** token authentication, and integrated with the **Payaza** payments API for virtual account creation, card charges, transfer confirmation, and payouts.

## What it does

- **Users** sign up, verify their email via a 6-digit OTP, and log in to receive a Sanctum API token.
- **Campaigns** (stored as `paymentlinks`) are payment collection pages a verified user can create, edit, and delete — each with a unique `formid` that's shared with payers.
- **Payments** can be made against a campaign three ways:
  - a **virtual bank account** created on the fly via Payaza, which is later confirmed once the transfer lands
  - a **direct card charge** through Payaza's card processor
  - a manual `makePayment` record (used for already-confirmed/external payments)
- **Funds** (stored as `transactions`) track every credit and debit against a user's balance — campaign payments in, withdrawals out — so a user can pull a full transaction history.
- **Withdrawals** let a verified user cash out their balance to their linked bank account via a Payaza payout.

## Why it's built this way

**Payer flows don't require login.** Anyone with a campaign's `formid` can view it and pay against it — `getCampaignById`, `initiateBankTransfer`, `confirmBankTransfer`, and `cardPayment` are all public routes. Only the campaign *owner's* actions (creating, editing, deleting campaigns, withdrawing funds, viewing history) sit behind `auth:sanctum`. This mirrors how payment link products actually work: the person paying shouldn't need an account.

**Expired campaigns self-delete on read.** `getCampaignById` checks the campaign's due date against the current time and deletes it in the same request if it's expired, rather than running a separate cleanup job. Simple, but it means campaign lifetime is enforced at the point of access instead of relying on a scheduler.

**Two-request confirmation for bank transfers.** Because a bank transfer isn't instant, `initiateBankTransfer` only creates a virtual account and a pending `Payment` row (`paid = false`). A separate `confirmBankTransfer` call — designed to be polled or triggered by a webhook — checks the transaction status with Payaza and only then marks the payment paid, logs the fund credit, and updates the user's balance. This keeps "money requested" and "money received" as two distinct, auditable states instead of assuming a transfer succeeded the moment it was requested.

**A shared trait for outbound API calls.** `FunctionsTrait::sendPostRequest` / `sendGetRequest` centralizes the Payaza request headers and auth so every controller that talks to Payaza (`PaymentController`, `FundManagementController`) goes through one place, rather than duplicating HTTP client setup per method.

**Every controller method is wrapped in try/catch.** Payment endpoints in particular fail in a lot of ways — a third-party API timing out, a malformed payload, a missing campaign — and each of those returns a consistent `{ response, message }` shape rather than a raw 500. That consistency matters more here than in a typical CRUD app, since the frontend needs to reliably tell "your card was declined" apart from "our server broke."

## Tech stack

- PHP 8.2 / Laravel 11
- Laravel Sanctum (API token authentication)
- Eloquent ORM
- Payaza API (virtual accounts, card charges, transfer confirmation, payouts)
- Laravel Mail (OTP delivery)

## API overview

| Method | Endpoint | Auth | Purpose |
|---|---|---|---|
| POST | `/signup` | – | Create a user, send OTP to email |
| POST | `/verifyotp` | – | Verify email with OTP |
| POST | `/login` | – | Authenticate, get Sanctum token |
| POST | `/completeprofile` | ✅ | Attach bank account details |
| POST | `/getuserprofile` | ✅ | Fetch the logged-in user's profile |
| POST | `/logout` | ✅ | Revoke current token |
| POST | `/addcampaign` | ✅ | Create a campaign / payment link |
| POST | `/getcampaigns` | ✅ | List a user's campaigns |
| POST | `/getcampaignstatus` | ✅ | Get a campaign's payment status |
| POST | `/editcampaign` | ✅ | Edit a campaign |
| POST | `/deletecampaign` | ✅ | Delete a campaign |
| POST | `/getcampaignbyid` | – | Public campaign lookup (for payers) |
| POST | `/initiatebanktransfer` | – | Create virtual account for a bank transfer |
| POST | `/confirmbanktransfer` | – | Confirm a transfer landed and credit funds |
| POST | `/cardpayment` | – | Charge a card directly |
| POST | `/makepayment` | – | Record an externally-confirmed payment |
| POST | `/withdraw` | ✅ | Withdraw balance to linked bank account |
| POST | `/gettransactionhistory` | ✅ | Fetch a user's fund history |

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Add your Payaza API key to `.env` (see note below) and any mail driver credentials for OTP delivery, then:

```bash
php artisan serve
```

> **Note:** the Payaza API key currently lives inline in `FunctionsTrait.php`. If you're setting this up from scratch, move it into `config/services.php` and reference it via `.env` instead — see the security note below.

## Known limitations / next steps

- Bank transfer confirmation currently relies on the client polling `/confirmbanktransfer`; a Payaza webhook receiver would be more reliable than polling.
- The Payaza API key should be moved out of source (`FunctionsTrait.php`) and into environment config — it's currently hardcoded, which isn't safe for a public repo.
- No automated tests currently cover the payment or campaign flows — the default Laravel test scaffolding is still in place.
