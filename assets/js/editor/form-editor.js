/**
 * Address-only Ninja builder controls using locally bundled Choices.
 * Premium discovery stays in Ninja's native available-field cards and modal.
 * @since 1.0.0
 */
import Choices from './vendor/choices.js';

/**
 * Enhance each newly rendered select once, preserving the native setting.
 *
 * @since 1.0.0
 * @returns {void}
 */
export function enhanceSelects() {
	const config = globalThis.nfgeoAutocompleteEditor || {};
	for (const select of document.querySelectorAll('select.nfgeoac-multiple')) {
		if (select.choices) continue;
		select.choices = new Choices(select, {
			removeItemButton: true,
			maxItemCount: 5,
			shouldSort: false,
			allowHTML: false,
			itemSelectText: '',
			searchEnabled: true,
			placeholderValue: select.id.endsWith('_country') ? config.allCountries : config.allTypes,
			searchPlaceholderValue: config.search || 'Search',
		});
		// Keep dropdown scrolling inside Ninja's drawer, like the premium editor.
		const dropdown = select.choices.dropdown.element;
		dropdown.addEventListener('wheel', (event) => event.stopPropagation(), {
			capture: true,
			passive: true,
		});
		// Ninja listens through jQuery's change lifecycle to persist array settings.
		select.addEventListener('change', () => globalThis.jQuery?.(select).trigger('change'));
	}
}

/**
 * Update dependent options and the presentation-only settings notice.
 *
 * @since 1.0.0
 * @returns {void}
 */
export function refresh() {
	enhanceSelects();
	const notice = document.querySelector('.nfgeoac-settings-notice');
	if (notice) {
		const cell = notice.closest('td');
		const row = cell?.parentElement;
		if (cell && row) {
			cell.colSpan = 2;
			row.classList.add('nfgeoac-settings-notice-row');
			const heading = row.querySelector('th');
			if (heading) heading.hidden = true;
		}
	}
	const autocomplete = document.querySelector('#nfgeo_address_autocomplete');
	const bias = document.querySelector('#nfgeo_autocomplete_restriction_usage');
	const force = document.querySelector('#nfgeo_force_autocomplete_selection');
	for (const input of document.querySelectorAll('[id^="nfgeo_"]')) {
		const container = input.closest('.nf-setting') || input.parentElement;
		if (!container || input === autocomplete) continue;
		let visible = !autocomplete || autocomplete.checked;
		if (input.id.startsWith('nfgeo_autocomplete_proximity_'))
			visible &&= bias?.value === 'proximity';
		if (
			input.id.startsWith('nfgeo_autocomplete_bounds_') ||
			input.id === 'nfgeo_address_autocomplete_strict_bounds'
		)
			visible &&= bias?.value === 'area_bounds';
		if (input.id === 'nfgeo_force_autocomplete_selection_message') visible &&= force?.checked;
		// Choices creates ID-less controls; visibility belongs to the native setting.
		container.style.display = visible ? '' : 'none';
	}
}

// Native text controls must persist even when Done closes before blur.
document.addEventListener('input', (event) => {
	if (event.target.matches('input.setting[id^="nfgeo_"][type="text"]')) {
		globalThis.jQuery?.(event.target).trigger('change');
	}
});
document.addEventListener('change', refresh);
let timer;
new MutationObserver(() => {
	clearTimeout(timer);
	timer = setTimeout(refresh, 50);
}).observe(document.documentElement, { childList: true, subtree: true });
if (document.readyState === 'loading')
	document.addEventListener('DOMContentLoaded', refresh, { once: true });
else refresh();
