// $("document").ready(function(){
//     setTimeout(function(){
//         $("div.alert").remove();
//     }, 5000 ); // 5 secs
// });

$(document).ready(function(){
    $("div.alert").delay(5000).slideUp(300);
    // setTimeout(function() {
    //     $("div.alert").fadeOut('fast');
    // }, 5000); 
});