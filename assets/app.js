/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

//console.log('Hi!, dude');
import getNiceMessage  from './js/get_nice_message';

import $ from 'jquery';

//global.$ = $;

$(document).ready(function(){
    $("button").click(function(){
        $("p").hide();
    });
});

console.log(getNiceMessage(18));
console.log(getNiceMessage(4));
// start the Stimulus application
import './bootstrap';


