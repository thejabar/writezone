# WZ-GOV Audit and Evidence Architecture

**Status:** Proposed physical design; signing/archive infrastructure not selected
**Domain owner:** WZ-GOV
**Shared types:** [Corporate physical-model notation](README.md#physical-model-notation)

## Goals and limits

GOV audit evidence is append-only at the application contract and includes a tamper-evident per-stream hash chain. A database hash chain alone is not immutable and does not protect against a privileged database operator who can rewrite the chain. Independent signing and archive are required before claiming tamper-evidence beyond the database trust boundary.

Business mutations that require audit must write the audit event in the same database transaction as the business change. Bootstrap catalogue events may have a null principal but require actor_kind and an opaque actor_ref, such as an approved change-ticket reference. No credentials belong in actor references or payloads.

## Audit table specification

All audit tables are GOV-owned. All FKs use RESTRICT/RESTRICT. Common ID, M, CODE, A and interval conventions are in the README.

| Table | Columns beyond default ID and metadata (an explicit PK replaces default ID) | Keys, FKs, checks and indexes | Lifecycle, audit, retention |
|---|---|---|---|
| gov_audit_streams | stream_key BINARY(16); domain_code CODE; stream_state CODE; M | UQ(stream_key); IX(domain_code,stream_state). | proposed → active → suspended/retired; GOV-R. |
| gov_audit_stream_heads | stream_id BIGINT UNSIGNED; last_sequence_no BIGINT UNSIGNED; last_event_hash BINARY(32); last_sealed_sequence_no BIGINT UNSIGNED DEFAULT 0; next_fencing_token BIGINT UNSIGNED DEFAULT 0; updated_at DATETIME(6); updated_actor_kind VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin; updated_actor_ref VARBINARY(128) | PK(stream_id); FK stream_id → gov_audit_streams(id); CHECK last_sealed_sequence_no ≤ last_sequence_no. | Mutable head only; each change is transactionally paired with an event/attempt; GOV-R. |
| gov_audit_events | event_ref BINARY(16); stream_id BIGINT UNSIGNED; sequence_no BIGINT UNSIGNED; event_kind CODE; actor_principal_id BIGINT UNSIGNED NULL; actor_kind VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin; actor_ref VARBINARY(128); correlation_ref BINARY(16) NULL; occurred_at DATETIME(6); payload JSON; previous_hash BINARY(32); event_hash BINARY(32); A | UQ(event_ref); UQ(stream_id,sequence_no); **UQ(id,stream_id,sequence_no,event_hash)**; FK stream; nullable actor FK → iam_principals(id); IX(actor,occurred_at); IX(correlation_ref). | Append-only; payload minimized and redacted; GOV-R. |
| gov_audit_batch_reservations | stream_id BIGINT UNSIGNED; reservation_ref BINARY(16); fencing_token BIGINT UNSIGNED; first_sequence_no BIGINT UNSIGNED; last_sequence_no BIGINT UNSIGNED; lease_owner_code CODE; lease_expires_at DATETIME(6); reservation_state CODE; created_at DATETIME(6); updated_at DATETIME(6) | PK(stream_id); UQ(stream_id,reservation_ref); UQ(stream_id,fencing_token); FK stream_id → gov_audit_stream_heads(stream_id); CHECK first≤last. | One mutable current reservation per stream; updates also write immutable attempts; state reserved/building/externals_ready/sealed/expired/failed; GOV-R. |
| gov_audit_batch_attempts | attempt_ref BINARY(16); stream_id BIGINT UNSIGNED; reservation_ref BINARY(16); fencing_token BIGINT UNSIGNED; attempt_no INT UNSIGNED; operation_code CODE; outcome_code CODE; first_sequence_no BIGINT UNSIGNED; last_sequence_no BIGINT UNSIGNED; manifest_hash BINARY(32); external_request_ref VARBINARY(128) NULL; failure_code CODE NULL; started_at DATETIME(6); completed_at DATETIME(6) NULL; A | UQ(attempt_ref); UQ(stream_id,reservation_ref,fencing_token,attempt_no,operation_code); **UQ(id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash,operation_code,outcome_code)**; FK stream; IX(reservation_ref,fencing_token). | Append-only; outcome includes succeeded, failed, stale, expired or authorized; raw response/error bodies are not retained; GOV-R. |
| gov_audit_seal_authorizations | authorization_ref BINARY(16); attempt_id BIGINT UNSIGNED; stream_id BIGINT UNSIGNED; reservation_ref BINARY(16); fencing_token BIGINT UNSIGNED; first_sequence_no BIGINT UNSIGNED; last_sequence_no BIGINT UNSIGNED; manifest_hash BINARY(32); operation_code CODE; outcome_code CODE; authorized_at DATETIME(6); authorized_by_code CODE | UQ(authorization_ref); **UQ(id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash)**; composite FK (attempt_id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash,operation_code,outcome_code) → gov_audit_batch_attempts(id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash,operation_code,outcome_code); FK stream. CHECK operation_code='seal_authorize' and outcome_code='authorized'; CHECK interval. | Immutable certificate created only after the current lease, external signature/archive and full range are verified under the stream-head/reservation lock; GOV-R. |
| gov_audit_batches | batch_ref BINARY(16); stream_id BIGINT UNSIGNED; seal_authorization_id BIGINT UNSIGNED; reservation_ref BINARY(16); fencing_token BIGINT UNSIGNED; first_sequence_no BIGINT UNSIGNED; last_sequence_no BIGINT UNSIGNED; event_count BIGINT UNSIGNED; manifest_hash BINARY(32); sealed_at DATETIME(6); A | UQ(batch_ref); UQ(stream_id,first_sequence_no); UQ(stream_id,last_sequence_no); **UQ(id,stream_id)**; **UQ(id,manifest_hash)**; composite FK (seal_authorization_id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash) → gov_audit_seal_authorizations(id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash); FK stream; CHECK nonempty range and event_count=last_sequence_no−first_sequence_no+1. | Insert only as sealed; immutable; count and sequence continuity are checked transactionally; GOV-R. |
| gov_audit_batch_items | batch_id BIGINT UNSIGNED; stream_id BIGINT UNSIGNED; event_id BIGINT UNSIGNED; sequence_no BIGINT UNSIGNED; event_hash BINARY(32); recorded_at DATETIME(6) | PK(batch_id,sequence_no); UQ(event_id); FK (batch_id,stream_id) → gov_audit_batches(id,stream_id); FK (event_id,stream_id,sequence_no,event_hash) → gov_audit_events(id,stream_id,sequence_no,event_hash); IX(stream,sequence). | Immutable membership; one event can appear in only one batch; exact event-to-stream-to-sequence-to-hash enforced by composite FK; GOV-R. |
| gov_archive_bundles | batch_id BIGINT UNSIGNED; storage_provider_code CODE; object_ref VARBINARY(512); object_hash BINARY(32); archived_at DATETIME(6); A | UQ(batch_id,storage_provider_code); **UQ(id,batch_id)**; UQ(storage_provider_code,object_ref); FK batch_id → gov_audit_batches(id); IX(object_hash). | Successful archive object only; content addressed; failed attempts remain in attempts table; GOV-R. |
| gov_evidence_manifests | batch_id BIGINT UNSIGNED; canonicalization_version SMALLINT UNSIGNED; manifest_hash BINARY(32); signature_algorithm CODE; signing_key_id CODE; signature VARBINARY(1024); signed_at DATETIME(6); A | UQ(batch_id); UQ(manifest_hash); **UQ(id,batch_id)**; composite FK (batch_id,manifest_hash) → gov_audit_batches(id,manifest_hash); IX(signing_key_id,signed_at). | One immutable signed manifest per batch; its hash must equal the hash authorized for the sealed batch; signature is public material, private key is external; GOV-R. |
| gov_evidence_verifications | batch_id BIGINT UNSIGNED; manifest_id BIGINT UNSIGNED; archive_bundle_id BIGINT UNSIGNED; verifier_version CODE; result_code CODE; observed_manifest_hash BINARY(32) NULL; observed_object_hash BINARY(32) NULL; failure_code CODE NULL; checked_at DATETIME(6); correlation_ref BINARY(16) NULL; A | FK (manifest_id,batch_id) → gov_evidence_manifests(id,batch_id); FK (archive_bundle_id,batch_id) → gov_archive_bundles(id,batch_id); IX(batch_id,checked_at). | Append-only verification attempt; app compares observed hash to manifest/bundle hash and records pass/fail; GOV-R. |

### Composite-key correction (Finding 9)

The exact FK required by verification is (archive_bundle_id, batch_id) → gov_archive_bundles(id, batch_id). Therefore gov_archive_bundles declares UNIQUE(id, batch_id) in that order. The table also has primary key id; the composite unique is deliberate because an FK must target a matching primary or unique key. gov_evidence_manifests declares UNIQUE(id,batch_id) for the analogous verification FK. The manifest itself uses (batch_id,manifest_hash) → gov_audit_batches(id,manifest_hash), so its digest must match the digest certified by the seal authorization. Verification records store the expected batch and target IDs; observed hashes are evidence values, not FK columns.

The matching verification FKs are:

- (manifest_id, batch_id) → gov_evidence_manifests(id, batch_id).
- (archive_bundle_id, batch_id) → gov_archive_bundles(id, batch_id).

### Reservation-to-batch proof (Finding 13)

The sealed batch does not FK to mutable reservation state. It carries reservation_ref, fencing_token, sequence range, manifest_hash and seal_authorization_id. The authorization row has a composite FK to one attempt row that contains the same stream, reservation, token, range, manifest hash, operation and outcome. The authorization CHECK allows only operation seal_authorize and outcome authorized. The batch's composite FK then requires that exact authorization tuple, and the manifest-to-batch composite FK requires the stored manifest digest to equal the certified digest.

Only the sealer transaction can create an authorization row:

1. Lock stream head, then the current reservation row.
2. Verify reservation_ref, fencing_token, owner and unexpired lease match the current row; verify first_sequence_no = last_sealed_sequence_no + 1 and reserved last_sequence_no ≤ the head's current last_sequence_no. The reservation stores the captured head sequence as its reserved last_sequence_no; later appends do not change the reservation range.
3. Verify every event in the reserved interval is present and contiguous; recompute count, first/last, chain and manifest hash.
4. Verify signature and archived object hashes.
5. Insert the immutable successful seal attempt, seal authorization, batch, items, manifest, archive bundle and initial verification in one transaction; advance the head's sealed sequence and mark reservation sealed.
6. Commit.

A stale or superseded token cannot pass step 2; no seal-authorization row is written. The FK chain then prevents a batch from referring to a failed, stale, expired or different-range attempt. The database cannot prove that application code performed the lock/lease comparison; runtime write access must be restricted to the approved service, and concurrency tests remain mandatory.

## Ordering and hash calculation

Within one stream, append locks the head, assigns last_sequence_no+1, writes previous_hash and event_hash, updates the head and commits in one transaction. The event hash is SHA-256 over a versioned canonical encoding of stream key, sequence, previous hash and canonical event payload. Different streams have independent order; timestamps do not establish a global total order.

An audit batch is a contiguous range. Unique keys prevent duplicate stream sequences and duplicate event membership. The sealer transaction additionally verifies that membership has exactly event_count rows and sequences equal every integer from first_sequence_no through last_sequence_no. No ordinary unique index enforces interval non-overlap; the one-row-per-stream reservation and locked last_sealed pointer do.

## Concurrent sealing and recovery

- The reservation table has one row per stream. Acquisition locks head then reservation; a live lease blocks another worker.
- Every replacement increments next_fencing_token under the head lock. The old token is stale even if its worker resumes.
- Reservation and external operation attempts are append-only records. Reservation current state is a projection.
- Signing/archive requests use an idempotency identity derived from reservation_ref, fencing_token and manifest_hash. Provider selection must require safe idempotent/recoverable requests.
- If an external object exists but DB sealing failed, retain it. Reconciler verifies the exact range and digest; it retries the seal only if the range remains next. Conflicts and unreferenced objects are retained for operator review, never deleted automatically.
- If signer/archive is unavailable, the unsealed local chain remains. Mark audit evidence degraded. Workflows whose approved policy requires a durable sealed checkpoint fail closed. The policy/operation set requires security approval.

No database hash chain is claimed to be immutable. Independent signing, separately controlled archive, DB privilege separation, periodic verification, backups and restore exercises are required for stronger tamper evidence.

## ER diagram

~~~mermaid
erDiagram
    GOV_AUDIT_STREAMS ||--|| GOV_AUDIT_STREAM_HEADS : head
    GOV_AUDIT_STREAM_HEADS ||--o| GOV_AUDIT_BATCH_RESERVATIONS : current_reservation
    GOV_AUDIT_STREAMS ||--o{ GOV_AUDIT_EVENTS : ordered_events
    GOV_AUDIT_BATCH_ATTEMPTS ||--o| GOV_AUDIT_SEAL_AUTHORIZATIONS : successful_attempt
    GOV_AUDIT_SEAL_AUTHORIZATIONS ||--o| GOV_AUDIT_BATCHES : exact_seal_proof
    GOV_AUDIT_STREAMS ||--o{ GOV_AUDIT_BATCHES : sealed_ranges
    GOV_AUDIT_BATCHES ||--|{ GOV_AUDIT_BATCH_ITEMS : contains
    GOV_AUDIT_EVENTS ||--o| GOV_AUDIT_BATCH_ITEMS : member
    GOV_AUDIT_BATCHES ||--|| GOV_EVIDENCE_MANIFESTS : manifest
    GOV_AUDIT_BATCHES ||--o{ GOV_ARCHIVE_BUNDLES : archived_copy
    GOV_EVIDENCE_MANIFESTS ||--o{ GOV_EVIDENCE_VERIFICATIONS : checked
    GOV_ARCHIVE_BUNDLES ||--o{ GOV_EVIDENCE_VERIFICATIONS : checked_copy
~~~
