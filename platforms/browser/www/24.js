(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[24],{

/***/ "h11V":
/*!****************************************************************!*\
  !*** ./node_modules/@ionic/core/dist/esm/ion-radio_2.entry.js ***!
  \****************************************************************/
/*! exports provided: ion_radio, ion_radio_group */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ion_radio", function() { return Radio; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ion_radio_group", function() { return RadioGroup; });
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_createForOfIteratorHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/createForOfIteratorHelper */ "uFwe");
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/defineProperty */ "rePB");
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/@babel/runtime/regenerator */ "o0o1");
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_asyncToGenerator__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/asyncToGenerator */ "HaE+");
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_classCallCheck__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/classCallCheck */ "1OyB");
/* harmony import */ var C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_createClass__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/createClass */ "vuIU");
/* harmony import */ var _index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./index-e806d1f6.js */ "A36C");
/* harmony import */ var _ionic_global_9d5c8ee3_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./ionic-global-9d5c8ee3.js */ "Zgba");
/* harmony import */ var _helpers_90f46169_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./helpers-90f46169.js */ "QPqR");
/* harmony import */ var _theme_ff3fc52f_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./theme-ff3fc52f.js */ "74mu");










var radioIosCss = ":host{--inner-border-radius:50%;display:inline-block;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;z-index:2}:host(.radio-disabled){pointer-events:none}.radio-icon{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:center;justify-content:center;width:100%;height:100%;contain:layout size style}.radio-icon,.radio-inner{-webkit-box-sizing:border-box;box-sizing:border-box}label{left:0;top:0;margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;position:absolute;width:100%;height:100%;border:0;background:transparent;cursor:pointer;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none;display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;opacity:0}[dir=rtl] label,:host-context([dir=rtl]) label{left:unset;right:unset;right:0}label::-moz-focus-inner{border:0}input{position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;margin:0;padding:0;border:0;outline:0;clip:rect(0 0 0 0);opacity:0;overflow:hidden;-webkit-appearance:none;-moz-appearance:none}:host(:focus){outline:none}:host{--color-checked:var(--ion-color-primary, #3880ff);width:15px;height:24px}:host(.ion-color.radio-checked) .radio-inner{border-color:var(--ion-color-base)}.item-radio.item-ios ion-label{margin-left:0}@supports ((-webkit-margin-start: 0) or (margin-inline-start: 0)) or (-webkit-margin-start: 0){.item-radio.item-ios ion-label{margin-left:unset;-webkit-margin-start:0;margin-inline-start:0}}.radio-inner{width:33%;height:50%}:host(.radio-checked) .radio-inner{-webkit-transform:rotate(45deg);transform:rotate(45deg);border-width:2px;border-top-width:0;border-left-width:0;border-style:solid;border-color:var(--color-checked)}:host(.radio-disabled){opacity:0.3}:host(.ion-focused) .radio-icon::after{border-radius:var(--inner-border-radius);left:-9px;top:-8px;display:block;position:absolute;width:36px;height:36px;background:var(--ion-color-primary-tint, #4c8dff);content:\"\";opacity:0.2}:host-context([dir=rtl]):host(.ion-focused) .radio-icon::after,:host-context([dir=rtl]).ion-focused .radio-icon::after{left:unset;right:unset;right:-9px}:host(.in-item){margin-left:10px;margin-right:11px;margin-top:8px;margin-bottom:8px;display:block;position:static}@supports ((-webkit-margin-start: 0) or (margin-inline-start: 0)) or (-webkit-margin-start: 0){:host(.in-item){margin-left:unset;margin-right:unset;-webkit-margin-start:10px;margin-inline-start:10px;-webkit-margin-end:11px;margin-inline-end:11px}}:host(.in-item[slot=start]){margin-left:3px;margin-right:21px;margin-top:8px;margin-bottom:8px}@supports ((-webkit-margin-start: 0) or (margin-inline-start: 0)) or (-webkit-margin-start: 0){:host(.in-item[slot=start]){margin-left:unset;margin-right:unset;-webkit-margin-start:3px;margin-inline-start:3px;-webkit-margin-end:21px;margin-inline-end:21px}}";
var radioMdCss = ":host{--inner-border-radius:50%;display:inline-block;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;z-index:2}:host(.radio-disabled){pointer-events:none}.radio-icon{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:center;justify-content:center;width:100%;height:100%;contain:layout size style}.radio-icon,.radio-inner{-webkit-box-sizing:border-box;box-sizing:border-box}label{left:0;top:0;margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;position:absolute;width:100%;height:100%;border:0;background:transparent;cursor:pointer;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none;display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;opacity:0}[dir=rtl] label,:host-context([dir=rtl]) label{left:unset;right:unset;right:0}label::-moz-focus-inner{border:0}input{position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;margin:0;padding:0;border:0;outline:0;clip:rect(0 0 0 0);opacity:0;overflow:hidden;-webkit-appearance:none;-moz-appearance:none}:host(:focus){outline:none}:host{--color:var(--ion-color-step-400, #999999);--color-checked:var(--ion-color-primary, #3880ff);--border-width:2px;--border-style:solid;--border-radius:50%;width:20px;height:20px}:host(.ion-color) .radio-inner{background:var(--ion-color-base)}:host(.ion-color.radio-checked) .radio-icon{border-color:var(--ion-color-base)}.radio-icon{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;border-radius:var(--border-radius);border-width:var(--border-width);border-style:var(--border-style);border-color:var(--color)}.radio-inner{border-radius:var(--inner-border-radius);width:calc(50% + var(--border-width));height:calc(50% + var(--border-width));-webkit-transform:scale3d(0, 0, 0);transform:scale3d(0, 0, 0);-webkit-transition:-webkit-transform 280ms cubic-bezier(0.4, 0, 0.2, 1);transition:-webkit-transform 280ms cubic-bezier(0.4, 0, 0.2, 1);transition:transform 280ms cubic-bezier(0.4, 0, 0.2, 1);transition:transform 280ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 280ms cubic-bezier(0.4, 0, 0.2, 1);background:var(--color-checked)}:host(.radio-checked) .radio-icon{border-color:var(--color-checked)}:host(.radio-checked) .radio-inner{-webkit-transform:scale3d(1, 1, 1);transform:scale3d(1, 1, 1)}:host(.radio-disabled){opacity:0.3}:host(.ion-focused) .radio-icon::after{border-radius:var(--inner-border-radius);left:-12px;top:-12px;display:block;position:absolute;width:36px;height:36px;background:var(--ion-color-primary-tint, #4c8dff);content:\"\";opacity:0.2}:host-context([dir=rtl]):host(.ion-focused) .radio-icon::after,:host-context([dir=rtl]).ion-focused .radio-icon::after{left:unset;right:unset;right:-12px}:host(.in-item){margin-left:0;margin-right:0;margin-top:9px;margin-bottom:9px;display:block;position:static}:host(.in-item[slot=start]){margin-left:4px;margin-right:36px;margin-top:11px;margin-bottom:10px}@supports ((-webkit-margin-start: 0) or (margin-inline-start: 0)) or (-webkit-margin-start: 0){:host(.in-item[slot=start]){margin-left:unset;margin-right:unset;-webkit-margin-start:4px;margin-inline-start:4px;-webkit-margin-end:36px;margin-inline-end:36px}}";

var Radio = /*#__PURE__*/function () {
  function Radio(hostRef) {
    var _this = this;

    Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_classCallCheck__WEBPACK_IMPORTED_MODULE_4__["default"])(this, Radio);

    Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["r"])(this, hostRef);
    this.ionStyle = Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["e"])(this, "ionStyle", 7);
    this.ionFocus = Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["e"])(this, "ionFocus", 7);
    this.ionBlur = Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["e"])(this, "ionBlur", 7);
    this.inputId = "ion-rb-".concat(radioButtonIds++);
    this.radioGroup = null;
    /**
     * If `true`, the radio is selected.
     */

    this.checked = false;
    /**
     * The tabindex of the radio button.
     * @internal
     */

    this.buttonTabindex = -1;
    /**
     * The name of the control, which is submitted with the form data.
     */

    this.name = this.inputId;
    /**
     * If `true`, the user cannot interact with the radio.
     */

    this.disabled = false;

    this.updateState = function () {
      if (_this.radioGroup) {
        _this.checked = _this.radioGroup.value === _this.value;
      }
    };

    this.onFocus = function () {
      _this.ionFocus.emit();
    };

    this.onBlur = function () {
      _this.ionBlur.emit();
    };
  }
  /** @internal */


  Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_createClass__WEBPACK_IMPORTED_MODULE_5__["default"])(Radio, [{
    key: "setFocus",
    value: function () {
      var _setFocus = Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_asyncToGenerator__WEBPACK_IMPORTED_MODULE_3__["default"])( /*#__PURE__*/C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee(ev) {
        return C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                ev.stopPropagation();
                ev.preventDefault();
                this.el.focus();

              case 3:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this);
      }));

      function setFocus(_x) {
        return _setFocus.apply(this, arguments);
      }

      return setFocus;
    }()
    /** @internal */

  }, {
    key: "setButtonTabindex",
    value: function () {
      var _setButtonTabindex = Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_asyncToGenerator__WEBPACK_IMPORTED_MODULE_3__["default"])( /*#__PURE__*/C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee2(value) {
        return C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                this.buttonTabindex = value;

              case 1:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2, this);
      }));

      function setButtonTabindex(_x2) {
        return _setButtonTabindex.apply(this, arguments);
      }

      return setButtonTabindex;
    }()
  }, {
    key: "connectedCallback",
    value: function connectedCallback() {
      if (this.value === undefined) {
        this.value = this.inputId;
      }

      var radioGroup = this.radioGroup = this.el.closest('ion-radio-group');

      if (radioGroup) {
        this.updateState();
        Object(_helpers_90f46169_js__WEBPACK_IMPORTED_MODULE_8__["a"])(radioGroup, 'ionChange', this.updateState);
      }
    }
  }, {
    key: "disconnectedCallback",
    value: function disconnectedCallback() {
      var radioGroup = this.radioGroup;

      if (radioGroup) {
        Object(_helpers_90f46169_js__WEBPACK_IMPORTED_MODULE_8__["b"])(radioGroup, 'ionChange', this.updateState);
        this.radioGroup = null;
      }
    }
  }, {
    key: "componentWillLoad",
    value: function componentWillLoad() {
      this.emitStyle();
    }
  }, {
    key: "emitStyle",
    value: function emitStyle() {
      this.ionStyle.emit({
        'radio-checked': this.checked,
        'interactive-disabled': this.disabled
      });
    }
  }, {
    key: "render",
    value: function render() {
      var _createColorClasses;

      var inputId = this.inputId,
          disabled = this.disabled,
          checked = this.checked,
          color = this.color,
          el = this.el,
          buttonTabindex = this.buttonTabindex;
      var mode = Object(_ionic_global_9d5c8ee3_js__WEBPACK_IMPORTED_MODULE_7__["b"])(this);

      var _getAriaLabel = Object(_helpers_90f46169_js__WEBPACK_IMPORTED_MODULE_8__["c"])(el, inputId),
          label = _getAriaLabel.label,
          labelId = _getAriaLabel.labelId,
          labelText = _getAriaLabel.labelText;

      return Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["H"], {
        "aria-checked": "".concat(checked),
        "aria-hidden": disabled ? 'true' : null,
        "aria-labelledby": label ? labelId : null,
        role: "radio",
        tabindex: buttonTabindex,
        onFocus: this.onFocus,
        onBlur: this.onBlur,
        class: Object(_theme_ff3fc52f_js__WEBPACK_IMPORTED_MODULE_9__["c"])(color, (_createColorClasses = {}, Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_createColorClasses, mode, true), Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_createColorClasses, 'in-item', Object(_theme_ff3fc52f_js__WEBPACK_IMPORTED_MODULE_9__["h"])('ion-item', el)), Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_createColorClasses, 'interactive', true), Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_createColorClasses, 'radio-checked', checked), Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_createColorClasses, 'radio-disabled', disabled), _createColorClasses))
      }, Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])("div", {
        class: "radio-icon",
        part: "container"
      }, Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])("div", {
        class: "radio-inner",
        part: "mark"
      }), Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])("div", {
        class: "radio-ripple"
      })), Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])("label", {
        htmlFor: inputId
      }, labelText), Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])("input", {
        type: "radio",
        checked: checked,
        disabled: disabled,
        tabindex: "-1",
        id: inputId
      }));
    }
  }, {
    key: "el",
    get: function get() {
      return Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["i"])(this);
    }
  }], [{
    key: "watchers",
    get: function get() {
      return {
        "color": ["emitStyle"],
        "checked": ["emitStyle"],
        "disabled": ["emitStyle"]
      };
    }
  }]);

  return Radio;
}();

var radioButtonIds = 0;
Radio.style = {
  ios: radioIosCss,
  md: radioMdCss
};

var RadioGroup = /*#__PURE__*/function () {
  function RadioGroup(hostRef) {
    var _this2 = this;

    Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_classCallCheck__WEBPACK_IMPORTED_MODULE_4__["default"])(this, RadioGroup);

    Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["r"])(this, hostRef);
    this.ionChange = Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["e"])(this, "ionChange", 7);
    this.inputId = "ion-rg-".concat(radioGroupIds++);
    this.labelId = "".concat(this.inputId, "-lbl");
    /**
     * If `true`, the radios can be deselected.
     */

    this.allowEmptySelection = false;
    /**
     * The name of the control, which is submitted with the form data.
     */

    this.name = this.inputId;

    this.setRadioTabindex = function (value) {
      var radios = _this2.getRadios(); // Get the first radio that is not disabled and the checked one


      var first = radios.find(function (radio) {
        return !radio.disabled;
      });
      var checked = radios.find(function (radio) {
        return radio.value === value && !radio.disabled;
      });

      if (!first && !checked) {
        return;
      } // If an enabled checked radio exists, set it to be the focusable radio
      // otherwise we default to focus the first radio


      var focusable = checked || first;

      var _iterator = Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_createForOfIteratorHelper__WEBPACK_IMPORTED_MODULE_0__["default"])(radios),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var radio = _step.value;
          var tabindex = radio === focusable ? 0 : -1;
          radio.setButtonTabindex(tabindex);
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    };

    this.onClick = function (ev) {
      ev.preventDefault();
      var selectedRadio = ev.target && ev.target.closest('ion-radio');

      if (selectedRadio) {
        var currentValue = _this2.value;
        var newValue = selectedRadio.value;

        if (newValue !== currentValue) {
          _this2.value = newValue;
        } else if (_this2.allowEmptySelection) {
          _this2.value = undefined;
        }
      }
    };
  }

  Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_createClass__WEBPACK_IMPORTED_MODULE_5__["default"])(RadioGroup, [{
    key: "valueChanged",
    value: function valueChanged(value) {
      this.setRadioTabindex(value);
      this.ionChange.emit({
        value: value
      });
    }
  }, {
    key: "componentDidLoad",
    value: function componentDidLoad() {
      this.setRadioTabindex(this.value);
    }
  }, {
    key: "connectedCallback",
    value: function () {
      var _connectedCallback = Object(C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_helpers_esm_asyncToGenerator__WEBPACK_IMPORTED_MODULE_3__["default"])( /*#__PURE__*/C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.mark(function _callee3() {
        var header, label;
        return C_Users_romai_Documents_Programmation_perso_Application_projet_mon_projet_ionic_node_modules_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_2___default.a.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                // Get the list header if it exists and set the id
                // this is used to set aria-labelledby
                header = this.el.querySelector('ion-list-header') || this.el.querySelector('ion-item-divider');

                if (header) {
                  label = this.label = header.querySelector('ion-label');

                  if (label) {
                    this.labelId = label.id = this.name + '-lbl';
                  }
                }

              case 2:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3, this);
      }));

      function connectedCallback() {
        return _connectedCallback.apply(this, arguments);
      }

      return connectedCallback;
    }()
  }, {
    key: "getRadios",
    value: function getRadios() {
      return Array.from(this.el.querySelectorAll('ion-radio'));
    }
  }, {
    key: "onKeydown",
    value: function onKeydown(ev) {
      var inSelectPopover = !!this.el.closest('ion-select-popover');

      if (ev.target && !this.el.contains(ev.target)) {
        return;
      } // Get all radios inside of the radio group and then
      // filter out disabled radios since we need to skip those


      var radios = Array.from(this.el.querySelectorAll('ion-radio')).filter(function (radio) {
        return !radio.disabled;
      }); // Only move the radio if the current focus is in the radio group

      if (ev.target && radios.includes(ev.target)) {
        var index = radios.findIndex(function (radio) {
          return radio === ev.target;
        });
        var current = radios[index];
        var next; // If hitting arrow down or arrow right, move to the next radio
        // If we're on the last radio, move to the first radio

        if (['ArrowDown', 'ArrowRight'].includes(ev.code)) {
          next = index === radios.length - 1 ? radios[0] : radios[index + 1];
        } // If hitting arrow up or arrow left, move to the previous radio
        // If we're on the first radio, move to the last radio


        if (['ArrowUp', 'ArrowLeft'].includes(ev.code)) {
          next = index === 0 ? radios[radios.length - 1] : radios[index - 1];
        }

        if (next && radios.includes(next)) {
          next.setFocus(ev);

          if (!inSelectPopover) {
            this.value = next.value;
          }
        } // Update the radio group value when a user presses the
        // space bar on top of a selected radio (only applies
        // to radios in a select popover)


        if (['Space'].includes(ev.code)) {
          this.value = current.value;
        }
      }
    }
  }, {
    key: "render",
    value: function render() {
      var label = this.label,
          labelId = this.labelId;
      var mode = Object(_ionic_global_9d5c8ee3_js__WEBPACK_IMPORTED_MODULE_7__["b"])(this);
      return Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["h"])(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["H"], {
        role: "radiogroup",
        "aria-labelledby": label ? labelId : null,
        onClick: this.onClick,
        class: mode
      });
    }
  }, {
    key: "el",
    get: function get() {
      return Object(_index_e806d1f6_js__WEBPACK_IMPORTED_MODULE_6__["i"])(this);
    }
  }], [{
    key: "watchers",
    get: function get() {
      return {
        "value": ["valueChanged"]
      };
    }
  }]);

  return RadioGroup;
}();

var radioGroupIds = 0;


/***/ })

}]);
//# sourceMappingURL=24.js.map