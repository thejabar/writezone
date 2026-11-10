# WZP-02.1 PHP and Composer Environment Plan

**Status:** Read-only readiness assessment and proposal
**No installation, PATH change, package operation, or project test was performed.**

## Verified local observations

- `composer.json` requires `php: ^8.2` and `vlucas/phpdotenv: ^5.6`; PSR-4 maps `App\\` to `app/` and `Core\\` to `core/`.
- `composer.lock` and `vendor/autoload.php` are present. Their presence does not verify the installed PHP/Composer versions or lockfile validity.
- Windows PowerShell command discovery returned only `wsl.exe` among `php`, `composer`, `wsl`, `docker`, and `podman`. No Windows PHP, Composer, Docker, or Podman executable was found through this check.
- `wsl.exe --list --verbose` returned `Wsl/EnumerateDistros/Service/E_ACCESSDENIED`. The existing Ubuntu distribution and its PHP/Composer tools could not be inspected from this process.
- No local `Dockerfile`, Compose file, Dev Container, PHPUnit configuration, or inspected GitHub/other CI workflow definition was found. The project uses standalone PHP regression scripts. No external GitHub settings were checked.
- The repository is readable at `C:\Users\DELL\Projects\writezone` in Windows. Access through `/mnt/c/Users/DELL/Projects/writezone` was not verified in this task because WSL enumeration failed.

No environment variables or `.env` contents were inspected. No PHP syntax, Composer, or application test command was run.

## Recommended primary route

Use the already provisioned Ubuntu 24.04 WSL distribution, if an appointed environment owner can access it without installing or reconfiguring software. First verify, read-only:

1. Distribution identity and WSL version.
2. `php -v` is within Composer's `^8.2` range; required project extensions and `pdo_mysql` are present.
3. `composer --version`, Composer compatibility with the existing lockfile, and repository/vendor readability.
4. File permissions and PHP CLI configuration when accessing the checkout through `/mnt/c`.
5. Required test tools are available without modifying global alternatives or system configuration.

This is the preferred route because the project already has an approved WSL environment history and the repository path is intended to be accessible there. **Current readiness remains BLOCKED:** WSL returned `E_ACCESSDENIED`; successful prior provisioning reports do not establish current access or tool versions.

## Fallback route if WSL remains inaccessible

Request a separate approval for a portable, isolated Windows PHP 8.2+ runtime and a version-pinned Composer executable in a dedicated user-controlled tools directory outside the repository. Use explicit executable paths instead of changing global `PATH`; set process-local `COMPOSER_HOME` and `COMPOSER_CACHE_DIR` to private tool/test locations; verify the Composer artifact hash and `composer.lock`; do not edit `vendor/` directly. Keep the repository on C: and do not write tool artifacts into the working tree.

Docker/Podman is not a current fallback because neither executable nor a project container definition was found. A hosted CI route is not available from the inspected repository configuration and would require a separate external-service decision. No packages or tools should be installed under this planning authorization.

## Reproducibility and validation sequence

After environment access is approved and verified:

1. Record executable paths and exact PHP/Composer versions; check relevant extensions.
2. Run Composer validation against the existing manifest/lockfile without dependency installation.
3. Run PHP syntax checks only on the approved code scope.
4. Run only Layer 1 tests initially; add Layer 2/3/4 only after the relevant isolation and approval gates pass.
5. Preserve `composer.lock`; do not update or install packages as a side effect of validation.
6. Record commands, environment, exit codes, and independent reproduction.

`composer install` and `scripts/release/check.sh` are not authorized by this plan. The release script currently reaches the unsafe aggregate test runner and database-connected migration status when its dirty-tree guard passes.

## Decisions and blocking actions

| Item | Status | Required action |
|---|---|---|
| PHP compatibility | NOT VERIFIED | Environment owner reports PHP 8.2+ version and extensions from existing WSL. |
| Composer compatibility | NOT VERIFIED | Record exact Composer version; validate existing lockfile. |
| WSL access | BLOCKED | Resolve access through an authorized normal-user route; no servicing or privilege change here. |
| Portable fallback | NOT AUTHORIZED | Project Owner separately approves the specific runtime source, path, version, and verification method. |
| Test execution | NOT AUTHORIZED in this task | First establish the applicable layer isolation; then approve exact test commands. |
