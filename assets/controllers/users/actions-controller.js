/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['deleteButton', 'deleteForm'];
    static values = { deleteUrl: String }

    confirmDelete() {
        this.deleteButtonTarget.classList.add('d-none');
        this.deleteFormTarget.classList.remove('d-none');
    }
}