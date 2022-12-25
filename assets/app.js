/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import {DataTable} from "simple-datatables";

require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';
import 'simple-datatables/src/style.css'

// start the Stimulus application
import './bootstrap';

document.addEventListener('DOMContentLoaded', (event) => {
    let tables = document.querySelectorAll('.init-datatable');

    for (const table of tables) {
        new DataTable(table);

        table.classList.remove('init-datatable');
    }
})