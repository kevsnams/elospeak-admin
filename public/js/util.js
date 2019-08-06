/*!
 * Serialize all form data into a query string
 * (c) 2018 Chris Ferdinandi, MIT License, https://gomakethings.com
 * @param  {Node}   form The form to serialize
 * @return {String}      The serialized form data
 */
var serialize = function (form) {

	// Setup our serialized data
	var serialized = [];

	// Loop through each field in the form
	for (var i = 0; i < form.elements.length; i++) {

		var field = form.elements[i];

		// Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
		if (!field.name || field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') continue;

		// If a multi-select, get all selections
		if (field.type === 'select-multiple') {
			for (var n = 0; n < field.options.length; n++) {
				if (!field.options[n].selected) continue;
				serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[n].value));
			}
		}

		// Convert field data to a query string
		else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
			serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
		}
	}

	return serialized.join('&');

};

function formElementsDisable(formSelector, disabled) {
	disabled = typeof disabled == 'undefined' ? true : disabled;
	var form = formSelector.toString() == "[object HTMLFormElement]" ? formSelector : document.querySelector(formSelector);

	if (!form) return;
	var allElements = form.elements;
	for (var i = 0, l = allElements.length; i < l; ++i) {
		allElements[i].disabled = disabled;
	}
}

// --- START Spoof Methods ---
// Not working on <IE7. But fuck that, who still uses that garbage?
/**
 * Adds a hidden _method field to spoof HTTP verb
 * @param method string The HTTP verb. PUT, PATCH, DELETE
 */
Element.prototype.setSpoofMethod = function (method) {
	method = method.toUpperCase();

	var h = this.querySelector('[name="_method"]');

	if (!h) {
		var e = document.createElement('input');
		e.setAttribute('type', 'hidden');
		e.setAttribute('name', '_method');
		e.value = method;

		this.appendChild(e);

		h = e;
	}

	h.setAttribute('type', 'hidden');
	h.value = method;
};

/**
 * Removes _hidden method field for HTTB verb spoofing
 */
Element.prototype.removeSpoofMethod = function () {
	var h = this.querySelector('[name="_method"]');

	if (h) h.parentNode.removeChild(h);
};

Element.prototype.getSpoofMethod = function () {
	var h = this.querySelector('[name="_method"]');

	if (h) return h.value;

	var m = this.getAttribute('method');

	if (m) return m;

	return 'GET';
};
// --- END Spoof Methods --

Element.prototype.disableAllFields = function () {
	formElementsDisable(this);
};

Element.prototype.enableAllFields = function () {
	formElementsDisable(this, false);
};