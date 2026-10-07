/** Exercise the actual standalone editor bundle, including bundled Choices. */
import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { createRequire } from 'node:module';
const require = createRequire(import.meta.url);
const { JSDOM } = require('jsdom');
const bundle = readFileSync(
	new URL('../../build/js/admin/nfgeo-form-editor.min.js', import.meta.url),
	'utf8'
);
const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

test('country/type selectors enhance once, retain selections and notify Ninja changes', async () => {
	const dom = new JSDOM(
		'<div class="nf-setting"><select id="nfgeo_address_autocomplete_country" class="setting nfgeoac-multiple" multiple><option value="US" selected>United States</option><option value="IL">Israel</option></select></div><div class="nf-setting"><select id="nfgeo_address_autocomplete_types" class="setting nfgeoac-multiple" multiple><option value="street_address" selected>Street addresses</option></select></div>',
		{ runScripts: 'outside-only', pretendToBeVisual: true }
	);
	try {
		const { window } = dom;
		window.matchMedia = () => ({ matches: false, addListener() {}, removeListener() {} });
		window.nfgeoAutocompleteEditor = { allCountries: 'All countries', allTypes: 'All place types' };
		window.jQuery = require('jquery')(window);
		let changes = 0;
		window.jQuery('.setting').on('change', () => changes++);
		window.eval(bundle);
		await sleep(100);
		assert.equal(window.document.querySelectorAll('.choices').length, 2);
		const country = window.document.querySelector('select');
		assert.deepEqual(
			Array.from(country.selectedOptions, (o) => o.value),
			['US']
		);
		country.choices.setChoiceByValue('IL');
		country.dispatchEvent(new window.Event('change', { bubbles: true }));
		assert.ok(changes > 0);
		assert.deepEqual(
			Array.from(country.selectedOptions, (o) => o.value),
			['US', 'IL']
		);
		window.NFGEOAC_FormEditor.refresh();
		await sleep(80);
		assert.equal(window.document.querySelectorAll('.choices').length, 2);
		assert.equal(window.document.querySelector('.nfgeoac-promotions'), null);
	} finally {
		dom.window.close();
	}
});
