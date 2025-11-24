import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * We'll load the CSRF token from the meta tag below so that we can
 * automatically include it with all of our outgoing HTTP requests.
 */

console.log('Attempting to set CSRF token for Axios...');
const csrfToken = document.querySelector('meta[name="csrf-token"]');

if (csrfToken) {
    const token = csrfToken.getAttribute('content');
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    console.log('CSRF token set for Axios:', token);
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
