import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// Set Authorization header if token exists
const token = localStorage.getItem('api_token');
console.log('API Token:', token);
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
