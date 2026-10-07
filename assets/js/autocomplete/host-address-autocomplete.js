/**
 * Standalone Ninja Forms adapter. Owns only input bindings and host value sync.
 * No framework FormCore, geocoder, map, locator or paid service is initialized.
 * @since 1.0.0
 */
import { AddressAutocomplete } from './services/address-autocomplete.js';
import { buildAddressOptions, isEnabled } from './address-options.js';
import { loadGooglePlaces } from './google-places-loader.js';

const bindings = new Map();
const pending = new Map();
let radioRegistered = false;

/**
 * Check whether an enabled physical input participates in the visible form.
 *
 * @since 1.0.0
 * @param {HTMLInputElement} input Address input.
 * @returns {boolean} Whether selection validation applies to this input.
 */
export function isActiveInput(input) {
	if (!input.isConnected || input.disabled) return false;
	for (let el = input; el && el !== input.form; el = el.parentElement) {
		const style = getComputedStyle(el);
		if (el.hidden || style.display === 'none' || style.visibility === 'hidden') return false;
	}
	return true;
}

/**
 * Update Ninja's Backbone model as well as DOM events used by conditional logic.
 *
 * @since 1.0.0
 * @param {HTMLInputElement} input Address input with the committed value.
 * @returns {void}
 */
function syncInput(input) {
	const id = input.id.replace(/^nf-field-/, '');
	const model = globalThis.nfRadio?.channel('fields').request('get:field', id);
	model?.set('value', input.value);
	// A committed value is a change, not new typing: input would reopen predictions.
	input.dispatchEvent(new Event('change', { bubbles: true }));
}

/**
 * Reject new unselected edits while preserving defaults during API initialization.
 *
 * @since 1.0.0
 * @param {HTMLInputElement} input Address input.
 * @param {{field: Object, value: string}} state Initial field value and preferences.
 * @returns {boolean} Whether submission or blur may proceed.
 */
function validatePending(input, state) {
	if (
		!isActiveInput(input) ||
		!isEnabled(state.field.nfgeo_force_autocomplete_selection) ||
		!input.value.trim() ||
		input.value === state.value
	)
		return true;
	input.value = '';
	state.value = '';
	syncInput(input);
	globalThis.alert(
		state.field.nfgeo_force_autocomplete_selection_message ||
			'Please select an address from the suggested results.'
	);
	input.focus();
	return false;
}
/**
 * Validate only physical inputs belonging to the requested form or container.
 *
 * @since 1.0.0
 * @param {Element} root Native form instance being submitted.
 * @returns {boolean} Whether every active Address input permits submission.
 */
export function validateSelection(root) {
	for (const [input, state] of pending) {
		if (root?.contains(input) && !validatePending(input, state)) return false;
	}
	for (const [input, binding] of bindings) {
		if (root?.contains(input) && !binding.enforceSelection(true)) return false;
	}
	return true;
}

/**
 * Register Ninja's validation hook so programmatic AJAX submission is covered.
 *
 * @since 1.0.0
 * @returns {void}
 */
function registerHostValidation() {
	if (radioRegistered || !globalThis.nfRadio) return;
	radioRegistered = true;
	globalThis.nfRadio.channel('submit').on('validate:field', (model) => {
		if (model.get('type') !== 'nfgeo_address') return;
		const id = String(model.get('id'));
		const input = [...bindings.keys(), ...pending.keys()].find(
			(input) => input.id === `nf-field-${id}`
		);
		const binding = bindings.get(input);
		const valid = binding
			? binding.enforceSelection(true)
			: !pending.has(input) || validatePending(input, pending.get(input));
		if (!valid) {
			globalThis.nfRadio
				.channel('fields')
				.request(
					'add:error',
					id,
					'nfgeoac-selection',
					model.get('nfgeo_force_autocomplete_selection_message') ||
						'Please select an address from the suggested results.'
				);
		} else {
			globalThis.nfRadio.channel('fields').request('remove:error', id, 'nfgeoac-selection');
		}
	});
}

/**
 * Attach one service per physical input and preserve saved/default values.
 *
 * @since 1.0.0
 * @param {HTMLInputElement} input Address input being initialized.
 * @param {Element} wrapper Host field wrapper for native component population.
 * @param {Object} field Whitelisted, premium-compatible field preferences.
 * @param {Object} config Browser API and localization preferences.
 * @returns {Promise<void>} Resolves after binding or installing failure validation.
 */
async function bindInput(input, wrapper, field, config) {
	const signature = JSON.stringify({ field, config });
	if (bindings.get(input)?.signature === signature || pending.has(input)) return;
	bindings.get(input)?.destroy();
	const initial = { field, value: input.value };
	pending.set(input, initial);
	let loadingBlurTimer;
	const loadingBlur = () => {
		loadingBlurTimer = setTimeout(() => {
			if (pending.has(input)) validatePending(input, initial);
		}, 200);
	};
	input.addEventListener('blur', loadingBlur);
	try {
		await loadGooglePlaces(config);
		if (!input.isConnected) return;
		const service = new AddressAutocomplete(
			{
				inputElement: input,
				prefix: 'nfgeo',
				fetchFields: ['formattedAddress'],
				debounceDelay: 200,
			},
			buildAddressOptions(field, config)
		);
		let selectedValue = initial.value;
		let blurTimer;
		const selected = () => {
			clearTimeout(blurTimer);
			selectedValue = input.value;
			syncInput(input);
			globalThis.nfRadio
				?.channel('fields')
				.request('remove:error', input.id.replace(/^nf-field-/, ''), 'nfgeoac-selection');
		};
		const enforceSelection = (submitting = false) => {
			clearTimeout(blurTimer);
			if (
				!isActiveInput(input) ||
				!isEnabled(field.nfgeo_force_autocomplete_selection) ||
				!input.value.trim() ||
				input.value === selectedValue
			)
				return true;
			if (service.pendingSelections > 0) {
				if (!submitting) blurTimer = setTimeout(enforceSelection, 100);
				return false;
			}
			input.value = '';
			selectedValue = '';
			service.resetInteractionState();
			service.clearSuggestions();
			syncInput(input);
			globalThis.alert(
				field.nfgeo_force_autocomplete_selection_message ||
					'Please select an address from the suggested results.'
			);
			input.focus();
			return false;
		};
		const blurred = () => {
			clearTimeout(blurTimer);
			blurTimer = setTimeout(enforceSelection, 200);
		};
		const focused = () => clearTimeout(blurTimer);
		const choosing = (event) => {
			if (event.target.closest('li')) {
				event.preventDefault();
				clearTimeout(blurTimer);
			}
		};
		input.addEventListener('place_changed', selected);
		input.addEventListener('blur', blurred);
		input.addEventListener('focus', focused);
		service.container.addEventListener('mousedown', choosing);
		bindings.set(input, {
			signature,
			enforceSelection,
			destroy() {
				clearTimeout(blurTimer);
				input.removeEventListener('place_changed', selected);
				input.removeEventListener('blur', blurred);
				input.removeEventListener('focus', focused);
				service.container.removeEventListener('mousedown', choosing);
				service.destroy();
				bindings.delete(input);
			},
		});
		if (input.value !== initial.value && document.activeElement === input) {
			input.dispatchEvent(new Event('input', { bubbles: true }));
		}
	} catch (error) {
		console.warn('[Address Autocomplete] Initialization failed:', error.message);
		// Keep require-selection honest when the API fails, while preserving saved values.
		let failureTimer;
		const enforceSelection = () => validatePending(input, initial);
		const failedBlur = () => {
			clearTimeout(failureTimer);
			failureTimer = setTimeout(enforceSelection, 200);
		};
		input.addEventListener('blur', failedBlur);
		bindings.set(input, {
			signature,
			enforceSelection,
			destroy() {
				clearTimeout(failureTimer);
				input.removeEventListener('blur', failedBlur);
				bindings.delete(input);
			},
		});
	} finally {
		clearTimeout(loadingBlurTimer);
		input.removeEventListener('blur', loadingBlur);
		pending.delete(input);
	}
}

/**
 * Resolve each mounted Ninja instance without global field ID lookups.
 *
 * @since 1.0.0
 * @returns {void}
 */
export function initializeAutocomplete() {
	registerHostValidation();
	for (const [input, binding] of bindings) if (!input.isConnected) binding.destroy();
	for (const data of Object.values(globalThis.nfgeoAutocompleteForms || {})) {
		const roots = document.querySelectorAll(
			`[id="nf-form-${data.formId}-cont"], [id^="nf-form-${data.formId}_"][id$="-cont"]`
		);
		for (const root of roots) {
			for (const field of data.fields) {
				if (!isEnabled(field.nfgeo_address_autocomplete)) continue;
				// Anchored suffixes cover duplicate embeds and native repeater rows.
				const candidates = root.querySelectorAll('input[id^="nf-field-"]');
				for (const input of candidates) {
					const id = input.id.replace(/^nf-field-/, '');
					if (
						id !== String(field.id) &&
						!id.startsWith(`${field.id}_`) &&
						!id.startsWith(`${field.id}.`)
					)
						continue;
					if (!input.disabled)
						bindInput(
							input,
							input.closest('.nf-field-container') || input.parentElement,
							field,
							data.config
						);
				}
			}
		}
	}
}

/**
 * Initialize after native rendering and observe physical input replacements.
 *
 * @since 1.0.0
 * @returns {void}
 */
function boot() {
	initializeAutocomplete();
	globalThis.jQuery?.(document).on('nfFormReady.nfgeoac', initializeAutocomplete);
	document.addEventListener(
		'submit',
		(event) => {
			if (!validateSelection(event.target)) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}
		},
		true
	);
	document.addEventListener(
		'click',
		(event) => {
			const button = event.target.closest(
				'.submit-container input, .submit-container button, .nf-next'
			);
			const root = button?.closest('.nf-form-cont');
			if (root && !validateSelection(root)) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}
		},
		true
	);
	let refresh;
	new MutationObserver((records) => {
		const changed =
			records.some((record) =>
				Array.from(record.addedNodes).some(
					(node) => node.nodeType === 1 && (node.matches('input') || node.querySelector('input'))
				)
			) || Array.from(bindings.keys()).some((input) => !input.isConnected);
		if (changed) {
			clearTimeout(refresh);
			refresh = setTimeout(initializeAutocomplete, 50);
		}
	}).observe(document.body, { childList: true, subtree: true });
}

if (document.readyState === 'loading')
	document.addEventListener('DOMContentLoaded', boot, { once: true });
else boot();
