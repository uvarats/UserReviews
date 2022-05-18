import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";
import axios from "axios";

export default class extends Controller {
    static targets = [ 'modal' ];
    static values = {
        removeUrl: String
    };
    static modal;
    remove_modal(e){
        e.preventDefault();

        this.modal = new Modal(this.modalTarget);
        this.modal.show();
    }
    remove_review(e){
        e.preventDefault();

        axios.post(this.removeUrlValue)
            .then(function (response) {
                console.log(response.data);
                this.modal.hide();
            });
    }
}