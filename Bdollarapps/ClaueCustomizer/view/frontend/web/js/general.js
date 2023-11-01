require(['jquery','domReady!','Magento_Ui/js/modal/modal'], function($, modal){ 

    "use strict";
    // Alert box options
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'alert-box',
        title: "Info",
        buttons: [
            {
                text: $.mage.__('Yes'),
                class: 'agree button',
                click: function () {
                    console.log('Agreed');
                    $('#app_access_no').prop("checked", false);
                    $('#app_access_yes').prop("checked", true);
                    this.closeModal();
                }
            },
            {
                text: $.mage.__('No'),
                class: 'not-agree button',
                click: function () {
                    console.log('Not Agreed');
                    $('#app_access_no').prop("checked", true);
                    $('#app_access_yes').prop("checked", false);
                    this.closeModal();
                }
            }
        ]
    };

    // $(document).ready(function($){
    //     console.log("Bdollar custom script loading...");
    // });

    // Get started page customiser

    // when clicked start show the finance section
    $('.get-started button').click(function(){
        $('.finance-section').show();
        $('.no-response').hide();
    });
    // when clicked no show the sorry message and close finance section
    $('.cancel-button button').click(function(){
        $('.finance-section').hide();
        $('.no-response').show();
    });
    // below rvp user not able to submit form
    $('[data-direction="next"]').click(function(){
        setInterval(function(){
            if($('#step10').hasClass('active')){
                console.log('active thank you !');
                $('[data-direction="finish"]').hide();
            }
        }, 1000);
    });

    // Agent Add or Edit Info logics

    $('[name="life_licensed"]').click(
        function(){
            console.log('clicked'); 
            if($('#life_licensed_yes').val() == 1){ 
                console.log('security licensed is checked, so please uncheck life license'); 
                $('#securities_licensed_no').prop("checked", true);
                $('.alert_showing').attr("style", "visibility: visible").show().delay(5000).fadeOut();
            }
        }
    );

    $('[name="securities_licensed"]').click(
        function(){
            console.log('clicked'); 
            if($('#securities_licensed_yes').val() == 1){ 
                console.log('life licensed is checked, so please uncheck security license'); 
                $('#life_licensed_no').prop("checked", true);
                $('.alert_showing').attr("style", "visibility: visible").show().delay(5000).fadeOut();
            }
        }
    );

    // alert if rvp provide app access

    $('[name="app_access"]').click(
        function(){
            $("#appAccess").attr("style", "visibility: visible").show();
            console.log('app access radio button clicked');
            if($('#app_access_yes').val() == 1){
                console.log('By giving app access, you certify that the agent is at least life licensed and has successfully tested out on level 1 & 2.');
                $("#appAccess").modal(options).modal('openModal');
            }
        }
    );

    // copy the email address in the agent edit form to send invitation form
    $('[name="field[64]"]').val($('#email_address').val());

    // check if level is disabled for course dashboard section

    if($(".level-1-button").is('[disabled]')){
        $(".level-1").hide();
    }
    if($(".level-2-button").is('[disabled]')){
        $(".level-2").hide();
    }
    if($(".level-3-button").is('[disabled]')){
        $(".level-3").hide();
    }
    if($(".level-4-button").is('[disabled]')){
        $(".level-4").hide();
    }

    // survey form changes

    $(".survery-form-index a.logo").removeAttr("href");

    // load ready to subscribe page to subscribe section

    $(document).ready(function(){
        $("html, body.cms-ready-to-subscribe").animate({ 
            scrollTop: $('.subscribtion-product-custom').offset().top 
        }, 1000);
    });
    
 })
