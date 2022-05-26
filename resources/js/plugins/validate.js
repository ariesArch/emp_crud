import { ValidationProvider, extend, ValidationObserver, validate } from 'vee-validate'
import { required, digits, email, min, max, regex, confirmed } from 'vee-validate/dist/rules'
import Vue from 'vue';

// Add a rule.
extend('secret', {
    validate: value => value === 'example',
    message: 'This is not the magic word'
})
extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})'
})
extend('required', {
    ...required,
    message: '{_field_} is required'
})
extend('min', {
    ...min,
    message: '{_field_} may not be less than {length} characters'
})
extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters'
})
extend('regex', {
    ...regex,
    message: '{_field_} {_value_} does not match {regex}'
})
extend('email', {
    ...email,
    message: 'Email must be valid email'
})
const phoneRule = {
    validate(value, args) {
        // Custom regex for a phone number
        // (supplied in the SO post: https://stackoverflow.com/q/50033651/2600825)
        // const MOBILEREG = /^(([0-9])+\d{8})$/
        const MOBILEREG = /^(([0-9])+\d{6})$/
        // const MOBILEREG = /^((\d(\s +)?)+\d{6})$/
        // Check for either of these to return true
        return MOBILEREG.test(value)
    }
}
extend('phone', {
    ...phoneRule,
    message: '{_field_} must be valid phone number'
})
extend('confirmed', {
    ...confirmed,
    message: '{_field_} must be match with {target} '
})
// Register it globally
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)

