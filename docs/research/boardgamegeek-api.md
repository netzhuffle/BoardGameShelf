# BoardGameGeek API constraints

Research date: 2026-10-29. Resolves [Establish current BoardGameGeek API constraints](https://github.com/netzhuffle/BoardGameShelf/issues/2). Evidence informs decisions; it does not select product policy.

## Evidence quality

Official pages below were available through fresh search-index retrieval; direct page opens returned HTTP 403. This is documentation research, not an authenticated integration test. No application registration, credentials, collection payload, or image fetch was exercised. Wiki guidance is not a service guarantee. The usage guide identifies its version as 2025-07-02; image guidance is explicitly historical.

## Access and operation

Normal access requires application registration and approval at `/applications`, followed by a token in `Authorization: Bearer <token>` over HTTPS to `boardgamegeek.com` without `www`. Approval may take a week or longer; approval and continued access are discretionary. Logged-in collection-download exceptions do not establish anonymous public-app access. Noncommercial licenses are generally free, not guaranteed permanently free. Donations, advertising, or payment can change license classification. [Using the XML API](https://boardgamegeek.com/using_the_xml_api)

BGG recommends server requests, cached results, and minimal traffic; excessive client-originated traffic can threaten access. Tokens currently need no refresh, but this can change. End-user applications should obtain their own token rather than ask ordinary users to register. Exact quotas remain unsettled and may vary by license; usage is visible under the application. Private website APIs have no general license. No technical support or stability commitment is offered. [Using the XML API](https://boardgamegeek.com/using_the_xml_api)

## Collection and game data

`/xmlapi2/collection?username=NAME&own=1&stats=1` selects owned entries with expanded statistics. A BGG collection also includes merely rated, played, wished-for, and formerly owned games; collection membership alone does not mean ownership. [XML API collection definition](https://boardgamegeek.com/wiki/page/XMLAPI)

The API2 wiki documents:

- Default collection results include expansions but can incorrectly label their subtype as `boardgame`; its workaround combines a request excluding expansions with a separate expansion request.
- `collid` selects one collection entry; `id` selects game items. These are distinct identities, not interchangeable identifiers.
- Personal-rating filters and BGG-rating filters are separate. `stats=1` expands rating/ranking information.
- `/xmlapi2/thing?id=ID,...&stats=1` supplies item statistics; batches have a maximum of 20 IDs. `versions=1` requests version information.
- `modifiedsince` covers added/status-changed entries and explicitly omits deletions.
- Collection HTTP 202 means queued; retry with delay until processing finishes. It does not mean an empty collection.
- Throttling can produce 500/503. A five-second gap is described as apparently sufficient, not a guaranteed quota.

[BGG XML API2](https://boardgamegeek.com/wiki/page/bgg_xml_api2)

## Attribution and images

The terms require source credit by name and a legible **Powered by BGG** logo linking to BGG for public-facing use. They prohibit modification of retrieved data and AI/LLM training, permit revocation, and incorporate the general terms. Their noncommercial grant does not replace the separate commercial licensing process. Do not infer blanket permission to rewrite descriptions or republish all assets. [XML API Terms of Use](https://boardgamegeek.com/xmlapi/termsofuse)

In 2018, BGG developer Daniel Karp stated that image reuse depends on uploader licensing, asked applications not to hotlink, and recommended downloading/resizing/serving permitted images themselves. This is historical first-party guidance, not confirmation of a current image API or blanket image license. URL size-suffix manipulation should not be treated as a supported contract. [Image links discussion](https://boardgamegeek.com/thread/1948876/image-links-from-xml-api-no-longer-support-size-su)

## Unresolved contracts and decisions enabled

No reviewed primary source establishes `lastmodified` as purchase/acquisition time, documents all triggers or timezone semantics, guarantees collection-entry uniqueness per game, or fully specifies unrated-value encoding. Do not assign those meanings without further evidence. A live authenticated fixture should confirm XML field paths, duplicate copies/versions, missing ratings, and error payloads before implementation.

No verified SLA, precise retry ceiling, freshness guarantee, current image-delivery contract, or image-by-image license clearance was established. The registration status and token availability for this project remain unknown.

The evidence enables separate decisions about registration readiness; which ownership/expansion/copy/rating concepts to display; full reconciliation versus incremental refresh; cached-data freshness and retry behavior; and permitted image delivery with attribution. Those policies remain Jannis's decisions. In particular, deletion-blind incremental filtering cannot alone establish a complete current inventory, and collection modification time must not silently become “date acquired.”
