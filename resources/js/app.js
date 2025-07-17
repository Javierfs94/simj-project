import $ from 'jquery';
window.$ = $;
window.jQuery = $;

// Bootstrap 4 y jQuery compatible
import 'popper.js';
import 'bootstrap';

import 'datatables.net-bs4';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Customs scripts
import './users';
