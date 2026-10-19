# Logical Address Autocomplete for Ninja Forms

## October 19 version 1.0.1 release

The owner authorized the Logical public title and all pending dashboard changes,
including the contained module, settings-first primary action, modern navigation,
notices below navigation, bottom-aligned discovery actions and current Starter/Pro
comparison. Preserve the approved wpgeo-address-autocomplete-for-ninja-forms slug,
folder, main filename, text domain, translation names, namespace and saved nfgeo data.
NFGEOAC_PLUGIN_NAME remains Address Autocomplete for Ninja Forms; the overview h1
remains Address Autocomplete with For Ninja Forms above it. No SDK is introduced.
The existing published orange banner already omits WPGeo and can be reused exactly.
The owner reviewed the installed candidate and authorized GitHub and WordPress.org
publication. The exact tested 40-file ZIP has SHA-256
872e6e6377e4a7a087606389e112cc71eec4f45b4a4a4b3051c0d759df9897df.
Locked build, six PHP fixture entrypoints (including PHP 7.4.30), PHP 7.4 lint and
15 JavaScript tests passed. Test Site 2 browser checks covered all four dashboards,
native settings, editor discovery dialogs by mouse/Enter, and actual Google
suggestions, required-selection clearing and a valid retry. Saved forms, entries,
settings and activation states remained unchanged. Native cards do not respond to
Space; editor JavaScript is unchanged from 1.0.0. Plugin Check has zero errors and
one warning for wp in the retained existing slug, also present in public 1.0.0.
A complete new minimum-host, guest-access, entry-write or conditional/multipage
installed matrix was not certified. Retain Test Site 2 as reviewed.
The existing banner exports are recorded in docs/wordpress-org-assets/ and do not
ship inside the plugin ZIP. No icon, banner or screenshot change is needed in SVN.

## October 17 local dashboard containment refactor

The owner requested the same copyable dashboard module as Gravity Geolocation,
with no behavior changes. Presentation now lives in src/Admin/Dashboard/:
Loader.php, Features.php, views/dashboard.php and assets/scss + assets/css.
Host callers, fixtures, build/watch paths and package checks use the new locations.
Native settings, setup guards, menus/URLs, feature content and saved data remain
host-owned and unchanged. No SDK, dependency, source version or published ZIP
changes. This is unreleased source work, not authorization to publish or install.
See src/Admin/Dashboard/README.md and the workspace audit at
artifacts/dashboard-modules-20261017/RESULTS.txt for validation boundaries.


## Standalone bootstrap convention

Use this convention for these three plugins and future standalone autocomplete plugins:

- Give free its own constant prefix and PHP namespace: GFGEOAC / GravityGeolocationAutocomplete,
  NFGEOAC / NinjaGeolocationAutocomplete, or FRMGEOAC / FormidableGeolocationAutocomplete.
  Never define aliases using premium's constants or classes.
- Register the guard on plugins_loaded, after all active plugin files have loaded.
  Check the corresponding premium version constant before registering any dashboard,
  field, settings or runtime integration. Premium wins regardless of file load order.
- Gravity runs the guard at priority 5, then registers its Add-On on gform_loaded at
  priority 8; Gravity Forms initializes Add-Ons during plugins_loaded at priority 10.
  Ninja/Formidable check at priority 20. Ninja constructs translated fields on init
  at priority 1, before its host's priority-5 field list. Match host lifecycle, not
  arbitrary identical hook numbers.
- On conflict, free registers only capability-gated site/network admin notices.
  Do not auto-deactivate plugins, redirect, delete data or change saved preferences.
  Premium being active but unable to run does not permit free to claim its field types.
- Preserve saved field types, field option names, global setting keys, native settings
  slugs, capabilities and existing integration hooks. These are data/API contracts;
  PHP bootstrap identities are not shared storage. Third-party PHP integrations must
  use the intended plugin's namespace/constants explicitly.
- Use short arrays in first-party production PHP. Tool/test formatting is independent.
- Test free alone and both premium/free load orders in separate processes; test
  notices with and without administrator capability. Run installed acceptance too.


A lean standalone plugin forked from our Gravity autocomplete baseline.
Only the dedicated Address field is supported. Native Ninja Address/component fields are deliberately outside this plugin.

## Structure

- Main plugin: isolated NFGEOAC constants and NinjaGeolocationAutocomplete autoloader.
- Public plugin/readme title is Logical Address Autocomplete for Ninja Forms. The
  existing slug and text domain retain wpgeo. Internal NFGEOAC_PLUGIN_NAME is
  Address Autocomplete for Ninja Forms, without either branding prefix.
- src/Loader.php: dependency check and premium-wins guard after all bootstraps load.
- src/Core/Bootstrap.php: native settings, one real Address field and editor/runtime hooks.
- src/Features/Form/Fields/Address: native text field lifecycle and supported options.
- src/Features/Form/Admin: autocomplete controls and four discovery-only editor links.
- src/Features/Form/Runtime: immutable, whitelisted frontend payloads.
- src/Admin: native global settings plus locally rendered Overview, Compare Packages,
  Help and More Plugins pages.
- assets/js/autocomplete: Google loader, extracted service and host-specific adapter.
- assets/js/editor and assets/scss: narrowly scoped native-builder presentation.
- tests: source contracts and actual built-bundle DOM fixtures.

No framework, account SDK, licensing SDK, geocoder, locator, premium field services,
migrations or uninstall data deletion is bundled. Premium fields already saved in
a form are the host/premium plugin's responsibility; free does not reinterpret them.

## Activation setup notice

First site activation records a setup notice in the plugin's own option. It appears
across site admin pages for administrators until permanently dismissed or a browser
API key is configured. It never redirects on activation. Reactivation does not reset
dismissed or completed guidance, and existing active installs are not enrolled by
an update alone. Network activation, missing/outdated hosts and premium conflicts
do not display setup guidance. The explicit Dismiss action is a capability-checked,
nonce-protected POST that returns to the same admin page. Shared host settings and
premium options are not changed. Plugin-row Settings and Overview links remain.

## Development

Use npm ci, npm run build and npm test. Runtime needs no Composer dependencies.
First-party JavaScript/SCSS formatting follows .prettierrc.json; vendor sources
are excluded by .prettierignore. PHP follows tools/phpcs.xml.dist, matching the
Gravity standalone conventions. Test-only host stubs are not shipped in ZIPs.
Development quality tooling lives in tools/. Readable JS/SCSS and sourcemaps ship
with the generated assets. Build scripts, package.json and package-lock.json stay
in the public source repository and are excluded from installable ZIPs.
npm run package creates a versioned, non-overwriting
ZIP under dist/. Public metadata lives in readme.txt and change_log.txt.

Global preferences preserve existing premium keys. Formidable merges only three
browser settings into its existing option; Ninja uses native per-key persistence.
Field country/language/type/bias and selection settings keep premium-compatible names.

## Acceptance and release

Inspect fresh-ZIP activation, dashboard links, editor persistence, real Google
suggestions, blur selection enforcement and valid retry, AJAX/conditional rendering,
entry editing, existing forms and premium switching in both activation orders.
Record tested host versions and any untested flows honestly. Use Plugin Check.
Minimum-host compatibility and screenshots remain release gates unless verified.

Four real Ninja screenshots are bundled in assets/screenshots/screenshot-1.png
through screenshot-4.png, with matching readme captions: live suggestions, native
editor options, global preferences and the plugin overview. Screenshot provenance
is recorded in docs/screenshots.txt. Do not relabel captures from other hosts.
GitHub publication and WordPress.org submission require separate approval.
