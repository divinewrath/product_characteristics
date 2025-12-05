define([
    'ko',
    'underscore',
    'Magento_Ui/js/dynamic-rows/dynamic-rows',
], function (ko, _, dynamicRows) {
    return dynamicRows.extend({

        defaults: {
            isUseDefault: false,
            disabled: false,
            dndEnabled: ko.observable(true),

            listens: {
                'isUseDefault': 'toggleUseDefault',
                'elems': 'checkSpinner setDefaultStateCustom'
            },
        },
        initialize: function () {
            return this._super()
                .setInitialValue();
        },

        initObservable: function () {
            return this._super()
                .observe('disabled isDifferedFromDefault')
                .observe('isUseDefault serviceDisabled');
        },

        setInitialValue: function () {
            this.isUseDefault(this.disabled());
            this.dndEnabled(!this.disabled());

            return this;
        },

        toggleUseDefault: function (state) {
            this.disabled(state);
            this.dndEnabled(!state);

            if (this.source && this.hasService()) {
                this.source.set('data.use_default.pictos', Number(state));
            }
        },

        setDefaultStateCustom: function(items) {
            let drDisabled = this.disabled();
            _.each(items, function (elem) {
                elem.setDisabled(drDisabled);
            });
        },

        hasService: function () {
            return this.service && this.service.template;
        },
    });
});
