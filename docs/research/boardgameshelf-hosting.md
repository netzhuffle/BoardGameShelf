# BoardGameShelf deployment planning constraints

Investigated 2026-10-29 for [Establish current production and hosting constraints](https://github.com/netzhuffle/BoardGameShelf/issues/3). This public summary deliberately omits infrastructure topology, server paths, account identities, permissions, configuration excerpts and operational evidence. Detailed evidence is retained privately by the maintainer.

## Findings relevant to the specification

- Read-only comparison confirmed that the selected deployed application files and collection cache match the September 20 snapshot. This establishes the comparison baseline, not a guarantee that all surrounding configuration is unchanged.
- Preserve the existing public entry point, https://jannis.rocks/boardgameshelf.php. The repository's entry-point layout is not a drop-in replacement: URL compatibility and deployment layout need an explicit decision before release.
- The inspected runtime supports continuing with PHP and XML processing. Confirm the effective web runtime and required extensions during release preparation; command-line checks alone are not acceptance evidence.
- The deployed source loads cached collection data and fetches it if loading fails; it does not establish an age-based refresh policy. Current cached display does not prove that authenticated fresh retrieval works. Any independent refresh mechanism remains unverified.
- Cache creation, replacement and migration require explicit design and verification. Do not assume that writable cache storage guarantees that existing cache entries can be overwritten.
- The legacy image helper remains in the deployed source, but its use is commented out in the main and expansion code. Decide its future from agreed image-delivery requirements, not mere file presence.
- No application-specific release and rollback contract was established by the investigation. Select scoped release access, affected artifacts, verification and restoration steps before production replacement.

## Decisions enabled

1. Choose how the local structure will preserve the public URL and supporting endpoint behavior.
2. Define collection freshness, refresh failures and last-known-good data behavior in light of current BoardGameGeek requirements.
3. Decide artifact layout, explicit filesystem path handling, dependency packaging and cache migration without assuming source-root deployment.
4. Define focused acceptance checks and rollback criteria. Keep access details and operational runbooks private.

## Evidence and limitations

Evidence consists of read-only application-file comparisons, source inspection and bounded runtime/deployment checks retained privately. The application was not executed, no refresh was triggered, and no server changes were made. Effective runtime configuration, independent refresh jobs and a release-specific deployment procedure need later confirmation. These are explicit planning prerequisites, not claims that production replacement is already qualified.
