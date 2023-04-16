"use strict";
function goBack(step='-1'){
    window.history.go(""+step+"");
}
function googleTranslateElementInit(){
    new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
var maxLength = max_lenght