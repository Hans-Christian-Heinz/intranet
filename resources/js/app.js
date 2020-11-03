/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('star-rating-preview', require('./components/StarRatingPreview.vue').default);
Vue.component('star-rating', require('./components/StarRating.vue').default);
Vue.component('review-category-form', require('./components/ReviewCategoryForm.vue').default);
Vue.component('review-form', require('./components/ReviewForm.vue').default);
Vue.component('resume', require('./components/ResumeComponent.vue').default);
Vue.component('application-new', require('./components/ApplicationComponentNew.vue').default);
Vue.component('app-tpl-new', require('./components/ApplicationTemplatesNew.vue').default);
Vue.component('application-no-tpl',  require('./components/ApplicationComponentNoTpl.vue').default);
Vue.component('documentation-table', require('./components/DocumentationTableComponent.vue').default);
Vue.component('section-text', require('./components/SectionTextComponent.vue').default);
Vue.component('table-component', require('./components/SectionText/TableComponent.vue').default);
Vue.component('list-component', require('./components/SectionText/ListComponent.vue').default);
Vue.component('link-component', require('./components/SectionText/LinkComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
