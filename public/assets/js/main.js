/*
 Template Name: Neon - Bootstrap + Laravel + PHP Admin Dashboard Template
 Website: http://themesbox.in/admin-templates/neon
 Author: Themesbox17
 File: Main JS File
 */

"use strict";
$(document).ready(function() {       

    /* -----  Menu JS ----- */
    $.sidebarMenu($('.xp-vertical-menu'));      

    $(function() {
        for (var x = window.location, xp = $(".xp-vertical-menu a").filter(function() {
            return this.href == x;
        }).addClass("active").parent().addClass("active"); ;) {
            if (!xp.is("li")) break;
            xp = xp.parent().addClass("in").parent().addClass("active");
        }
    }), 

    /* -----  Menu Hamburger ----- */
    $("body").on("click", '.xp-menu-hamburger',function(e) {
        e.preventDefault();
        $("body").toggleClass("xp-toggle-menu");
    });   

    // $('body').on('click','.xp-userprofile',function(){
    //     var data = $('.xp-userprofile-div').data('toggle')
    //     console.log(data)

    //     if(data == true){
    //         $('.xp-userprofile-div').data('toggle','false')
    //         $('.xp-userprofile-div').hide()
    //     }else{
    //         $('.xp-userprofile-div').data('toggle','true')
    //         $('.xp-userprofile-div').show()
    //     }
    // })
    /* -----  Bootstrap Popover ----- */
    $('[data-toggle="popover"]').popover();

    /* -----  Bootstrap Tooltip ----- */
    $('[data-toggle="tooltip"]').tooltip();

});