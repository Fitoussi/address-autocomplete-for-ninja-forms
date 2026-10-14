=== WPGeo Address Autocomplete for Ninja Forms ===
Contributors: ninjew
Tags: address autocomplete, ninja forms, google places, address, geolocation
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Google Places address suggestions for Ninja Forms, with country and language controls, location bias and required selection.

== Description ==

Add Google-powered address suggestions to Ninja Forms. Use the included single-line Address field to help visitors enter addresses with less typing.

= Features =

* Dedicated single-line Address field in the native Ninja Forms builder.
* Country restrictions: up to five countries per field.
* Result types: regions, cities, street addresses or premises.
* Per-field suggestion language.
* Global language and region preferences.
* Location bias by point and radius or rectangular bounds.
* Optional restriction to rectangular bounds.
* Required suggestion selection with a custom alert message.
* Saves addresses through Ninja Forms' normal submission lifecycle.

= An Address field built for Ninja Forms =

Add the Address field from the Geolocation field group and configure autocomplete in its field settings. The plugin collects a complete address in one input; it does not fill Ninja Forms' separate street, city, state, ZIP or country fields.

= Customize suggestions =

Choose countries, result types and language for each field. Prioritize suggestions near a point or within map bounds, or restrict results to those bounds. The global region preference influences results; country restrictions limit which countries can appear.

= Require a suggestion selection =

Optionally require visitors to choose an address suggestion. Manually typed, unselected text is cleared, and your custom alert prompts them to select a suggestion. This is not postal address validation or a deliverability guarantee.

= Google API requirements =

A Google Maps API key is required for address autocomplete to work. Follow [this tutorial](https://ninjageolocation.com/docs/create-google-maps-api-keys-ninja-forms/) to create your Google Maps API key.

[Google Maps Platform terms](https://cloud.google.com/maps-platform/terms/) | [Google privacy policy](https://policies.google.com/privacy)

= Need more than autocomplete? =

[Ninja Geolocation](https://ninjageolocation.com/) offers paid packages for interactive maps, geocoding, dynamic location fields, current-location detection, directions, distance calculations, address validation, nearby locations and drawing tools. Feature availability depends on the package.

Ninja Geolocation is a separate plugin, not functionality unlocked inside this free plugin by entering a license.

[Explore packages and pricing](https://ninjageolocation.com/pricing/) | [Try the live demos](https://demo.ninjageolocation.com/) | [Read the documentation](https://ninjageolocation.com/docs/)

== Installation ==

1. Install and activate Ninja Forms 3.13.2 or newer.
2. Install and activate WPGeo Address Autocomplete for Ninja Forms. Deactivate Ninja Geolocation first if you are switching to this free plugin.
3. Open Ninja Forms > Settings and find the Address Autocomplete section.
4. Enter your Google Maps API key with Maps JavaScript API and Places API (New) enabled.
5. Set optional global region and language preferences.
6. Open your form in the Ninja Forms builder and add the Address field from the Geolocation group. Configure its options and save.
7. Preview and test selecting suggestions and submitting the form.

The overview is available under Ninja Forms > Address Autocomplete. If Ninja Forms is unavailable, it appears under Settings > Address Autocomplete instead.

== Frequently Asked Questions ==

= Are all the autocomplete controls included free? =

Yes. All autocomplete controls described above are included free. Ninja Forms and your own Google Maps API key are required.

= Does this validate postal addresses or provide maps and directions? =

No. Autocomplete helps visitors choose a Google suggestion, but does not verify postal deliverability or provide connected maps, directions or current-location detection. Ninja Geolocation offers those features in separate paid packages.

= Can it fill Ninja Forms' separate address components? =

No. Use the included single-line Address field. This plugin does not fill Ninja Forms' separate street, city, state, ZIP or country fields.

= What happens if someone types an address without selecting a suggestion? =

If required suggestion selection is enabled, unselected text is cleared when the visitor leaves the field, and your custom message prompts them to select a suggestion. Without that option, manually typed addresses are allowed. Ninja Forms' Required setting still controls whether the field can be left empty.

= What is the difference between region and country restriction? =

The global region preference influences Google's results. A field's country restriction limits suggestions to the selected countries. A field's language setting can override the global language.

= Can I use it on AJAX or multi-page forms? =

The plugin supports AJAX rendering and dynamically displayed Address fields. Multi-page and conditional forms require the corresponding Ninja Forms features or add-ons. Test your form, theme and integrations before going live.

= Can I use this alongside Ninja Geolocation? =

Deactivate this free plugin when switching to Ninja Geolocation. If both are active, the free plugin pauses and displays an administrator notice, regardless of plugin load order. Your saved Address fields and settings are not deleted. Fields that require Ninja Geolocation still need that plugin to work.

= Is a plugin account or license required? =

No WPGeo account or plugin license is required. You do need your own Google Cloud account and API key, as described above.

= Can another integration load Google? =

Yes. Developers can use the `nfgeo_disable_google_maps_api` filter to prevent this plugin from loading Google. Another integration must then load a compatible Google Maps JavaScript API with Places support. This is a developer filter, not a settings switch.

= Where can developers find the source code? =

Readable JavaScript and SCSS sources are included in the plugin. The full development project, build configuration and dependency lockfile are available on [GitHub](https://github.com/Fitoussi/address-autocomplete-for-ninja-forms). To rebuild assets, download the repository, install Node.js/npm, then run `npm ci` and `npm run build` (esbuild and Sass). These tools are not required to use the plugin.

Includes [Choices.js 11.1.0](https://github.com/Choices-js/Choices/tree/v11.1.0), MIT licensed; readable distribution: assets/js/editor/vendor/choices.js; license: assets/licenses/choices.txt.

== Screenshots ==

1. Live Google Places suggestions in the dedicated Ninja Forms Address field. Google attribution remains visible.
2. Address Autocomplete field options in the Ninja Forms builder, including required suggestion selection, result types, countries, language and location bias.
3. Global Google browser API key, region and language settings, with a link to the API setup guide.
4. The plugin overview introduces the included standalone Address Autocomplete integration and links to its configuration.

== Changelog ==

= 1.0.0 =
* Initial release.
