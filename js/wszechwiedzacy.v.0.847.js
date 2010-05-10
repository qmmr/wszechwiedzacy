$(window).load(function(){
    // this loads after all elements on the page has been downloaded
    //alert("done!");
});
// reCaptcha style
var RecaptchaOptions = {
    custom_translations: {
        visual_challenge: "Załaduj słowa",
        audio_challenge: "Odsłuchaj nagranie",
        refresh_btn: "Załaduj nowe słowa",
        instructions_visual: "Przepisz powyższe słowa",
        instructions_audio: "Wpisz co usłyszałeś:",
        help_btn: "Pomoc",
        play_again: "Odtwórz nagranie jeszcze raz",
        cant_hear_this: "Zapisz nagranie jako .mp3",
        incorrect_try_again: "Niepoprawnie, spróbuj jeszcze raz."
    },
    theme: 'white'
};
// button states for the form buttons
function hoverO(){
    $(this).addClass("ui-state-hover");
}

function hoverT(){
    $(this).removeClass("ui-state-hover");
}

function mDown(){
    $(this).addClass("ui-state-active");
}

function mUp(){
    $(this).removeClass("ui-state-active");
}

// main object
var wszechwiedzacy = {
    // placeholder for site URL
    site_url: {},
    // shows current location for debugging
    curLoc: function(){
        alert("wszechwiedzacy.curLoc -> " + window.location);
    },
    // initialized on every page
    init: function(){
        // checks if we're developing on localhost or live online
        (window.location.hostname == "localhost") ? wszechwiedzacy.site_url = "http://localhost/wszechwiedzacy/" : wszechwiedzacy.site_url = "http://wszechwiedzacy.pl/";
        //	alert("location is "+window.location.hostname+" so the url is "+wszechwiedzacy.site_url);
        (window.location == wszechwiedzacy.site_url + "admin/pytania/") ? wszechwiedzacy.admin.init() : false;
        (window.location == wszechwiedzacy.site_url + "admin/") ? $("#tabs").tabs() : false;
        // LOGIN / REGISTER
        $("#login").click(function(e){
        
            e.preventDefault();
            my_form = $("#login_dialog");
            $("#login_dialog").dialog("open");
            $(".dialogLink").show();
            
        });
        
        $("#register").click(function(e){
        
            e.preventDefault();
            my_form = $("#register_dialog");
            $("#register_dialog").dialog("open");
            
        });
        
        // link to forgotDialog (recover forgotten nick or password)
        $("#forgot").click(function(){
        
            $("#login_dialog").dialog("close");
            $("#forgot_dialog").dialog("open");
            
        });
        
        // changeDialog (from login to register)
        $("#changeDialog").click(function(){
        
            $("#login_dialog").dialog("close");
            $("#register_dialog").dialog("open");
            
        });
        
        // LOGIN DIALOG
        $("#login_dialog").dialog({
        
            open: function(event, ui){
            
                my_form = $("#login_form");
                my_form.find("#login_email").focus();
                $(this).keyup(function(e){
                
                    var f = my_form.find("input:first").val();
                    var s = my_form.find("input:last").val();
                    // alert(f+" "+s);
                    if (e.keyCode == 13 && f != "" && s != "") {
                    
                        // e.preventDefault();
                        // alert("keyCode "+e.keyCode+" was pressed and we have no empty
                        // inputs");
                        my_form.closest('.ui-dialog').find(".ui-dialog-buttonpane button:eq(0)").trigger("click");
                        // my_form.submit();
                    
                    }
                    
                });
                
            },
            autoOpen: false,
            draggable: false,
            resizable: false,
            bgiframe: true,
            modal: true,
            title: 'Logowanie',
            beforeclose: function(){
            
                // this function fires when close is pressed, resets warnings and clears
                // input
                var validator = my_form.validate();
                validator.resetForm();
                $("input:filled").val("").removeClass("valid");
                
            },
            buttons: {
            
                Loguj: function(){
                
                    var validator = my_form.validate({
                    
                        rules: {
                        
                            email: {
                                required: true,
                                email: true
                            },
                            password: "required"
                        
                        },
                        messages: {
                        
                            email: {
                                required: "Adres email jest wymagany.",
                                email: "To nie jest poprawny adres email."
                            },
                            password: "Proszę podać swoje hasło ..."
                        
                        }
                    
                    }).form();
                    
                    // if the form is valid we can send the input to php
                    if ($("#login_form").valid()) {
                    
                        $("#login_form").hide(); // hide form
                        $("#login_dialog .ajaxLoader").show(); // show image ajax-loader.gif
                        $(".dialogLink").hide(); // hide link to change dialogs to register
                        var email = $("#login_email").val();
                        var pwd = $("#login_password").val();
                        var user_data = "email=" + email + "&password=" + pwd;
                        //alert(user_data);
                        
                        $.ajax({
                        
                            type: "POST",
                            data: user_data,
                            url: wszechwiedzacy.site_url + "includes/logowanie.php",
                            dataType: "json",
                            success: function(data){
                            
                                var email = data.email;
                                var pwd = data.password;
                                // alert( email + " and " + pwd );
                                if (email == "valid" && pwd == "valid") {
                                
                                    // alert('user found -> proceed to login');
                                    window.location.reload();
                                    
                                }
                                else {
                                
                                    $("#login_dialog .ajaxLoader").hide(); // hide ajax-loader.gif
                                    $("#login_form").show(); // showing form// again
                                    // do sth when php don't find user
                                    
                                    if (email == "inactive") {
                                    
                                        // alert("user not found");
                                        var validator = $("#login_form").validate();
                                        validator.showErrors({
                                            "email": "Konto nie zostało aktywowane!"
                                        });
                                        
                                    }
                                    else 
                                        if (email == "invalid") {
                                        
                                            var validator = $("#login_form").validate();
                                            validator.showErrors({
                                                "email": "Nie ma takiego użytkownika!"
                                            });
                                            
                                        }
                                        else {
                                        
                                            // alert("user != password");
                                            var validator = $("#login_form").validate();
                                            validator.showErrors({
                                                "password": "Hasło nie pasuje do użytkownika!"
                                            });
                                            
                                        }
                                    
                                } // end if/else valid
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                alert("błąd podczas logowania: " + textStatus);
                            }
                            
                        }); // end ajax
                    } // end of client_valid
                } // end of loguj function
            } // end of buttons
        }); // end of login dialog
        
        // FORGOT DIALOG
        $("#forgot_dialog").dialog({
        
            open: function(event, ui){
            
                $(this).find("#email").focus();
                // alert( $(this).attr("id") );
                $(this).keyup(function(e){
                
                    if (e.keyCode == 13) {
                    
                        // alert(e.keyCode+" was pressed");
                        // $(this).formSubmit();
                        $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').trigger("click");
                        
                    }
                    
                });
                
            },
            autoOpen: false,
            draggable: false,
            resizable: false,
            bgiframe: true,
            modal: true,
            title: 'Odzyskiwanie hasła',
            beforeclose: function(){
            
                var validator = $("#forgot_form").validate();
                validator.resetForm();
                $("input:filled").val("");
                
            },
            // height: 380,
            width: 320,
            buttons: {
            
                Odzyskaj: function(){
                
                    var validator = $("#forgot_form").validate({
                    
                        rules: {
                            email: {
                                required: true,
                                email: true
                            }
                        },
                        messages: {
                        
                            email: {
                                required: "Musisz podać swój adres email.",
                                email: "To nie jest poprawny adres email."
                            }
                        
                        }
                    
                    }).form();
                    
                    if ($("#forgot_form").valid()) {
                    
                        // hide form and show image ajax-loader.gif
                        $("#forgot_form").hide();
                        $("#forgot_dialog .ajaxLoader").show();
                        var recover = "email=" + $("#lost_email").val();
                        // alert(recover);
                        
                        $.ajax({
                        
                            type: "POST",
                            data: recover,
                            url: wszechwiedzacy.site_url + "includes/recover_email.php",
                            dataType: "json",
                            success: function(data){
                            
                                var email = data.email;
                                alert(email);
                                
                                if (email == "sent") {
                                
                                    // alert("nowe hasło zostało wysłane na zarejestrowany adres email!");
                                    $("#forgot_dialog").dialog('close');
                                    
                                }
                                else {
                                
                                    $("#forgot_dialog .ajaxLoader").hide(); // hide ajax-loader.gif
                                    $("#forgot_form").show(); // showing form again
                                    // do sth when php don't find email
                                    if (email == "invalid") {
                                    
                                        var validator = $("#forgot_form").validate();
                                        validator.showErrors({
                                            "email": "To nie jest poprawny adres email!"
                                        });
                                        
                                    }
                                    else 
                                        if (email == "not posted") {
                                        
                                            var validator = $("#forgot_form").validate();
                                            validator.showErrors({
                                                "email": "Musisz podać adres email!"
                                            });
                                            
                                        }
                                        else 
                                            if (email == "database empty") {
                                            
                                                var validator = $("#forgot_form").validate();
                                                validator.showErrors({
                                                    "email": "Nie ma takiego adresu w naszej bazie!"
                                                });
                                                
                                            }
                                            else 
                                                if (email == "mail failed") {
                                                
                                                    var validator = $("#forgot_form").validate();
                                                    validator.showErrors({
                                                        "email": "Oops! Strajk na poczcie, email nie został wysłany :("
                                                    });
                                                    
                                                }
                                                else {
                                                
                                                    alert("Rzadko się zdarza taki błąd: " + email);
                                                    
                                                }
                                    
                                } // end of email validation
                                $("#forgot_dialog .ajaxLoader").hide(); // hide ajax-loader.gif
                                $("#forgot_form").show(); // showing form again
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                alert("ajax call to recover_email failed, reason: " + textStatus);
                            }
                            
                        }); // end ajax
                    } // end of #forgot_form
                } // end of odzyskaj button
            } // end of buttons
        }); // end of forgot dialog
        
        // REGISTER DIALOG
        $("#register_dialog").dialog({
        
            open: function(event, ui){
            
                my_form = $("#register_form");
                my_form.find("#reg_username").focus();
                // $(this).keyup(function(e) {
                // var filled = my_form.find("input:filled").length;
                // if (e.keyCode == 13 && filled == 4) {
                // e.preventDefault();
                // //alert("keyCode "+e.keyCode+" was pressed and we have no empty
                // inputs");
                // my_form.closest('.ui-dialog').find(".ui-dialog-buttonpane
                // button:eq(0)").trigger("click");
                // }
                // });
            
            },
            autoOpen: false,
            draggable: false,
            resizable: false,
            bgiframe: true,
            modal: true,
            zIndex: 9999,
            title: 'Rejestracja gracza',
            beforeclose: function(){
            
                var validator = my_form.validate();
                validator.resetForm();
                $("input:filled").val("").removeClass("valid");
                
            },
            width: 350,
            buttons: {
            
                Zarejestruj: function(){
                
                    var validator = my_form.validate({
                    
                        rules: {
                            reg_username: {
                                required: true,
                                rangelength: [3, 14]
                            },
                            reg_password: {
                                required: true,
                                rangelength: [4, 14]
                            },
                            reg_password2: {
                                required: true,
                                equalTo: '#reg_password'
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            year: "required",
                            month: "required",
                            day: "required",
                            akc_regulamin: "required"
                        },
                        // errorElement: "div",
                        // wrapper: "div", // a wrapper around the error message
                        errorPlacement: function(error, element){
                        
                            if (element.attr('type') == 'checkbox') {
                            
                                element = element.parent();
                                element.css("border", "1px solid #820F00");
                                
                            }
                            offset = element.offset();
                            error.insertAfter(element);
                            error.addClass('checkbox_error');
                            // error.css('position', 'absolute');
                            // error.css('left', offset.left + element.outerWidth());
                            // error.css('top', offset.top);
                        
                        },
                        messages: {
                        
                            reg_username: {
                                required: "Proszę podać nazwę użytkownika.",
                                rangelength: "Minimalna ilość znaków 3, max 14."
                            },
                            reg_password: {
                                required: "Proszę podać hasło.",
                                rangelength: "Minimalna ilość znaków 4, max 14."
                            },
                            reg_password2: {
                                required: "Proszę potwierdzić hasło.",
                                equalTo: "Hasła nie są identyczne."
                            },
                            email: {
                                required: "Adres email jest wymagany.",
                                email: "To nie jest poprawny adres email."
                            },
                            year: "",
                            month: "",
                            day: "",
                            akc_regulamin: ""
                        }
                    
                    }).form();
                    
                    // if the form is valid we can send the input to php
                    if (my_form.valid()) {
                    
                        $("#register_dialog .ajaxLoader").show(); // ajax-loader.gif
                        $("#register_form").hide();
                        var usr = $("#reg_username").val();
                        var pwd = $("#reg_password").val();
                        var pwd2 = $("#reg_password2").val();
                        var email = $("#reg_email").val();
                        var birthdate = $("#year").val() + "-" + $("#month").val() + "-" + $("#day").val();
                        var mailing = $("#lista_mailingowa").attr("checked");
                        var rcf = $("#register_dialog #recaptcha_challenge_field").val();
                        var rrf = $("#register_dialog #recaptcha_response_field").val();
                        var data_str = "username=" + usr + "&password=" + pwd + "&password2=" + pwd2 + "&email=" + email + "&birthday=" + birthdate + "&mailing=" + mailing + "&recaptcha_challenge_field=" + rcf + "&recaptcha_response_field=" + rrf;
                        // alert(data_str);
                        // alert("data: " + data_str + "serialize: " + serial);
                        
                        $.ajax({
                        
                            type: "POST",
                            data: data_str,
                            url: wszechwiedzacy.site_url + "includes/rejestracja.php",
                            dataType: "json",
                            success: function(data){
                            
                                // alert(data.user+" | "+data.password+" | "+data.email+" | "+data.mysql+ " | "+data.recaptcha);
                                // ajax-loader.gif
                                $("#register_dialog .ajaxLoader").hide();
                                $("#register_form").show();
                                if (data.user == "valid" && data.password == "valid" && data.email == "sent" && data.mysql == "valid" && data.recaptcha == "valid") {
                                
                                    // alert(data.mysql);
                                    $("#register_dialog").dialog('close');
                                    $("#header").after("<div class=\"info_block\"><p>Wiadomość z kodem aktywacyjnym wysłaliśmy na adres <a href=\"mailto:" + email + "\">" + email + "</a></p><p>Aby dokończyć proces aktywacji, kliknij na link znajdujący się w wiadomości.</p></div>");
                                    $(".info_block").animate({
                                        "height": "toggle",
                                        "opacity": "toggle"
                                    }, 1000, "easeOutQuint");
                                    
                                    var cd = setTimeout(function(){
                                    
                                        $(".info_block").animate({
                                            "height": "toggle",
                                            "opacity": "toggle"
                                        }, 2000, "easeInQuint", function(){
                                            $(this).remove();
                                        });
                                        
                                    }, 5000);
                                    
                                }
                                else {
                                
                                    $("#register_dialog .ajaxLoader").hide();
                                    $("#register_form").show();
                                    var validator = $("#register_form").validate();
                                    if (data.user == "taken") {
                                    
                                        validator.showErrors({
                                            "reg_username": "Ten nick jest już zarejestrowany!"
                                        });
                                        
                                    }
                                    if (data.email == "taken") {
                                    
                                        validator.showErrors({
                                            "email": "Adres email jest już zarejestrowany!"
                                        });
                                        
                                    }
                                    if (data.email == "error") {
                                    
                                        alert("Przepraszamy! Wystąpił błąd podczas rejestracji i email z kluczem potwierdzającym nie został wysłany. Aby aktywować konto proszę skontaktować się z nami pod adresem kontakt@wszechwiedzacy.com");
                                        
                                    }
                                    if (data.mysql != "valid") {
                                    
                                        alert("Oops! Wystąpił błąd podczas przetwarzania informacji w bazie MySQL, spróbuj jeszcze raz lub skontaktuj się z nami pod adresem kontakt@wszechwiedzacy.com");
                                        
                                    }
                                    if (data.recaptcha == "wrong") {
                                    
                                        // validator.showErrors({"recaptcha": "Przepisane wyrazy są
                                        // niepoprawne!"});
                                        alert(data.recaptcha + " || Przepisane wyrazy są nieprawidłowe!");
                                        
                                    }
                                    
                                } // end of data validation
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                alert("Błąd podczas rejestracji: " + textStatus);
                            }
                            
                        }); // end of ajax call
                    } // end of if(client_valid)
                } // end of zarejestruj function
            } // end of buttons
        }); // end of register dialog
        // LOGOUT via AJAX
        $("#logout").click(function(e){
        
            e.preventDefault();
            // alert("Logout");
            
            $.ajax({
            
                type: "POST",
                url: wszechwiedzacy.site_url + "includes/logowanie.php",
                success: function(data){
                    window.location = wszechwiedzacy.site_url;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert("błąd podczas wylogowania: " + textStatus);
                }
                
            })
            
        }); // end #logout.click
    }, // end of wszechwiedzacy.init()     
    gra: {
        cdnum: 3, // var countdown number
        countdown: {}, // var to store the setTimeout method for clearing timeout
        init: function(){
            location.href = "#crumb"; // centers the game area
            /**
             * EXPOSE THE GAME AREA when we go to gra.php it will dimm out all
             * but the #mainContent just to make sure the player focuses on the
             * questions
             */
            var mc = $("#mainContent").expose({
                color: '#17373C',
                opacity: 0.75,
                loadSpeed: 0,
                api: true,
                onBeforeClose: function(e){
                    qd.dialog("open");
                } // if we click on the grey area a quit dialog pops
            }).load(); // loads expose tool
            // QUIT DIALOG
            var qd = $("#quit_dialog").dialog({
                open: wszechwiedzacy.changeFocus,
                autoOpen: false,
                modal: true,
                resizable: false,
                closeOnEscape: false,
                zIndex: 9999,
                width: 320,
                title: 'Uwaga!',
                buttons: {
                    Tak: function(){
                        window.location.replace(wszechwiedzacy.site_url);
                    },
                    Nie: function(){
                        qd.dialog("close");
                        mc.load();
                    } // mc.load loads expose tool		     
                } // end of buttons
            }); // end of quit dialog
            // initialize the setButtons
            wszechwiedzacy.gra.setButtons();
            
			/*
            // testing function
            $().keyup(function(e){
                // select radio buttons when 1,2,3,4 is pressed -> to do <-
                switch (e.keyCode) {
                    case 49:
                    case 97:
                        $("label:eq(0)").click(wszechwiedzacy.gra.labelCheck());
                        break;
                    case 50:
                    case 98:
                        $("label:eq(1)").click(wszechwiedzacy.gra.labelCheck());
                        break;
                    case 51:
                    case 99:
                        $("label:eq(2)").click(wszechwiedzacy.gra.labelCheck());
                        break;
                    case 52:
                    case 100:
                        $("label:eq(3)").click(wszechwiedzacy.gra.labelCheck());
                        break;
                    case 13:
                        if ($("form label.radio_check").length != 0) {
                            $("#submitButton").click();
                        }
                        break;
                    default:
                        break;
                }
                //alert("other key was pressed "+e.keyCode);
            });
            */
			
        }, // end of gra.init()
        setButtons: function(){
            // alert("setting Buttons!");
            $("button").each(function(){
                $(this).click(function(e){
                    e.preventDefault();
                    var id = $(this).attr("id"); // assign buttons id to check what button was clicked in switch
                    //alert(id);
                    switch (id) {
                        case "start":
                            $("#startWrap").remove();
                            wszechwiedzacy.gra.showNextQuestion();
                            break;
                            
                        case "submitButton":
                            $("#scored").val(wszechwiedzacy.time.pts); // assigns scored points to hidden input so that it can be passed to php creates vars to store answer and correct answer
                            var odp = $("input[selected]").val();
                            var poprawna = $("input[name=\"poprawna\"]").val();
                            var punkty = $("#scored").val();
                            var odpowiedzi = "group=" + odp + "&poprawna=" + poprawna + "&punkty=" + punkty;
//                          alert(odpowiedzi);
                            $("#pytanieWrap").remove();
                            $("#loaderContainer").show(); // show the animation gif
                            // we can receive data from php either by reading echo values or reading arrays in json
                            // to specify we add dataType: "json" or "text" to set single value we make var some_value = data;
                            // to set multiple values from json we specify key => value from associative array ... = data['some_value']
                            $.ajax({
                                type: "POST",
                                data: odpowiedzi,
                                url: wszechwiedzacy.site_url + "includes/odpowiedz.php",
                                dataType: "json",
                                success: function(data){
                                
                                    var game = data['game_state']; // data from php dictates what to show next
                                    switch (game) {
                                        case "over":
                                            // game over
                                            $.ajax({
                                                url: wszechwiedzacy.site_url + "includes/wynik.php",
                                                success: function(data){
                                                
                                                    $("#mainContent").append(data);
                                                    $("#breakWrap").fadeIn(1000);
                                                    $("#loaderContainer").hide(); // hides the animation gif
                                                    $("#wrong1").tooltip({
                                                        tip: "#tooltip1",
                                                        delay: 250,
                                                        offset: [-5, 0],
                                                        effect: 'slide',
                                                        slideOffset: 10,
                                                        slideInSpeed: 250,
                                                        slideOutSpeed: 100,
                                                        bounce: true
                                                    });
                                                    $("#wrong2").tooltip({
                                                        tip: '#tooltip2',
                                                        delay: 250,
                                                        offset: [-5, 0],
                                                        effect: 'slide',
                                                        slideOffset: 10,
                                                        slideInSpeed: 250,
                                                        slideOutSpeed: 100,
                                                        bounce: true
                                                    });
                                                    $("#wrong3").tooltip({
                                                        tip: '#tooltip3',
                                                        delay: 250,
                                                        offset: [-5, 0],
                                                        effect: 'slide',
                                                        slideOffset: 10,
                                                        slideInSpeed: 250,
                                                        slideOutSpeed: 100,
                                                        bounce: true
                                                    });
                                                    $("#tooltip1").appendTo("body");
                                                    $("#tooltip2").appendTo("body");
                                                    $("#tooltip3").appendTo("body");
                                                    $("p.know").click(function(){
                                                    
                                                        $(this).hide().next("p.correct").fadeIn(500);
                                                        
                                                    });
                                                    
                                                },
                                                error: function(XMLHttpRequest, textStatus, errorThrown){
                                                    alert("ajax call to wynik.php failed, reason: " + textStatus);
                                                }
                                                
                                            });
                                            break;
                                            
                                        case "advance":
                                            // game advance (we move to the next round)
                                            $.ajax({
                                                url: wszechwiedzacy.site_url + "includes/runda.php",
                                                success: function(data){
                                                
                                                    $("#mainContent").append(data);
                                                    $("#breakWrap").fadeIn(1000);
                                                    wszechwiedzacy.gra.setButtons();
                                                    $("#loaderContainer").hide(); // hides the
                                                // animation gif
                                                
                                                },
                                                error: function(XMLHttpRequest, textStatus, errorThrown){
                                                
                                                    alert("ajax call to runda.php failed, reason: " + textStatus);
                                                    
                                                }
                                                
                                            });
                                            break;
                                            
                                        default:
                                            // just another question
                                            wszechwiedzacy.gra.showNextQuestion();
                                            break;
                                    } // end of swith(game)
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown){
                                
                                    alert("ajax call to odpowiedz.php failed, reason: " + textStatus);
                                    
                                }
                                
                            });
                            break;
                            
                        case "continue":
                            $("#breakWrap").remove();
                            wszechwiedzacy.gra.showNextQuestion();
                            break;
                            
                        case "wprowadzenie":
                            alert("zróbmy jakieś wprowadzenie");
                            break;
                        case "rejestracja":
                            e.preventDefault();
                            my_form = $("#register_dialog");
                            $("#register_dialog").dialog("open");
                            break;
                        default:
                            break;
                            
                    } // end of swich(id)
                }); // /(this).click
            }).hover(hoverO, hoverT).mouseup(mUp).mousedown(mDown); // /each function
        }, // end of wszechwiedzacy.gra.setButtons
        setupRadioButtons: function(){
        
            // checks all input of type radio if they have class radioNormal
            // if true they are removed and radioHidden are added
			var tick = $("#tick");
			var odpowiedziForm = $("#odpowiedzi");
			var labels = $("label", odpowiedziForm);
//			var radioBtn = $("input:radio", odpowiedziForm);
			
//            (radioBtn.hasClass("radioNormal")) ? radioBtn.removeClass("radioNormal").addClass("radioHidden") : false;
            
            // next all labels receive click function that checks if they have class radio_check            			
			labels.hover(
				function(){
					$(this).stop().animate({
														"backgroundColor":"#e6f6cd",
														"borderTopColor":"#58742b",
														"borderRightColor":"#58742b",
														"borderBottomColor":"#58742b",
														"borderLeftColor":"#58742b"
													}, 250, "easeOutSine");
				},
				function(){
					$(this).stop().animate({
														"backgroundColor":"#F6F6F6",
														"borderTopColor":"#F6F6F6",
														"borderRightColor":"#F6F6F6",
														"borderBottomColor":"#F6F6F6",
														"borderLeftColor":"#F6F6F6"
														}, 250, "easeOutSine");
				}
			).bind("click", function(e){
				
				// check if any other answer was chosen and deselect it
				var lc = $(".radio_check", odpowiedziForm);
				if(lc.length != 0) {
					lc.toggleClass("radio_check").next().removeAttr("selected");					
				}
				
				var thisLabel = $(e.target);
				var thisInput = thisLabel.next();
				thisLabel.toggleClass("radio_check").next().attr("selected", "selected");
				
				if(thisLabel.hasClass("radio_check")) {
					
					thisLabel.next().attr("selected", "selected");
					thisInput.after(tick);
					$("#submitButton").fadeIn(500);
					tick.css({"top": -10}).stop().animate({"top": 0}, 500, "easeOutBounce");
															
				}        				
				
			});
        
        }, // end of wszechwiedzacy.gra.setupRadioButtons
        showNextQuestion: function(){
        
            $("#loaderContainer").show(); // show the animation gif
            clearInterval(wszechwiedzacy.time.newInt);
            
            $.ajax({
            
                type: "POST",
                url: wszechwiedzacy.site_url + "includes/pytanie.php",
                success: function(data){
                    wszechwiedzacy.gra.loadTheQuestion(data);
                },
                error: function(){
                    alert("The ajax call 'showNextQuestion' was a FAILURE!");
                }
                
            }); // end of ajax call to pytanie.php
        }, // end of wszechwiedzacy.gra.showNextQuestion
        loadTheQuestion: function(data){
        
            $("#mainContent").append(data); // insert html generated by pytanie.php
            wszechwiedzacy.gra.setupRadioButtons(); // adjust visual style of input radio buttons
            $("#submitButton").hide(); // store the button in var and hide
            wszechwiedzacy.gra.setButtons(); // set up function that runs code depending on what button was pressed
            // next fades in #pytanieWrap and after finishes it starts the counter (callback)
            $("#pytanieWrap").fadeIn(500, function(){
                pytanie.fadeIn(500, callback);
            });
            $("#loaderContainer").hide(); // hides the animation gif
            var pytanie = $("#tresc > p").hide();
            var odpowiedzi = $("p.answer").hide();
            $("#progressbar").progressbar({
                value: 100
            });
            
            function callback(){
                wszechwiedzacy.gra.cdnum = 0;
                wszechwiedzacy.gra.countdown = setTimeout("wszechwiedzacy.time.showLabels()", 1500);
                // pytanie.animate({left: "10px", display: "block"}, 1000,
                // "easeOutExpo");
            } // /callback
        } // /loadTheQuestion
    }, // end of wszechwiedzacy.gra
    admin: {
    
        oTable: {}, // empty var to store pytania table
        id: {}, // question id to send to mysql
        trNumber: {}, // oData row number
        init: function(){
        
            //alert("admin init");
            // INITIALIZE DATATABLE
            wszechwiedzacy.admin.oTable = $("#pytaniaAdmin").dataTable({
                "bJQueryUI": true,
                "aoColumns": [{
                    "sWidth": "50px"
                }, {
                    "sWidth": "60px"
                }, {
                    "sWidth": "135px"
                }, null, {
                    "sWidth": "55px"
                }, {
                    "sWidth": "50px"
                }, ],
                "sPaginationType": "full_numbers",
                "oLanguage": {
                    "sProcessing": "Proszę czekać ...",
                    "sLengthMenu": "Wyświetl _MENU_ pytań na stronie",
                    "sZeroRecords": "Nie znaleziono żadnych pasujących indeksów",
                    "sInfo": "Pozycje od _START_ do _END_ z _TOTAL_ pytań",
                    "sInfoEmpty": "Pozycji 0 z 0 dostępnych",
                    "sInfoFiltered": "(filtrowanie spośród _MAX_ dostępnych pytań)",
                    "sInfoPostFix": "",
                    "sSearch": "Szukaj:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Pierwsza",
                        "sPrevious": "Poprzednia",
                        "sNext": "Następna",
                        "sLast": "Ostatnia"
                    }
                },
                "bStateSave": true // remember last position
            }); // end of oTable
            // QUESTION ADD DIALOG
            $("#frmQuestionAdd").dialog({
                autoOpen: false,
                modal: true,
                resizable: false,
                title: 'Dodaj pytanie',
                width: 660,
                beforeclose: function(){
                    // this function fires when close is pressed, resets warnings and
                    // clears input
                    var validator = $("#frmQuestionAdd").validate();
                    validator.resetForm();
                    $("input:filled").val("");
                    $("textarea:filled").val("");
                    $("select").val("");
                },
                buttons: {
                    Dodaj: function(e){
                        e.preventDefault();
                        var runda = $("#runda").val();
                        var autor = $("#frmQuestionAdd").attr("name");
                        var kategoria = $("#kategoria").val();
                        var tresc = $("#tresc").val();
                        var odp_a = $("#odp_a").val();
                        var odp_b = $("#odp_b").val();
                        var odp_c = $("#odp_c").val();
                        var odp_d = $("#odp_d").val();
                        var poprawna = "";
                        if ($("#poprawna").val() == 1) {
                            poprawna = $("#odp_a").val();
                        }
                        else 
                            if ($("#poprawna").val() == 2) {
                                poprawna = $("#odp_b").val();
                            }
                            else 
                                if ($("#poprawna").val() == 3) {
                                    poprawna = $("#odp_c").val();
                                }
                                else {
                                    poprawna = $("#odp_d").val();
                                }
                        var link = $("#link").val();
                        var question = "runda=" + runda + "&autor=" + autor + "&kategoria=" + kategoria + "&tresc=" + tresc + "&odp_a=" + odp_a + "&odp_b=" + odp_b + "&odp_c=" + odp_c + "&odp_d=" + odp_d + "&poprawna=" + poprawna + "&link=" + link;
                        // alert(question);
                        var validator = $("#frmQuestionAdd").validate({
                            rules: {
                                runda: "required",
                                kategoria: "required",
                                tresc: {
                                    required: true,
                                    minlength: 10,
                                    maxlength: 255
                                },
                                odp_a: "required",
                                odp_b: "required",
                                odp_c: "required",
                                odp_d: "required",
                                poprawna: "required",
                                link: {
                                    required: true,
                                    url: true,
                                    maxlength: 255
                                }
                            },
                            messages: {
                                runda: "Wybierz rundę",
                                kategoria: "Podaj kategorię",
                                tresc: {
                                    required: "Podaj treść pytania",
                                    minlength: "Pytanie jest za krótkie, min. 10 znaków",
                                    maxlength: "Pytanie jest za długie, max. 255 znaków"
                                },
                                odp_a: "Podaj treść odpowiedzi #1",
                                odp_b: "Podaj treść odpowiedzi #2",
                                odp_c: "Podaj treść odpowiedzi #3",
                                odp_d: "Podaj treść odpowiedzi #4",
                                poprawna: "Podaj poprawną odpowiedź ...",
                                link: {
                                    required: "Podaj link do źródła (np. wikipedia.org, imdb.com)",
                                    url: "To nie jest poprawny adres (http://)",
                                    maxlength: "Link jest zbyt długi, max. 255 znaków"
                                }
                            }
                        }).form();
                        
                        if ($("#frmQuestionAdd").valid()) {
                        
                            // alert("Form is valid");
                            $("#frmQuestionAdd>fieldset").hide(); // hide form
                            $("#frmQuestionAdd #loaderContainer").show(); // show image ajax-loader.gif
                            // $("#frmQuestionAdd").dialog("close");
                            
                            $.ajax({
                            
                                type: "POST",
                                url: wszechwiedzacy.site_url + "includes/add_question.php",
                                data: question,
                                dataType: "json",
                                success: function(data){
                                    // wszechwiedzacy.admin.addRow(data.id, data.runda,
                                    // data.kategoria, data.tresc); // adds row to table
                                    // alert(data.success);
                                    // $("#frmQuestionAdd").dialog("close");
                                    window.location.reload(true);
                                    /*
                                     * $('#pytaniaAdmin > tbody:last').append("<tr id=\"" + data.id + "\" " + trClass + "><td name=\"numer\">" +
                                     * (numRows + 1) + "</td><td title=\"" + data.runda + "\" name=\"runda\">" +
                                     * data.runda + "</td><td class=\"tla\" name=\"kategoria\" title=\""+data.kategoria+"\">" +
                                     * data.kategoria + "</td><td class=\"tla\" name=\"tresc\">" +
                                     * data.tresc + "</td><td class=\"edit\"><a id=\"" +
                                     * data.id + "\" href=\"#\" class=\"edit\"><span
                                     * class=\"edit\"></span></a></td><td class=\"delete\"><a
                                     * id=\"" + data.id + "\" class=\"delete\" href=\"#\"><span
                                     * class=\"delete\"></span></a></td></tr>").fadeIn(1000);
                                     * var numRows = $("tbody tr").length; var trClass = "";
                                     * if(numRows % 2 != 0) { trClass = "class =\"odd\"";}
                                     * setEditLinks(); setDeleteLinks();
                                     */
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown){
                                    alert("ajax call to add_question have failed, reason: " + textStatus);
                                }
                                
                            });// end ajax
                        }
                        
                    },
                    Wyczyść: function(){
                    
                        // this function fires when close is pressed, resets warnings and
                        // clears input
                        var validator = $("#frmQuestionAdd").validate();
                        validator.resetForm();
                        $("input:filled").val("");
                        $("textarea:filled").val("");
                        $("select").val("");
                        
                    } // end of wyczysc
                } // end of buttons
            }); // end of frmQuestionAdd
            // QUESTION EDIT DIALOG
            $("#frmQuestionEdit").dialog({
                autoOpen: false,
                modal: true,
                resizable: false,
                draggable: false,
                title: 'Edytuj pytanie',
                width: 660,
                beforeclose: function(){
                    // this function fires when close is pressed, resets warnings and
                    // clears input
                    var validator = $("#frmQuestionEdit").validate();
                    validator.resetForm();
                    $("input:filled").val("");
                    $("textarea:filled").val("");
                    $("select").val("");
                },
                buttons: {
                    Aktualizuj: function(e){
                        e.preventDefault();
                        var runda = $("#runda_edit").val();
                        var autor = $("#frmQuestionEdit").attr("name");
                        var kategoria = $("#kategoria_edit").val();
                        var tresc = $("#tresc_edit").val();
                        var odp_a = $("#odp_a_edit").val();
                        var odp_b = $("#odp_b_edit").val();
                        var odp_c = $("#odp_c_edit").val();
                        var odp_d = $("#odp_d_edit").val();
                        var poprawna = "";
                        if ($("#poprawna_edit").val() == 1) {
                            poprawna = $("#odp_a_edit").val();
                        }
                        else 
                            if ($("#poprawna_edit").val() == 2) {
                                poprawna = $("#odp_b_edit").val();
                            }
                            else 
                                if ($("#poprawna_edit").val() == 3) {
                                    poprawna = $("#odp_c_edit").val();
                                }
                                else {
                                    poprawna = $("#odp_d_edit").val();
                                }
                        var link = $("#link_edit").val();
                        var question = "id=" + id + "&runda=" + runda + "&autor=" + autor + "&kategoria=" + kategoria + "&tresc=" + tresc + "&odp_a=" + odp_a + "&odp_b=" + odp_b + "&odp_c=" + odp_c + "&odp_d=" + odp_d + "&poprawna=" + poprawna + "&link=" + link;
                        // alert(question);
                        var validator = $("#frmQuestionEdit").validate({
                            rules: {
                                runda_edit: "required",
                                kategoria_edit: "required",
                                tresc_edit: {
                                    required: true,
                                    minlength: 10,
                                    maxlength: 255
                                },
                                odp_a_edit: "required",
                                odp_b_edit: "required",
                                odp_c_edit: "required",
                                odp_d_edit: "required",
                                poprawna_edit: "required",
                                link_edit: {
                                    required: true,
                                    url: true,
                                    maxlength: 255
                                }
                            },
                            messages: {
                                runda: "Wybierz rundę",
                                kategoria: "Podaj kategorię",
                                tresc: {
                                    required: "Podaj treść pytania",
                                    minlength: "Pytanie jest za krótkie, min. 10 znaków",
                                    maxlength: "Pytanie jest za długie, max. 255 znaków"
                                },
                                odp_a: "Podaj treść odpowiedzi #1",
                                odp_b: "Podaj treść odpowiedzi #2",
                                odp_c: "Podaj treść odpowiedzi #3",
                                odp_d: "Podaj treść odpowiedzi #4",
                                poprawna: "Podaj poprawną odpowiedź ...",
                                link_edit: {
                                    required: "Podaj link do źródła (np. wikipedia.org, imdb.com)",
                                    url: "To nie jest poprawny adres (http://)",
                                    maxlength: "Link jest zbyt długi, max. 255 znaków"
                                }
                            }
                        }).form();
                        // alert(question);
                        
                        if ($("#frmQuestionEdit").valid()) {
                            // alert("Form is valid");
                            $("#frmQuestionEdit>fieldset").hide(); // hide form
                            $("#frmQuestionEdit #loaderContainer").show(); // show image ajax-loader.gif
                            $.ajax({
                            
                                type: "POST",
                                url: wszechwiedzacy.site_url + "includes/edit_question.php",
                                data: question,
                                dataType: "json",
                                success: function(data){
                                    window.location.reload(true);
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown){
                                    alert("ajax call to edytuj_pytanie have failed, reason: " + textStatus);
                                }
                                
                            });// end ajax
                        } // end of form valid
                    }, // end of aktualizuj button
                    Wyczyść: function(){
                        // this function fires when close is pressed, resets warnings and clears input
                        var validator = $("#frmQuestionEdit").validate();
                        validator.resetForm();
                        $("input:filled").val("");
                        $("textarea:filled").val("");
                        $("select").val("");
                    }
                    
                } // end of buttons
            }); // end of frmQuestionEdit dialog
            // QUESTION DELETE DIALOG
            $("#question_delete").dialog({
            
                autoOpen: false,
                open: wszechwiedzacy.changeFocus,
                modal: true,
                resizable: false,
                draggable: false,
                height: 185,
                width: 400,
                title: 'Usunięcie pytania',
                buttons: {
                    Tak: function(){
                        // sending to PHP the id of the question that will be deleted it is set by clicking the delete icon
                        wszechwiedzacy.admin.oTable.fnDeleteRow(wszechwiedzacy.admin.trNumber); // DataTable function to remove row
                        // $("#question_delete").dialog("close");
                        // alert(wszechwiedzacy.admin.id);
                        
                        $.ajax({
                        
                            type: "POST",
                            url: wszechwiedzacy.site_url + "includes/delete_question.php",
                            data: "id=" + wszechwiedzacy.admin.id,
                            dataType: "json",
                            success: function(data){
                                $("#question_delete").dialog("close");
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown){
                                alert("ajax call to delete_question have failed, reason: " + textStatus);
                            }
                            
                        }); // end of ajax
                    },
                    Nie: function(){
                        $(this).dialog("close");
                    }
                    
                } // end of buttons
            }); // end of question delete dialog
            // initialize all edit and delete links
            wszechwiedzacy.admin.setEditLinks();
            wszechwiedzacy.admin.setDeleteLinks();
            
            $("#newQuestion").click(function(e){
                e.preventDefault();
                $("#frmQuestionAdd").dialog("open");
            });
            
            // selecting from list fills the input field
            $("#frmQuestionAdd #kategoria_select").change(function(){
                $("#frmQuestionAdd #kategoria").val($(this).val());
            });
            
            $("#frmQuestionEdit #kategoria_select_edit").change(function(){
                $("#frmQuestionEdit #kategoria_edit").val($(this).val());
            });
            
            $("#pytaniaAdmin tbody tr").click(function(){
                /* Get the position of the current data from the node */
                wszechwiedzacy.admin.trNumber = wszechwiedzacy.admin.oTable.fnGetPosition(this);
                
                /* Get the data array for this row */
                // var aData = oTable.fnGetData( aPos[0] );
            
                // wszechwiedzacy.admin.oTable.fnDeleteRow( trNumber,
                // function(){alert("tr #: "+trNumber);} );
            
                // wszechwiedzacy.admin.oTable.fnFilter('', 2); // string and optional
                // column num
                // oTable.fnDraw(false);
                /* Update the data array and return the value */
                // aData[ aPos[1] ] = 'clicked';
                // this.innerHTML = 'clicked';
            });
        },
        addRow: function(id, runda, kategoria, tresc){
            wszechwiedzacy.admin.oTable.fnAddData(["<td name=\"numer\">" + id + "</td>", "<td name=\"runda\">" + runda + "</td>", "<td class=\"tla\" name=\"" + kategoria + "\">" + kategoria + "</td>", "<td class=\"tla\" name=\"" + tresc + "\">" + tresc + "</td>", "<td class=\"edit\"><a id=\"" + id + "\" class=\"edit\" href=\"\"><span class=\"edit\" title=\"Edytuj pytanie\"></span></a></td>", "<td class=\"delete\"><a id=\"" + id + "\" class=\"delete\" href=\"\"><span class=\"delete\" title=\"Skasuj pytanie\"></span></a></td>"]);
        }, // end of addRow
        setEditLinks: function(){
            // this function selects all links with class="edit" and assigns click function to edit
            // when we click AJAX gets information from database and fills the fields in #frmQuestionEdit
            $("a.edit").each(function(){
            
                $(this).click(function(e){
                
                    e.preventDefault();
                    wszechwiedzacy.admin.id = $(this).attr("id");
                    var value = "id=" + wszechwiedzacy.admin.id;
                    
                    $.ajax({
                    
                        type: "POST",
                        url: wszechwiedzacy.site_url + "includes/get_question.php",
                        data: value,
                        dataType: "json",
                        success: function(data){
                        
                            // alert( "odp_a: " + data.odp_a+" odp_b: " + data.odp_b+"
                            // odp_c: " + data.odp_c+" odp_d: " + data.odp_d+" poprawna:
                            // "+data.poprawna);
                            $("#frmQuestionEdit").dialog("open");
                            $("#frmQuestionEdit #runda_edit").val(data.runda);
                            $("#frmQuestionEdit #kategoria_edit").val(data.kategoria);
                            $("#frmQuestionEdit #tresc_edit").val(data.tresc);
                            $("#frmQuestionEdit #odp_a_edit").val(data.odp_a);
                            $("#frmQuestionEdit #odp_b_edit").val(data.odp_b);
                            $("#frmQuestionEdit #odp_c_edit").val(data.odp_c);
                            $("#frmQuestionEdit #odp_d_edit").val(data.odp_d);
                            var poprawna = data.poprawna;
                            
                            switch (poprawna) {
                            
                                case data.odp_a:
                                    $("#frmQuestionEdit #poprawna_edit").val(1);
                                    break;
                                case data.odp_b:
                                    $("#frmQuestionEdit #poprawna_edit").val(2);
                                    break;
                                case data.odp_c:
                                    $("#frmQuestionEdit #poprawna_edit").val(3);
                                    break;
                                case data.odp_d:
                                    $("#frmQuestionEdit #poprawna_edit").val(4);
                                    break;
                                default:
                                    $("#frmQuestionEdit #poprawna_edit").val("");
                                    break;
                                    
                            }
                            
                            $("#frmQuestionEdit #link_edit").val(data.link);
                            
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                            alert("ajax call to get_question have failed, reason: " + textStatus);
                        }
                        
                    }); // end ajax
                }); // end of $(this).click function
            }); // end of a.edit.each function
        }, // end of setEditLinks
        setDeleteLinks: function(){
        
            // this function selects all links with class="delete" and assigns click function to delete them
            // when we click we assign the id to know which question is to be deleted and open confirmation dialog
            $("a.delete").each(function(){
            
                $(this).click(function(e){
                
                    e.preventDefault();
                    wszechwiedzacy.admin.id = $(this).attr("id");
                    // alert("Do you really want to delete the question #" + id +"?");
                    $("#question_delete").dialog("open");
                    
                });
                
            }); // end of click function
        }, // end of setDeleteLinks
        pytania: function(){
            alert("empty pytania");
        } // end of admin.pytania
    }, // end of wszechwiedzacy.admin
    kontakt: {
    
        init: function(){
        
            //alert("sir, I think we made contact ...");
            $("#frmKontakt").find("#name").focus();
            $("#kontakt_submit").click(function(e){
            
                e.preventDefault();
                var validator = $("#frmKontakt").validate({
                
                    // highlight: function(element, errorClass) {
                    //    
                    // $(element).fadeOut(250, function() {
                    // $(element).addClass(errorClass).fadeIn(500);
                    // $(element.form).find("label[for=" + element.id +"]:last").addClass(errorClass).fadeIn(250);
                    // });
                    //    
                    // },
                    // unhighlight: function(element, errorClass) {
                    //    
                    // $(element).removeClass(errorClass);
                    // $(element.form).find("label[for=" + element.id +"]:last").removeClass(errorClass);
                    //    
                    // },
                    rules: {
                        name: "required",
                        email: {
                            required: true,
                            email: true
                        },
                        temat: "required",
                        mainMessage: {
                            required: true,
                            rangelength: [15, 255]
                        }
                    },
                    messages: {
                    
                        name: "Podaj swój nick",
                        email: {
                            required: "Podaj swój  adres email",
                            email: "To nie jest poprawny adres email"
                        },
                        temat: "Wybierz jeden z tematów",
                        mainMessage: {
                            required: "Co chcesz nam przekazać?",
                            rangelength: "Minimalna ilość znaków 15, max 255."
                        }
                    
                    },
                    success: function(label){
                        label.text("").addClass("success");
                    }
                }).form();
                
                if ($("#frmKontakt").valid()) {
                
                    var name = $("#kontakt_name").val();
                    var email = $("#kontakt_email").val();
                    var subject = $("#temat :selected").text();
                    var msg = $("#mainMessage").val();
                    var rcf = $("#frmKontakt #recaptcha_challenge_field").val();
                    var rrf = $("#frmKontakt #recaptcha_response_field").val();
                    var values = "name=" + name + "&email=" + email + "&temat=" + subject + "&msg=" + msg + "&recaptcha_challenge_field=" + rcf + "&recaptcha_response_field=" + rrf;
                    // alert(values);
                    
                    $.ajax({
                    
                        type: "POST",
                        url: wszechwiedzacy.site_url + "includes/email.php",
                        data: values,
                        dataType: "json",
                        success: function(data){
                        
                            // alert(data.msg);
                            if (data.msg == "valid" && data.recaptcha == "valid") {
                            
                                // when recaptcha is valid and email was sent
                                $(".formWrap").fadeOut(1000, function(){
                                
                                    // this function is run when fadeOut finishes
                                    // alert("Hidden");
                                    $("#mainContent").html("<div class=\"emailConfirmation\"><span class=\"checkmark\"></span><h1>Email został wysłany.</h1><p>Dziękuję za kontakt. Postaram się odpowiedzieć w przeciągu 24h!</p><div class=\"clearfloat\"></div></div>").hide().fadeIn(500, function(){
                                        $(".checkmark").animate({
                                            top: +70
                                        }, 750, "easeOutBounce");
                                    }); // end of animate
                                });
                                
                            }
                            else {
                            
                                // alert("email was not sent! "+data.msg+" | "+data.recaptcha);
                                // $("#recaptcha_msg").show(500);
                                var n = $("#recaptcha_msg").length;
                                if (n != 0) {
                                
                                    $("#recaptcha_msg").css({
                                        "height": "0px"
                                    }).animate({
                                        height: "24px"
                                    }, 500, "easeOutBounce");
                                    
                                }
                                else {
                                
                                    $("div.clear_float:last").after("<div id=\"recaptcha_msg\"><p><span class=\"ui-icon-alert\"></span>Ops! Przepisane wyrazy nie pasują, spróbuj ponownie lub przeładuj obrazek!</p></div>");
                                    $("#recaptcha_msg").css({
                                        "height": "0px"
                                    }).animate({
                                        height: "24px"
                                    }, 750, "easeOutBounce");
                                    
                                }
                                
                            }
                            
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown){
                            alert("błąd podczas przesyłania wiadomości: " + textStatus);
                        } // end of error
                    }); // end of ajax
                } // end of #frmKontakt valid
            }).hover(hoverO, hoverT).mouseup(mUp).mousedown(mDown);
            
        } // end of kontakt.init()
    }, // end of wszechwiedzacy.kontakt
    ranking: {
    
        init: function(){
            // alert("this is ranking page");
            // $("tr:contains('agn0stic')&tr:contains(11)").expose({api: true,
            // onBeforeClose: function(e) {
            // //e.preventDefault();
            // //alert("Do you want to quit?");
            // //$("#quit").dialog("open");
            // }
            // }).load();
            // $("tr").each(function(){
            // $(this).click(function(){
            // //alert("tr");
            // $(this).expose({api: true}).load();
            // });
            // });
            // $("tr:contains('agn0stic')").expose({api: true, onBeforeClose:
            // function(e) {
            // //e.preventDefault();
            // //alert("Do you want to quit?");
            // //$("#quit").dialog("open");
            // }
            // }).load();
            // $("#footer").click(function() {
            // $.scrollTo("tr[title='zigismund']", 2000);
            // });
        } // end of ranking.init()
    }, // end of ranking
    twojaStrona: {
    
        init: function(){
        
            $("#edytuj_profil").click(function(e){
            
                e.preventDefault();
                $("#edit_profile").dialog("open");
                
            });
            
            $("#edit_profile").dialog({
                autoOpen: false,
                draggable: false,
                resizable: false,
                bgiframe: true,
                modal: true,
                width: 430,
                title: 'Edycja profilu',
                beforeclose: function(){
                
                    // var validator = $("#profile_form").validate();
                    // validator.resetForm();
                    // $("input:filled").val("").removeClass("valid");
                
                },
                buttons: {
                
                    Aktualizuj: function(){
                    
                        var wiek = $("#wiek:checked");
                        var sex = $("input:radio:checked");
                        var newsletter = $("#newsletter:checked");
                        var passwords = $("input:password");
                        var collected = "username=" + $("#userName").val() + "&wiek=" + wiek.val() + "&sex=" + sex.val() + "&city=" + $("#city").val() + "&degree=" + $("#degree").val() + "&newsletter=" + newsletter.val() + "&old_pass=" + passwords.eq(0).val() + "&new_pass=" + passwords.eq(1).val() + "&new_pass2=" + passwords.eq(2).val();
                        // alert(collected);
                        
                        // if password fields are not empty we run validator else we submit
                        // query via ajax
                        if (passwords.val() != "") {
                        
                            var validator = $("#profile_form").validate({
                            
                                rules: {
                                
                                    old_pass: {
                                    
                                        required: true,
                                        rangelength: [4, 14]
                                    
                                    },
                                    new_pass: {
                                    
                                        required: true,
                                        rangelength: [4, 14]
                                    
                                    },
                                    new_pass2: {
                                    
                                        required: true,
                                        equalTo: '#new_pass'
                                    
                                    },
                                
                                },
                                messages: {
                                
                                    old_pass: {
                                    
                                        required: "Proszę podać stare hasło.",
                                        rangelength: "Minimalna ilość znaków 4, max 14."
                                    
                                    },
                                    new_pass: {
                                    
                                        required: "Proszę podać nowe hasło.",
                                        rangelength: "Minimalna ilość znaków 4, max 14."
                                    
                                    },
                                    new_pass2: {
                                    
                                        required: "Proszę potwierdzić nowe hasło.",
                                        equalTo: "Hasła nie są identyczne."
                                    
                                    },
                                
                                }
                            
                            }).form();
                            
                        }
                        
                        if ($("#profile_form").valid()) {
                        
                            $("#edit_profile .ajaxLoader").show(); // ajax-loader.gif
                            $("#profile_form").hide();
                            
                            $.ajax({
                            
                                type: "POST",
                                data: collected,
                                url: wszechwiedzacy.site_url + "includes/profil.php",
                                dataType: "json",
                                success: function(data){
                                
                                    if (data.msg == "ok") {
                                    
                                        window.location.reload();
                                        
                                    }
                                    else {
                                    
                                        // hides animation git and shows form
                                        $("#edit_profile .ajaxLoader").hide();
                                        $("#profile_form").show();
                                        
                                        if (data.msg == "wrong old password") {
                                        
                                            // alert(data.msg);
                                            var validator = $("#profile_form").validate();
                                            validator.showErrors({
                                                "old_pass": "Obecne hasło jest niepoprawne!"
                                            });
                                            
                                        }
                                        else 
                                            if (data.msg == "old equals new") {
                                            
                                                // alert(data.msg);
                                                var validator = $("#profile_form").validate();
                                                validator.showErrors({
                                                    "new_pass": "To jest stare hasło!"
                                                });
                                                
                                            }
                                            else {
                                            
                                                alert(data.msg);
                                            // window.location.reload();
                                            
                                            }
                                        
                                    }
                                    
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown){
                                    alert("Błąd podczas aktualizacji danych w profilu: " + textStatus);
                                }
                                
                            }); // end of ajax
                        }
                        
                    }, // end of aktualizuj button
                    Anuluj: function(){
                        $(this).dialog("close");
                    }
                    
                } // end of buttons
            }); // end of edit_profile dialog
            $link_sfs = $("#show_full_stats");
            $link_sfs.click(function(){
            
                $("#full_stats").toggle(1000, function(){
                    ($link_sfs.text() == "pokaż dodatkowe statystyki") ? $link_sfs.text("ukryj dodatkowe statystyki") : $link_sfs.text("pokaż dodatkowe statystyki");
                });
                
            });
            
        }
        
    }, // end of twojaStrona
    changeFocus: function(){
    
        /**
         * changes focus from the default to first button work on jQuery UI dialog
         * component for now it only blurs the default button so that nothing is
         * selected on open
         */
        $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
        $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').focus();
        $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur();
        
    }, // end of changeFocus
    time: {
    
        newInt: {}, // interval for counting time
        sms: 20000, // starting value for miliseconds
        ms: {}, // miliseconds used for counting
        pts: {}, // points and also the percentage of progressbar
        init: function(){
        
            wszechwiedzacy.time.ms = wszechwiedzacy.time.sms; // every new question starts with the default time that is set by sms
            wszechwiedzacy.time.newInt = setInterval("wszechwiedzacy.time.countTime()", 100); // countTime function gets called every 100ms
        },
        countTime: function(){
        
            /**
             * this function gets called every 100ms (as set by setInterval) and
             * deducts 10ms from var ms when == 0 clears the interval and
             * triggers the click on the submit button else it deducts 10ms and
             * counts points (which are also used to determine the length of
             * progressbar)
             */
            if (wszechwiedzacy.time.ms <= 0) {
            
                clearInterval(wszechwiedzacy.time.newInt);
                wszechwiedzacy.time.ms = 0;
                // alert("Koniec!");
                $("#submitButton").trigger("click");
                
            }
            else {
            
                wszechwiedzacy.time.ms -= 10;
                wszechwiedzacy.time.pts = Math.round((wszechwiedzacy.time.ms * 100) / wszechwiedzacy.time.sms);
                $("#progressbar").progressbar('value', wszechwiedzacy.time.pts);
                
            }
            
        },
        showLabels: function(){
        
            // alert("callback");
            if (wszechwiedzacy.gra.cdnum < 4) {
            
                $("p.answer:eq(" + wszechwiedzacy.gra.cdnum + ")").fadeIn(300);
                wszechwiedzacy.gra.cdnum++;
                
                if (wszechwiedzacy.gra.cdnum < 4) {
                
                    wszechwiedzacy.gra.countdown = setTimeout("wszechwiedzacy.time.showLabels()", 300);
                    
                }
                
            }
            
            if (wszechwiedzacy.gra.cdnum == 4) {
            
                clearTimeout(wszechwiedzacy.gra.countdown);
                wszechwiedzacy.time.init();
                
            }
            
        } // / showLabels
    } //time
}; // end of wszechwiedzacy library
