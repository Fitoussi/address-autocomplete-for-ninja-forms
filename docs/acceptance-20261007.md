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
- 15 actual built-bundle DOM tests pass (including native Choices persistence).
- Native submission modal saved an edited QA address; native database read-back confirmed the change.
- Retested saved US/IL restrictions, Cities results, Hebrew, proximity coordinates/radius and the custom selection message. Real Google returned Hebrew city suggestions.

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
- Native export/import round trip remains unverified.
- Ninja's paid conditional/multipage/repeater extensions are not installed on the acceptance site. Live add-on flows are not certified; the adapter fixtures cover hidden/replaced inputs and nested definitions.
- Language choices retain the existing reference list; not every listed Google language/result type was exercised.
- GitHub publication and WordPress.org submission need separate approval.

## Package

Filename: address-autocomplete-for-ninja-forms.1.0.0.zip
SHA-256: 6b36b0e17892c0881e00af718729b9f98939d70fbb6d2cb812c70e4a23440686

Night cleanup: first-party PHP/JS/SCSS formatting and lifecycle documentation completed;
unused editor localization and a dead dashboard variable removed. The empty tests/helpers
directory was removed. Native field groups, unavailable-card mechanisms and saved keys are unchanged.
Final ZIP contains 38 files, with no framework/account SDK, tools/tests/docs or installed dependencies.
No commit, push or publication was performed during this cleanup.

The second Formidable repeater rejection test stalled the in-app browser at its expected alert.
The last installed-ZIP browser smoke could not be completed after that stall; final ZIP validation
used Plugin Check, source contracts, bundle tests and native runtime checks instead.

Readable JS/SCSS and frontend sourcemaps ship with the compiled assets.
Development tools, tests, docs, editor files, node_modules and vendor are excluded.
The local package builder refuses to overwrite an existing ZIP.

## Test data and restoration

Private evidence and the reversible database backup live outside the repository in
artifacts/standalone-host-qa-20261006.6G91os and artifacts/autocomplete-night-audit-20261007.9mM2Gn.
They contain local configuration and must not be published.
Only disposable QA forms/entries were changed. Original activation and global-settings options are restored after testing.
QA forms are Ninja 7 and Formidable 12; pages 347/348 are retained as drafts, with entries kept as review fixtures.
The night audit added Ninja 8, Formidable 13 plus flow parent/child 14/15.
Its pages 353/354/357 are also retained as drafts, and QA entries remain available for review.
The temporary save diagnostic is removed from the test site after testing.
The two new installed plugins were inactive after the night restoration. Premium and Gravity source repositories were not modified.

## Daytime live-flow follow-up — completed

This follow-up supersedes the older conditional/multipart and final browser-smoke gaps above.
gmwdev: WordPress 7.2-alpha-63323, Ninja Forms 3.15.5, Conditional Logic 3.1,
and Multi-Part 3.0.23. Both extensions were already active.

- Native multipart QA form 65: hidden required Address permits Next; later-part
  suggestions initialize; both selected addresses survive Next/Previous.
- Conditional Address hide/show preserves its selection. Final AJAX submission
  saves both page values (entry 17174), verified through the native model.
- Ordinary conditional QA form 66: hidden required Address permits submission
  (17175). Shown Address returns predictions; an unselected edit clears on blur
  with an alert; valid retry saves the selected address (17176).
- Rechecked both premium activation orders on Test Site 2: premium ownership and
  no standalone callbacks, then standalone resumes after premium deactivation.
  Field/settings/data-version fingerprints remain unchanged across four states.
- Final installed ZIP browser smoke on Test Site 2: real Places suggestions and
  native submission succeed, entry 363 saves the selected address exactly once.
- Automated suites rerun: 15 JavaScript tests and PHP contracts pass. Installed
  native regression also rechecks the earlier admin entry edit and saved options.

No new runtime correction was needed. A Formidable native confirmation temporarily
stalled browser controls; the owner dismissed it and remaining checks resumed.
Ninja's repeatable-fieldset extension, minimum-version installed matrix and
export/import remain outside this live follow-up; do not infer them from these passes.

Per owner instruction, no daytime backup/restoration: both standalone plugins
remain active on both sites, both premiums inactive, relevant Ninja extensions
active on gmwdev. QA pages/forms/entries are retained locally; owner forms/settings
were not changed. No commit, push, publication or submission.
Detailed evidence: artifacts/standalone-flow-acceptance-20261007.Ty0zcg in the
development workspace. It is private QA material, not release content.
