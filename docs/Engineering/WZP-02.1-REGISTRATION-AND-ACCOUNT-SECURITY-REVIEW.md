# WZP-02.1 Registration and Account-Security Review

**Status:** Static source review and correction requirements; no code or account data changed
**Scope:** Existing public-account registration and sign-in only; no new recovery or corporate identity capability

## Current source behavior

- `routes/web.php:53-65` exposes GET/POST registration and applies `guest`, `ratelimit`, and `csrf` to POST. Login POST receives the same middleware (`routes/web.php:39-51`). The router runs route middleware before the controller (`core/Routing/Router.php:199-265`).
- `AuthController::register()` (`app/Controllers/AuthController.php:50-85`) trims `username`, derives `handle` by lowercasing and deleting characters outside ASCII letters/digits/underscore, trims `email`, hashes `password` using `PASSWORD_DEFAULT`, inserts through `User::create()`, then authenticates the new ID.
- No explicit server-side required/type/format/length/password validation branch or duplicate-key handling was found in that controller. HTML `required` and `type=email` attributes in `resources/views/auth/register.php:8-45` are client-side only and can be bypassed.
- Handle collisions are possible by derivation: usernames whose only difference is characters removed by the regex can map to the same handle; punctuation-only input can produce an empty handle. This is a source-derived risk, not a reproduced account collision.
- `core/Database/Model.php:31-33` delegates creation to `QueryBuilder::create()` (`core/Database/QueryBuilder.php:95-119`), which prepares/inserts and returns `lastInsertId()` without a duplicate-key result path. Uniqueness constraints are declared for username, email, and handle in both the dated schema snapshot and baseline migration, but D07/live state and collation are unresolved.
- `AuthController::login()` (`AuthController.php:15-41`) searches using the submitted email value as-is, uses `password_verify()`, but returns “User not found.” for an unknown address and “Invalid credentials.” for a wrong password (`lines 22-30`). This exposes an account-existence distinction.
- `Auth::login()` regenerates the session and stores `user_id` (`core/Authentication/Auth.php:11-15`). Password recovery or email-verification routes were not found in the inspected web routes; WZP-01.6 PO-03 excludes self-service password reset from B and requires an appointed support owner to approve any assisted process before beta.

## Required validation policy

The following is a proposed implementation contract, not a new approved product policy:

1. Require scalar strings for every submitted field before calling string functions. Reject arrays, objects, missing values, malformed encodings, and blank required values without inserting a user or authenticating a session.
2. Trim username and email surrounding whitespace consistently on registration and login. Do not trim or silently normalize passwords; preserve passphrase spaces and compare the submitted password with `password_verify()`.
3. Define and approve username/handle allowed characters, lengths, and derivation. Reject a value that produces an empty or invalid handle; do not silently strip data in a way that makes distinct names collide. The existing columns are length 50 for username/handle and 255 for email in the snapshot and migration, but a live database constraint is not established.
4. Choose an email syntax and canonicalization rule, then apply it identically for registration, login, duplicate checks, and uniqueness. Do not assume case sensitivity or insensitive collation; inspect the authorized schema/target before relying on it. Recommend trimming and validating the address; whether to lowercase all or part of the address is a product/security decision because it changes identity matching semantics.
5. Approve a password minimum/maximum and strength policy with Security/Product owners. Use a maximum compatible with the deployed `PASSWORD_DEFAULT` implementation; do not truncate passwords. Keep hashing server-side and never log password material.
6. Enforce limits on the server before persistence, with byte/character/encoding rules aligned to the actual column charset. Return field-level, non-sensitive errors and retain no rejected account or session.
7. Treat database unique constraints as the final race-safe authority only after schema reconciliation. Catch the specific duplicate-key condition and return a generic, safe registration result; do not rely on a prior `SELECT` check alone. Do not add or alter schema until D07 and target evidence are resolved.
8. Keep registration and login responses generic enough not to reveal existing account status. In particular, unknown email and incorrect password must have equivalent public failure wording/status. Do not add email verification, MFA, recovery, or other features without separate product approval.

## Security and product implications

- Invalid or empty usernames/emails/passwords can reach persistence or produce runtime/database errors because validation is missing at the source boundary. The actual accepted input and database result are not runtime-verified.
- Derived-handle collision, empty handle, duplicate registration, and case/whitespace discrepancies can prevent account creation or produce inconsistent sign-in. Database constraints may reject duplicates, but the current controller has no user-facing duplicate path.
- A competing registration request can race any proposed pre-check. Unique constraints and duplicate-key handling are required together.
- Login error text distinguishes unknown and existing accounts. Route rate limits are present by source declaration; their storage isolation and effective request behavior were not tested.
- There is no evidence of email verification or self-service recovery. Do not tell users that these are available. Support-assisted recovery requires the appointed support owner and separate approval.

## Required regression cases

**Pure / isolated validation:** missing fields; whitespace-only values; wrong PHP input types; malformed email; field lengths at limit and one over; Unicode normalization/invalid-byte handling; username characters; empty/colliding derived handles; password boundary and preserved spaces; no session creation on rejected input; generic login failure equivalence; password hash verification; output escaping; CSRF denial; rate-limit behavior.

**Database-backed after Layer 3 approval only:** duplicate email/username/handle against the approved disposable target; simultaneous duplicate submissions to prove unique constraints are race-safe; failure cleanup deletes only the current run's test-owned rows; no user row after any rejected request. Never run against `.env`-selected or shared state.

## Proposed affected-file inventory

Likely code scope, subject to a new exact implementation approval: `app/Controllers/AuthController.php`, `resources/views/auth/register.php`, `resources/views/auth/login.php`, and `tests/RegistrationSecurityRegression.php` (new). `routes/web.php` should remain unchanged unless an approved finding proves the existing CSRF/rate-limit boundary is incomplete. A database migration is not currently proposed; any need requires verified schema evidence and separate approval.
