# WZP-02.1 Authorization and Access-Control Review

**Status:** Source-level correction specification; no runtime change authorized
**Evidence boundary:** Static inspection only; no PHP request or database test executed

## Request and authorization flow

1. `public_html/index.php:30-36` registers the exception boundary, starts the session, and invokes the application bootstrap.
2. `bootstrap/app.php:19-40` registers `request_boundary` as global middleware and maps the `auth`, `guest`, `csrf`, and `ratelimit` aliases before route dispatch.
3. `core/Routing/Router.php:199-265` merges global middleware before route middleware and invokes the controller only after the middleware pipeline continues.
4. `app/Middleware/AuthMiddleware.php:13-21` checks `Auth::check()`, which is a session `user_id` presence check (`core/Authentication/Auth.php:22-30`). `Auth::login()` regenerates the session and stores the user ID (`Auth.php:11-15`). This path establishes a public-account session; no role catalogue or moderator/admin permission resolution was found in the inspected app policies/routes. `routes/web.php:299-302` labels the Admin section as future/reserved.
5. Controller code loads the resource through a model query and calls `Authorization::allows()` with the authenticated user ID and returned object.
6. `core/Authorization/Authorization.php:13-25` instantiates the named policy, denies unknown ability names, then invokes the policy method with the same object.

This is an owner-based public-account authorization path. It is not evidence of corporate IAM, administrative RBAC, or moderator privileges.

## Confirmed subject-contract mismatch

| Resource/action | Controller route and call | Actual query result | Policy parameter | Static result |
|---|---|---|---|---|
| Writ edit | GET `/writs/{id}/edit`; `WritController::edit()` at lines 93-114 | `Writ::findByPublicId()` returns `?object` using default `fetchObject()` (`app/Models/Writ.php:111-128`), i.e. a generic PDO row object, not an instance of `App\Models\Writ`. | `WritPolicy::update(int|string, Writ)` (`app/Policies/WritPolicy.php:10-13`). | PHP rejects the argument before the owner comparison runs. |
| Writ update | POST `/writs/{id}/update`; `WritController::update()` at lines 118-173 | Same `findByPublicId()` result. | Same `WritPolicy::update()`. | The call fails before `Writ::updateById()`; no update is reached on this path. |
| Writ delete | POST `/writs/{id}/delete`; `WritController::delete()` at lines 175-223 | Same generic row result. | `WritPolicy::delete(int|string, Writ)` (`WritPolicy.php:15-18`). | The call fails before `Writ::deleteById()`; delete is excluded from B in any case. |
| Comment edit | GET `/comments/{id}/edit`; `CommentController::edit()` at lines 120-143 | `Comment::findWithAuthor()` calls `fetch(PDO::FETCH_OBJ)` (`app/Models/Comment.php:71-91`), returning a generic row object. | `CommentPolicy::update(int|string, Comment)` (`app/Policies/CommentPolicy.php:10-13`). | PHP rejects the argument before the owner comparison. |
| Comment update | POST `/comments/{id}/update`; `CommentController::update()` at lines 145-190 | `Comment::find()` delegates to `Model::find()` / `QueryBuilder::find()`, whose `fetchObject()` returns a generic row object (`core/Database/Model.php:19-22`; `core/Database/QueryBuilder.php:73-82`). | Same `CommentPolicy::update()`. | The call fails before `Comment::updateById()`. |
| Comment delete | POST `/comments/{id}/delete`; `CommentController::delete()` at lines 192-233 | Same generic object from `Comment::find()`. | `CommentPolicy::delete(int|string, Comment)` (`CommentPolicy.php:15-18`). | The call fails before `Comment::deleteById()`. |

These locations were verified in source. The runtime exception message/status was not reproduced. `public_html/index.php:30` registers `ErrorHandler`; `core/Error/ErrorHandler.php:42-45,55-75` handles uncaught throwables, but the actual configured debug state and response were not checked.

## Impact and security assessment

- **Confirmed defect:** the queried subject object class does not satisfy the policy's concrete model type declaration.
- **Likely functional result:** `TypeError` occurs at the typed policy call for both owner and non-owner requests. The test `tests/AuthorizationRegression.php:24-72` uses manually constructed `new Writ()` and `new Comment()` subjects, so its current 10 direct assertions do not exercise the PDO row representation or controller routes.
- **Fail-closed inference:** because the exception occurs before the policy boolean and before the subsequent update/delete calls, this source path does not reach the protected mutation. This is not a reproduced runtime claim and does not rule out other entry points.
- **Product impact:** Writ edit is within approved Baseline B. Its failure would block a required journey. Comment editing/deletion and Writ deletion are outside B, but remain source paths that should not carry broken authorization contracts.
- **Privilege escalation:** no direct bypass was found in the traced path. Do not label this an authorization bypass or assert exploitability without request-level evidence.
- **Adjacent gap:** routes protect Writ/Comment mutations with `auth`, `ratelimit`, and `csrf` (`routes/web.php:124-135,171-204`); edit GET routes use `auth`. This is source composition only. There is no role-based admin/moderator policy in the inspected application paths.

## Recommended minimal correction

Align both policy subject parameters with the actual repository return contract. The minimal proposal is an explicit `object` subject plus a guarded owner-ID extraction that returns `false` if `user_id` is absent or invalid; do not accept arbitrary arrays/scalars, assume a missing owner, or remove the owner comparison. Keep public-ID lookup, default-deny unknown abilities, middleware, CSRF, and rate limits unchanged. A stronger typed authorization-projection DTO is an alternative if the technical owner prefers explicit compile-time domain contracts, but it expands the file inventory and must be separately reviewed.

Avoid changing all database hydration semantics or making controllers bypass policies. No model-wide hydration refactor is justified by this finding alone.

## Proposed regression evidence

1. Exercise each policy with the same generic object shape returned by PDO: owner allowed, non-owner denied, and string/numeric IDs handled consistently.
2. Missing, null, non-numeric, or absent `user_id` fails closed; unknown ability stays denied; arrays and non-objects cannot be supplied.
3. Verify all six controller call sites use the compatible subject contract. Add route/controller integration evidence only after a safe fixture strategy exists; do not use a non-allowlisted database.
4. For Writ editing, verify owner sees the form and can update; another user and an unauthenticated user cannot mutate; missing public IDs return a safe unavailable result.
5. For comments/deletion (outside B), if the paths are retained, verify author-only edits/deletes and ensure denied requests produce no write.
6. Security reviewer inspects exact diff and confirms no code path converts a thrown policy error into permission.

The existing `AuthorizationRegression.php` is a pure in-memory starting point, not route-level proof. Future implementation should add the missing generic-row and controller-path evidence in the approved test layer.

## Proposed affected-file inventory

Minimum code proposal: `app/Policies/WritPolicy.php`, `app/Policies/CommentPolicy.php`, and `tests/AuthorizationRegression.php`. If route-flow tests require new fixtures or files, list them in the implementation approval before work begins. No PHP file was modified for WZP-02.1.
