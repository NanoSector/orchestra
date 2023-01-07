import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['pinButton'];
    static values = { pinned: Boolean }

    togglePin() {
        if (this.pinnedValue) {

        }
    }
}