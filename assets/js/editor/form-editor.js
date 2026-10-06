/**
 * Minimal Ninja builder controls and discovery-only premium buttons.
 * Buttons are ordinary HTML links, never native field types or draggable fields.
 * @since 1.0.0
 */
function refresh() {
	const config = globalThis.nfgeoAutocompleteEditor;
	if (!config) return;
	const group = document.querySelector('.nfgeo-geolocation, [data-section="nfgeo_geolocation"]');
	const palette = group || document.querySelector('#nf-field-type-section-nfgeo_geolocation');
	if (palette && !palette.querySelector('.nfgeoac-promotions')) {
		const promotions = document.createElement('div');
		promotions.className = 'nfgeoac-promotions';
		for (const label of Object.values(config.features)) {
			const link = document.createElement('a');
			link.className = 'nfgeoac-premium-field';
			link.href = config.overviewUrl;
			link.textContent = `${label} 🔒`;
			link.title = config.premium;
			link.draggable = false;
			promotions.appendChild(link);
		}
		palette.appendChild(promotions);
	}
	const autocomplete = document.querySelector('#nfgeo_address_autocomplete');
	const bias = document.querySelector('#nfgeo_autocomplete_restriction_usage');
	const force = document.querySelector('#nfgeo_force_autocomplete_selection');
	for (const input of document.querySelectorAll('[id^="nfgeo_"]')) {
		const container = input.closest('.nf-setting') || input.parentElement;
		if (!container || input === autocomplete) continue;
		let visible = !autocomplete || autocomplete.checked;
		if (input.id.startsWith('nfgeo_autocomplete_proximity_')) visible &&= bias?.value === 'proximity';
		if (input.id.startsWith('nfgeo_autocomplete_bounds_') || input.id === 'nfgeo_address_autocomplete_strict_bounds') visible &&= bias?.value === 'area_bounds';
		if (input.id === 'nfgeo_force_autocomplete_selection_message') visible &&= force?.checked;
		container.style.display = visible ? '' : 'none';
	}
}

// Persist our native text settings as users type, including before Done closes the drawer.
document.addEventListener('input', event => {
	if (event.target.matches('input.setting[id^="nfgeo_"][type="text"]')) {
		globalThis.jQuery?.(event.target).trigger('change');
	}
});

document.addEventListener('change', event => {
	const select = event.target.closest('.nfgeoac-multiple');
	if (select && select.selectedOptions.length > 5) {
		for (const option of Array.from(select.selectedOptions).slice(5)) option.selected = false;
		globalThis.jQuery?.(select).trigger('change');
	}
	refresh();
});

let timer;
new MutationObserver(() => { clearTimeout(timer); timer = setTimeout(refresh, 50); }).observe(document.documentElement, {childList: true, subtree: true});
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', refresh, {once: true});
else refresh();
