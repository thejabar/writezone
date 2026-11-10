# WZP-02.1 Notification and Bookmark Findings

**Status:** Static findings and separate-scope correction proposals
**Baseline B:** Both capabilities remain excluded under WZP-01.6/WZP-01.8

## Notification read-state mutation

**VERIFIED IN SOURCE:** `routes/web.php:265-270` maps authenticated GET `/notifications` to `NotificationController::index()`. The controller calls `Notification::markAllRead(Auth::id())` before loading the list (`app/Controllers/NotificationController.php:9-22`). The model executes `UPDATE notifications SET is_read = 1 WHERE user_id = ?` (`app/Models/Notification.php:43-52`). It is a bulk transition of all rows for the authenticated user, not a per-item transition. The read query is user-scoped (`Notification.php:23-41`). The navbar links to `/notifications` (`resources/views/partials/navbar.php:48-50`).

Opening a GET page therefore changes server state. This violates safe/read-only GET semantics and can mark notifications read when navigation is triggered by prefetching or repeated page load. The exact browser/client effect was not tested. The route requires authentication but has no CSRF middleware; that is acceptable for a truly read-only GET, but not for a state-changing action.

**Smallest proposed correction if separately authorized:** remove the mutation from `index()` so GET only reads; add an explicit POST action such as `/notifications/read-all` for bulk state changes. Protect it with `auth`, `ratelimit`, and `csrf`; derive the target user only from the authenticated session; keep it idempotent. If per-item actions are required, use a separately approved resource-scoped route and verify the item belongs to the current user. Do not add notification capability to B by correcting the latent defect.

**Tests required:** GET leaves all read flags and unread counts unchanged; authenticated POST changes only the caller’s notifications; unauthenticated, missing/invalid CSRF, and rate-limited requests do not write; repeating POST is safe; unknown/other-user item identifiers cannot change another user’s state. Database assertions require Layer 3; policy/request tests remain isolated.

**Proposed files:** `routes/web.php`, `app/Controllers/NotificationController.php`, `app/Models/Notification.php`, notification view/form (`resources/views/notifications/index.php`), and a new isolated `tests/NotificationReadSemanticsRegression.php`. No schema change is indicated by the current behavior.

## Bookmark public-ID route mismatch

**VERIFIED IN SOURCE:** `routes/web.php:252-257` accepts only POST IDs matching `[1-9][0-9]*`. The Writ detail form submits `$writ->public_id` (`resources/views/writs/show.php:18-24`). `Writ::generatePublicId()` creates seven characters from `ABCDEFGHJKLMNPQRSTUVWXYZ23456789` (`app/Models/Writ.php:62-88`). `BookmarkController::store()` passes the route argument to `Writ::findByPublicId()` (`app/Controllers/BookmarkController.php:11-18`); that method queries `w.public_id` (`Writ.php:111-128`). The database uses a separate internal numeric `writs.id`; the controller does not resolve the route as that ID.

Therefore, normal generated IDs containing letters cannot match the digit-only route. The request will fail route matching before the controller. A numeric-only seven-character public ID could match the route and still be looked up as `public_id`; there is no source evidence that the controller will resolve the wrong row by internal ID. The issue is a route/lookup contract mismatch, not evidence of IDOR.

**Smallest proposed correction if separately authorized:** define the route constraint from the public-ID generator’s exact alphabet/length (or remove the numeric-only constraint and validate a public ID in the controller), while keeping lookup by public ID. Do not switch to internal numeric IDs. Authentication, rate limit, CSRF, and per-user bookmark ownership remain in force.

**Tests required:** generated IDs with letters and digits reach controller; invalid length/alphabet and unknown IDs fail safely; numeric internal database IDs are not treated as public IDs; repeated bookmark is idempotent; only the authenticated user’s bookmark changes. Route and generator tests can be Layer 1; persistence needs Layer 3.

**Proposed files:** `routes/web.php`, optionally a public-ID validation helper/model method in `app/Models/Writ.php`, `app/Controllers/BookmarkController.php` only if validation needs to move, and a new `tests/BookmarkPublicIdRouteRegression.php`. No schema change is indicated.

## Scope and approval boundary

WZ-01.6 records notifications and bookmarks as excluded/deferred; no correction is a Baseline B prerequisite unless a later Project Owner decision changes scope. WZP-01.8 keeps that boundary. These proposals require their own product, security, test, and implementation approvals. No notification or bookmark code was changed or tested in WZP-02.1.
