import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller{
    static values = {
        requestUrl: String
    }
    add(e){
        e.preventDefault();
    }
    remove(e){
        e.preventDefault();

        axios.get(this.requestUrlValue)
            .then((response) => {
                console.log(response);
            });
    }
}

