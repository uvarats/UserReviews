import { Controller } from "@hotwired/stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = [ 'count' ];

    static values = {
        requestUrl: String
    };
    like(e){
        e.preventDefault();
        let likes = this.countTarget;
        let heart = this.element.getElementsByClassName('fa-heart')[0];
        console.log(likes.textContent);
        if(heart.classList.contains('fa-regular')){
            heart.classList.remove('fa-regular');
            heart.classList.add('fa-solid');
        } else if(heart.classList.contains('fa-solid')){
            heart.classList.remove('fa-solid');
            heart.classList.add('fa-regular');
        }
        axios.post(this.requestUrlValue)
            .then((response) => {
                likes.textContent = response.data.new_count;
            });
    }
}