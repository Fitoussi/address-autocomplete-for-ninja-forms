# Standalone acceptance — Ninja Forms

Date: 2026-10-07. Build: 1.0.0. Status: working first draft for owner review, not published.

## Boundaries

Separate local repository and isolated bootstrap/namespace. Shared premium-compatible saved field type and supported option keys.
No framework, account/licensing SDK, remote catalog/feed, geocoder/locator or paid field runtime.
Four discovery-only editor links: Map, Directions, Distance & Duration, Address Validation.
Only the dedicated single-line Address field; no native address/component synchronization.

## Environment

WordPress 7.1.1 on Test Site 2. Ninja Forms 3.15.3; Ninja Geolocation 3.6.0 used for switching.
PHP source contracts also executed using PHP 7.4.30. This is not a full installed-site PHP 7.4 test.
Minimum declared host 3.13.2 and WordPress 6.5 were not installed and are not certified by these results.

## Passed

- Fresh ZIP activation and isolated free bootstrap.
- Local Overview, Compare Packages, Help and More Plugins pages, settings navigation and scoped assets.
- Exactly four nonfunctional premium discovery links, without registering fake field types.
- Native field editor saves country, language, force-selection and proximity controls; saved values persist.
- Global browser key, region and language preferences integrate with the host settings page.
- Live region changes reflected in the frontend browser configuration.
- Real Google Places API (New) suggestions; selected address saved by the native submission flow.
- Typed unselected value clears on blur with an alert; a subsequent selected address successfully submits.
- Ninja submission list displays the saved single-line address.
- Premium takeover when free is already active and when premium is active first.
- Deactivating premium lets free resume on the next request.
- Stored field/settings SHA-256 fingerprints identical before, during and after the activation-order tests.
- Data-version markers unchanged by switching.
- Final installed ZIP: Plugin Check reports “Checks complete. No errors found.”
- Repository PHP coding standards: zero errors and zero warnings.
- PHP source contracts pass, including scalar input handling, persistence keys and runtime payload whitelisting.
- 14 actual built-bundle DOM tests pass.

Automated frontend coverage: saved defaults, selection synchronization, blur rejection, valid retry,
hidden conditional inputs, duplicate embeds, AJAX replacement, API-loading window, API failure,
pending place-detail fetch, country/language/type/proximity/bounds translation and narrowly allowed legacy fallback.
PHP collection coverage also includes native repeater definitions and Ninja numeric 1.0 flags.

## Fix found during installed acceptance

A synthetic input event after selection reopened predictions over the host Submit button.
Committed values now notify the native change lifecycle without pretending to be new typing.
Both adapters have an explicit regression proving that committing a suggestion does not request or reopen predictions.

## Remaining release gates

- Owner review of labels, discovery links, package marketing and the two dashboards.
- Actual screenshots for these products; no Gravity screenshots relabeled, no placeholder images shipped.
- Full installed-site minimum host/WordPress/PHP matrix.
- Real host add-on conditional, multi-page and repeater flows. DOM fixtures cover relevant adapter behavior but do not certify every add-on.
- Ninja live entry-edit save and native export/import round trip remain unverified; the native submission list was verified.
- Language choices retain the existing reference list; not every listed Google language/result type was exercised.
- GitHub publication and WordPress.org submission need separate approval.

## Package

Filename: address-autocomplete-for-ninja-forms.1.0.0.zip
SHA-256: eb008701275b62e6db96cafe7c40cc411c997bb43beaf6602dd07dcd737ca62b

Readable JS/SCSS and frontend sourcemaps ship with the compiled assets.
Development tools, tests, docs, editor files, node_modules and vendor are excluded.
The local package builder refuses to overwrite an existing ZIP.

## Test data and restoration

Private evidence and the reversible database backup live outside the repository in
artifacts/standalone-host-qa-20261006.6G91os. They contain local configuration and must not be published.
Only disposable QA forms/entries were changed. Original activation and global-settings options are restored after testing.
QA forms are Ninja 7 and Formidable 12; pages 347/348 are retained as drafts, with entries kept as review fixtures.
The two new installed plugins are inactive after restoration. Premium and Gravity source repositories were not modified.
