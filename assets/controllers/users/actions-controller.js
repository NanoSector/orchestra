import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['deleteButton', 'deleteForm'];
    static values = { deleteUrl: String }

    confirmDelete() {
        this.deleteButtonTarget.classList.add('d-none');
        this.deleteFormTarget.classList.remove('d-none');
    }
}