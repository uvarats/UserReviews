import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import axios from "axios";

export default class extends Controller {
    static targets = [ 'modal' ];
    static values = {
        removeUrl: String
    };

    remove_modal(e){
        e.preventDefault();
        console.log('working');
        this.confirm_modal = new Modal(this.modalTarget);
        console.log(this.confirm_modal);
        this.confirm_modal.show();
    }
    remove_review(e){
        e.preventDefault();
        axios.post(this.removeUrlValue)
            .then(function (response) {
                console.log(response.data);
                location.reload();
            });
        this.confirm_modal.hide();
    }
}