import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['deleteButton', 'deleteForm'];

    confirmDelete() {
        this.deleteButtonTarget.classList.add('d-none');
        this.deleteFormTarget.classList.remove('d-none');
    }
}