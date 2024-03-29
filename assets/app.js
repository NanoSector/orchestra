/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

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
import 'simple-datatables/src/css/style.css'

// start the Stimulus application
import './bootstrap';

document.addEventListener('DOMContentLoaded', (event) => {
    let tables = document.querySelectorAll<HTMLTableElement>('.init-datatable');

    for (const table of tables) {
        new DataTable(table, {
            footer: false,
            paging: false,
            searchable: false
        });

        table.classList.remove('init-datatable');
    }
})
