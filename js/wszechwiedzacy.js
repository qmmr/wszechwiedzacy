/*
	wszechwiedzacy.pl main Object
	ver beta 0.8
	site uri: http://wszechwiedzacy.pl
	author Marcin Kumorek
	author's uri: http://qmmr.pl
*/
var wszechwiedzacy = {
	// placeholder for the #mainContent
	mc: $("#mainContent"),
    // placeholder to store session points
    session_pts: 0,
    // placeholder for site URL
    site_url: {},
    // form placeholder
    mForm: {},    
    // button hover and mouse down/up states
	mHover: function(){
    $(this).toggleClass("ui-state-hover");
  },
	mDownUp: function() {
    $(this).toggleClass("ui-state-active");
  },
	// initialized on every page
    init: function () {
        // checks if we're developing on localhost or live online
        wszechwiedzacy.site_url = "http://" + (window.location.hostname == 'localhost') ? "localhost/wszechwiedzacy/" : "wszechwiedzacy.pl";
        // game if off -> for the login purpose
        wszechwiedzacy.gra.gameStatus = wszechwiedzacy.mc.data('game','off');
        // LOGIN / REGISTER
        $("#login, button[name=login], a[name=login]").live('click',function(e){
            e.preventDefault();
            $("#login_dialog").dialog("open");
            $(".dialogLink").show();
        });
        
        $("#register, #reg2, button[name=register], a[name=register]").live('click',function(e){
            e.preventDefault();
            $("#register_dialog").dialog("open");            
        });
        
        // link to forgotDialog (recover forgotten nick or password)
        $("#forgot").click(function() {
            
            $("#login_dialog").dialog("close");
            $("#forgot_dialog").dialog("open");
            
        });
        
        // changeDialog (from login to register)
        $("#changeDialog").click(function(){
            
            $("#login_dialog").dialog("close");
            $("#register_dialog").dialog("open");
            
        });
        // end changeDialog
        
        // LOGIN DIALOG
        var ld = $("#login_dialog").dialog({
            
            open: function(e, ui){                
                wszechwiedzacy.mForm = $("#login_form");
                wszechwiedzacy.mForm.find("#login_email").focus();
                $(this).keyup(function(e){
					e.preventDefault();
                    var f = wszechwiedzacy.mForm.find("input:first").val();
                    var s = wszechwiedzacy.mForm.find("input:last").val();
                    if (e.keyCode == 13 && f != "" && s != "") {                        
						wszechwiedzacy.mForm.closest('.ui-dialog').find(".ui-dialog-buttonpane button:eq(0)").trigger("click");
                    }                    
                });                
            },
            autoOpen: false,
            draggable: false,
            resizable: false,
            closeOnEscape: false,
            modal: true,
            zIndex: 9999,
			width: 440,
            title: 'Logowanie',
            beforeclose: function(){
                var validator = $("#login_form").validate();
                validator.resetForm();
                $("input:filled").val("").toggleClass("valid");
            },
            buttons: {
                
                Loguj: function(){
                    
                    $("#login_form").validate({
                        
                        rules: {
                            email: {
                                required: true,
                                email: true
                            },
                            password: "required"
                        },
                        messages: {
                            email: {
                                required: "Email jest wymagany.",
                                email: "To nie jest poprawny adres email."
                            },
                            password: "Podaj hasło."
                        }
                    }).form();
                    // if the form is valid we can send the input to php
                    
                    if(wszechwiedzacy.mForm.valid()){
                        
                        $("#login_form").hide();
                        $("#login_dialog .ajaxLoader").show();
                        $(".dialogLink").hide();
                        
                        $.ajax({
                            type: "POST",
                            data: "email=" + $("#login_email").val() + "&password=" + $("#login_password").val(),
                            url: wszechwiedzacy.site_url + "includes/logowanie.php",
                            dataType: "json",
                            success: function(data){                                
                                
                                if (data.logged == "true"){
								
									// console.log('gameStatus is '+wszechwiedzacy.gra.gameStatus.data('game'));
									
									// updates the login panel
									$.get(wszechwiedzacy.site_url+"includes/reg_head.php",function(data){
									   $("#log_head").hide().replaceWith(data);
									   $("#reg_head").hide().fadeIn(500); 
									});
									
									if(wszechwiedzacy.gra.gameStatus.data('game') == 'on'){
									
										// when player wants to log in during the game
										$("#advert").slideToggle(250);
										$(".floatContainer").css({marginTop:'35px'});
										ld.dialog('close');
										
									} else {
									
										// when player wants to log in during the end game										
										ld.dialog('close');
										var $r = $("#rezultat").hide();
										wszechwiedzacy.gra.lc.show();
										$.get(wszechwiedzacy.site_url+"includes/end_game_login.php",function(data){
										
											wszechwiedzacy.gra.lc.hide(0, function(){
											
												$r.html(data).fadeIn(1000);
												
											});
											
										});
										
									}
                                    
                                } else {
                                    
                                    $("#login_dialog .ajaxLoader").hide();
                                    $("#login_form").show();
                                    
                                    if (data.email == "inactive"){
                                        
                                        var validator = $("#login_form").validate();
                                        validator.showErrors({"email":"Konto nie zostało aktywowane!"});
                                        
                                    } else if (data.email == "invalid"){
                                        
                                        var validator = $("#login_form").validate();
                                        validator.showErrors({"email":"Nie ma takiego użytkownika!"});
                                        
                                    } else {
                                        
                                        var validator = $("#login_form").validate();
                                        validator.showErrors({"password":"Hasło nie pasuje do użytkownika!"});
                                        
                                    }
                                    
                                } // end if/else valid
                                
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("błąd podczas logowania: " + textStatus);}
                            
                        }); // end ajax
                        
                    } // end of wszechwiedzacy.mForm.valid()
                    
                } // end of loguj function
                
            } // end of buttons
            
        });
        // end of login dialog
        
        // FORGOT DIALOG
        $("#forgot_dialog").dialog({
            
            open: function (event, ui) {
                
                $(this).find("#email").focus();
                // alert( $(this).attr("id") );
                $(this).keyup(function (e) {
                    
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
            beforeclose: function () {
                
                var validator = $("#forgot_form").validate();
                validator.resetForm();
                $("input:filled").val("");
                
            },
            width: 440,
            buttons: {
                
                Odzyskaj: function () {
                    
                    $("#forgot_form").validate({
                        
                        rules: {
                            
                            email: {
                                required: true,
                                email: true
                            }
                            
                        },
                        messages: {                            
                            email: {
                                required: "Podaj adres email",
                                email: "Niepoprawny adres email"
                            }                            
                        }
                        
                    }).form();
                    
                    if ($("#forgot_form").valid()) {
                        
                        // hide form and show image ajax-loader.gif
                        $("#forgot_form").hide();
                        $("#forgot_dialog .ajaxLoader").show();
                        var recover = "email=" + $("#lost_email").val();
                        
                        $.ajax({
                            
                            type: "POST",
                            data: recover,
                            url: wszechwiedzacy.site_url + "includes/recover_email.php",
                            dataType: "json",
                            success: function (data) {
                                
                                var email = data.email;
                                //alert(email);
                                if (email == "sent") {
                                    
                                    //alert("nowe hasło zostało wysłane na zarejestrowany adres email!");
                                    $("#forgot_dialog").dialog('close');
                                    
                                } else {
                                    
                                    $("#forgot_dialog .ajaxLoader").hide(); // hide ajax-loader.gif
                                    $("#forgot_form").show(); // showing form again
                                    // do sth when php don't find email
                                    if (email == "invalid") {
                                        var validator = $("#forgot_form").validate();
                                        validator.showErrors({"email": "To nie jest poprawny adres email!"});
                                    } else if (email == "nr") {
                                        var validator = $("#forgot_form").validate();
                                        validator.showErrors({"email": "Należy podać adres email!"});
                                    } else if (email == "de") {
                                        var validator = $("#forgot_form").validate();
                                        validator.showErrors({"email": "Nie ma takiego adresu w naszej bazie!"});
                                    } else if (email == "failed") {
                                        var validator = $("#forgot_form").validate();
                                        validator.showErrors({"email": "Oops! Strajk na poczcie, email nie został wysłany!"});
                                    } else {
                                        alert("Taki błąd nie powinien się zdarzyć: " + email);
                                    }
                                    
                                } // end of email validation
                                $("#forgot_dialog .ajaxLoader").hide(); // hide ajax-loader.gif
                                $("#forgot_form").show(); // showing form again
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {alert("ajax call to recover_email failed, reason: " + textStatus);}
                            
                        }); // end ajax
                        
                    } // end of #forgot_form
                    
                } // end of odzyskaj button
                
            } // end of buttons
            
        });
        // end of forgot dialog
        
        // REGISTER DIALOG
        $("#register_dialog").dialog({
            open: function(e, ui){                
                $("#reg_username").focus();
            },
            autoOpen: false,
            draggable: false,
            resizable: false,
            modal: true,
            zIndex: 9999,
            title: 'Rejestracja',
            beforeclose: function(){
                var validator = $("#register_form").validate();
                validator.resetForm();
                $("input:filled").val("").removeClass("valid");
            },
            width: 440,
            buttons: {
                Rejestruj: function(){
                    $("#register_form").validate({
                        rules: {                            
                            reg_username: {
                                required: true,
                                rangelength: [3, 14]
                            },
                            reg_password: {
                                required: true,
                                rangelength: [4, 14]
                            },
                            email: {
                                required: true,
                                email: true
                            }                            
                        },                        
                        messages: {                            
                            reg_username: {
                                required: "Podaj swój nick",
                                rangelength: "Minimalna ilość znaków 3, max 14"
                            },
                            reg_password: {
                                required: "Podaj hasło",
                                rangelength: "Minimalna ilość znaków 4, max 14"
                            },
                            email: {
                                required: "Email jest wymagany",
                                email: "Niepoprawny adres email"
                            }
                        }
                    }).form();
					
                    if ($("#register_form").valid()){
                        
                        $("#register_dialog .ajaxLoader").show();
                        $("#register_form").hide();
                        var usr = $("#reg_username").val();
                        var pwd = $("#reg_password").val();
                        var email = $("#reg_email").val();						
                        var data = "username=" + usr + "&password=" + pwd + "&email=" + email;
                        // console.log(data);						
						
                        $.ajax({
                            type: "POST",
                            data: data,
                            url: wszechwiedzacy.site_url + "includes/rejestracja.php",
                            dataType: "json",
                            success: function(data){
                                
                                // console.log(data.user+" | "+data.password+" | "+data.email+" | "+data.mysql);
                                $("#register_dialog .ajaxLoader").hide();
                                $("#register_form").show();
                                if (data.user == "valid" && data.password == "valid" && data.email == "sent" && data.mysql == "valid"){
									
									if(wszechwiedzacy.session_pts != 0){
									
										$("#register_dialog").dialog('close');
										$("#log").next("h4").hide().html("<span>Na adres "+email+" zostal wyslany email z kluczem aktywacyjnym.<br />Prosze aktywowac konto w ciagu nastepnych 24h w przeciwnym razie zostanie usuniete.<br />Teraz mozesz sie zalogowac uzywajac adresu email i hasla</h4>").fadeIn(1500).next().remove();
									
									} else {
										
										$("#register_dialog").dialog('close');
										$("#header").after("<div class=\"info_block\"><p>Aby dokończyć proces aktywacji, przeczytaj wiadomość, wyslana na adres <a href=\"mailto:" + email + "\">" + email + "</a></p><a name=\"close\" href=\"#\"></a></div>");
										$(".info_block").slideDown(1000);
										$("a[name=close]").click(function(){
											clearTimeout(cd);
											$(".info_block").slideToggle(500);
										});
										var cd = setTimeout(function () {
											$(".info_block").slideToggle(1000);
										}, 10000);
										
									}                                    
                                    
                                } else {
                                    
                                    $("#register_dialog .ajaxLoader").hide();
                                    $("#register_form").show();
                                    var validator = $("#register_form").validate();
                                    (data.user == "taken") ? validator.showErrors({"reg_username": "Ten nick jest już zarejestrowany!"}) : false;                                    
                                    (data.email == "taken") ? validator.showErrors({"email": "Adres email jest już zarejestrowany!"}) : false;
                                    (data.email == "error") ? alert("Oops! Email z kluczem aktywacyjnym nie został wysłany. Aby aktywować konto wyślij email do kontakt@wszechwiedzacy.pl") : false;
                                    (data.mysql != "valid") ? alert("Oops! Błąd MySQL, spróbuj jeszcze raz lub napisz do nas na adres kontakt@wszechwiedzacy.pl") : false;
                                    
                                } // end of data validation
                                
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("Błąd podczas rejestracji: " + textStatus);}
                            
                        }); // end of ajax call
                    } // end of if(client_valid)
                } // end of zarejestruj function
            } // end of buttons
        });
        // end of register dialog
        
        // LOGOUT via AJAX
        $("#logout").live('click',function(e){
            e.preventDefault();            
            $.ajax({
                type: "POST",
                url: wszechwiedzacy.site_url + "includes/logowanie.php",
                success: function(){
					// console.log("Logged out");
                    window.location = wszechwiedzacy.site_url;
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("błąd podczas wylogowania: "+textStatus);}
            });            
        }); // end #logout.click
        
    },
    // end of wszechwiedzacy.init()
    
    gra: {        
        // var countdown number
        cdnum: 3,
        // var to store the setTimeout method for clearing timeout
        countdown: {},
        // mainContent expose container
        mcExpose: {},
		// main view
		mv: {},
		// current view
		cv: {},
		// gamestatus
		gameStatus: {},
		// loader gif animation div
		lc: {},
		// fadeInOut
		fadeInOut: function(dir,mv,cv) {
			if(dir == "in"){
				mv.fadeOut(250, function(){cv.fadeIn(500);});
			} else {
				cv.fadeOut(250, function(){mv.fadeIn(500);});
			}
		},
        // initializes all stuff
        init: function () {            
            
			// game starts
			wszechwiedzacy.gra.gameStatus = wszechwiedzacy.mc.data('game','on');
			// store $("#loaderContainer")
			wszechwiedzacy.gra.lc = $("#loaderContainer");
			// assign main view at the beggining
			wszechwiedzacy.gra.mv = $("#startWrap");
            location.href = "#crumb"; // centers the game area
            /**
             * EXPOSE THE GAME AREA when we go to gra.php it will dimm out all
             * but the #mainContent just to make sure the player focuses on the
             * questions
             */
            wszechwiedzacy.gra.mcExpose = wszechwiedzacy.mc.expose({
			
                color: '#2A6731',
                opacity: 0.75,
                loadSpeed: 250,
                api: true,
                onBeforeClose: function(){qd.dialog("open");}
				
            }).load(); // loads expose tool
            
            // QUIT DIALOG
            var qd = $("#quit_dialog").dialog({
                open: wszechwiedzacy.changeFocus,
                autoOpen: false,
                modal: true,
                resizable: false,
                closeOnEscape: false,
				dialogClass: 'dialogClose',
                zIndex: 9999,
                width: 320,
                title: 'Uwaga!',
                buttons: {
                    Tak: function () {
						wszechwiedzacy.gra.gameStatus = wszechwiedzacy.mc.data('game','off');
                        window.location.replace(wszechwiedzacy.site_url);
                    },
                    Nie: function () {
                        qd.dialog("close");
                        wszechwiedzacy.gra.mcExpose.expose().load();
                    } // mc.load loads expose tool		     
                } // end of buttons
            }); // end of quit dialog
			
            // captureKeys
            wszechwiedzacy.gra.captureKeys(document.documentElement);
			
			// can be clicked only once
            $("button[name=start]").click(function(){
				wszechwiedzacy.gra.mv.remove();
                wszechwiedzacy.gra.showNextQuestion();
				return false;
			});
			$("#tutEnd").click(function(){
				wszechwiedzacy.gra.cv.remove();
				wszechwiedzacy.gra.showNextQuestion();
				return false;
			});
			
            // set live() buttons
			
			//  hover effect and mouse down/up
			$("button").live('mouseenter mouseleave',function(){
				$(this).toggleClass('ui-state-hover');
			}).live('mousedown mouseup',function(){
				$(this).toggleClass('ui-state-active');
			});			
			$("button[name=back]").live('click',function(e){
                e.preventDefault();
				// console.log("mv -> "+wszechwiedzacy.gra.mv.attr('id')+" cv -> "+wszechwiedzacy.gra.cv.attr('id'));
				wszechwiedzacy.gra.fadeInOut("out",wszechwiedzacy.gra.mv,wszechwiedzacy.gra.cv);
            });
			$("button[name=continue]").live('click',function(e){
				$("#breakWrap").remove();
				wszechwiedzacy.gra.lc;
				wszechwiedzacy.gra.showNextQuestion();
			});
			$("#sts").live('click',function(e){
				wszechwiedzacy.gra.cv = $("#statystyki");
				wszechwiedzacy.gra.fadeInOut("in",wszechwiedzacy.gra.mv,wszechwiedzacy.gra.cv);
			});
			$("#wa").live('click',function(e){
				wszechwiedzacy.gra.cv = $("#wrongAnswers");
				wszechwiedzacy.gra.fadeInOut("in",wszechwiedzacy.gra.mv,wszechwiedzacy.gra.cv);
			});
			$("#show_tut").live('click',function(e){
				wszechwiedzacy.gra.cv = $("#tutorialWrap");
				wszechwiedzacy.gra.fadeInOut("in",wszechwiedzacy.gra.mv,wszechwiedzacy.gra.cv);
			});
			$("#showRank").live('click',function(e){
				alert("szefie, mamy problem ...");
			});
			$("button[name=again]").live('click',function(e){
				$("#rezultat, #endWrap, #statystyki, #wrongAnswers, #tutorialWrap").remove();
				wszechwiedzacy.gra.lc.show();
				$.ajax({
					url: wszechwiedzacy.site_url + "includes/clear_stats.php",
					dataType: "json",
					success: function() {
						wszechwiedzacy.gra.lc.hide();
						wszechwiedzacy.gra.showNextQuestion();
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("stats not cleared via ajax -> " + textStatus);}
				});
			});
			$("#submitBtn").live('click',function(e){
				e.preventDefault();
				// console.log(wszechwiedzacy.mc.data('game'));
				// assigns scored points to hidden input so that it can be passed to php creates vars to store answer and correct answer
				$("#scored").val(wszechwiedzacy.time.pts);
				var odp = $("input[selected]").val();
				// if no answer was chosen we input text
				(odp == undefined) ? odp = "brak odpowiedzi" : false;
				var poprawna = $("input[name=\"poprawna\"]").val();
				var punkty = $("#scored").val();                            
				var odpowiedzi = "group=" + odp + "&poprawna=" + poprawna + "&punkty=" + punkty;
				$("#pytanieWrap").remove();
				// show the animation loading gif
				wszechwiedzacy.gra.lc.show();
				// stops the count down
				clearTimeout(wszechwiedzacy.gra.countdown);
				
				$.ajax({                                
					type: "POST",
					data: odpowiedzi,
					dataType: "json",
					url: wszechwiedzacy.site_url + "includes/odpowiedz.php",
					success: function(data){
						
						switch (data.game_state){
							case "over":                                            
								$.ajax({
									url: wszechwiedzacy.site_url + "includes/wynik.php",
									success: function (data) {
										// sets the game to end so that when you log in the points will be updated -> see login dialog
										wszechwiedzacy.gra.gameStatus.data('game', 'end');
										// appends data to #mainContent
										wszechwiedzacy.mc.append(data);
										// assigns endWrap as the main view
										wszechwiedzacy.gra.mv = $("#endWrap");
										wszechwiedzacy.gra.cv = $("#rezultat").fadeIn(1000);
										// hides the animation gif
										wszechwiedzacy.gra.lc.hide();
									},
									error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("ajax call to wynik.php failed -> " + textStatus);}
									
								});
								break;
								
							case "advance":
								// game advance (we load the summary after finished round)
								$.ajax({
									url: wszechwiedzacy.site_url + "includes/runda.php",
									success: function (data) {
										// appends data to #mainContent
										wszechwiedzacy.mc.append(data);
										$("#breakWrap").fadeIn(1000);
										wszechwiedzacy.gra.lc.hide();
									},
									error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("ajax to runda.php failed -> " + textStatus);}
								});
								break;
							
							default:
								// just another question
								wszechwiedzacy.gra.showNextQuestion();
								break;
							
						} // end of swith(game)
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("ajax to odpowiedz.php failed->" + textStatus);}
				});
			}); // end of #submit
			
        },
        // end of gra.init
        
        captureKeys: function(obj) {            
            $(obj).keyup(function(e){                
                // select radio buttons when 1,2,3,4 is pressed
                switch (e.keyCode) {
                    case 49:
                    case 97:
                        $("label:eq(0)").next().attr("selected", "selected");
                        $("#submitBtn").trigger("click");
                        break;
                    case 50:
                    case 98:
                        $("label:eq(1)").next().attr("selected", "selected");
                        $("#submitBtn").trigger("click");
                        break;
                    case 51:
                    case 99:
                        $("label:eq(2)").next().attr("selected", "selected");
                        $("#submitBtn").trigger("click");
                        break;
                    case 52:
                    case 100:
                        $("label:eq(3)").next().attr("selected", "selected");
                        $("#submitBtn").trigger("click");
                        break;
                    case 13:				
						($("button[name=continue]").length == 1) ? $("button[name=continue]").trigger('click') : false;

                        break;
                    default:
                    break;
                }
                //console.log(e.KeyCode);
            });            
        },
        // end of captureKeys		
        
        setupRadioButtons: function () {
            // checks all input of type radio if they have class radioNormal
            // if true they are removed and radioHidden are added
            var tick = $("#tick");
            var odpowiedziForm = $("#odpowiedzi");
            var labels = $("label", odpowiedziForm);
            //	var radioBtn = $("input:radio", odpowiedziForm);
            // (radioBtn.hasClass("radioNormal")) ? radioBtn.removeClass("radioNormal").addClass("radioHidden") : false;
            // next all labels receive click function that checks if they have class radio_check            			
            labels.hover(                
                function () {
                    $(this).stop(true,true).animate({
                        "backgroundColor": "#e6f6cd",
                        "borderTopColor": "#58742b",
                        "borderRightColor": "#58742b",
                        "borderBottomColor": "#58742b",
                        "borderLeftColor": "#58742b"
                    }, 250, "easeOutSine");
					$(this).find("span").stop(true,true).animate({
						opacity:.35
					}, 250, 'easeOutSine');
                }, function () {
                    $(this).stop(true,true).animate({
                        "backgroundColor": "#FFFFFF",
                        "borderTopColor": "#DFDFDF",
                        "borderRightColor": "#DFDFDF",
                        "borderBottomColor": "#DFDFDF",
                        "borderLeftColor": "#DFDFDF"
                    }, 250, "easeOutSine");
					$(this).find("span").stop(true,true).animate({
						opacity:0
					}, 250, 'easeOutSine');
                }
            ).bind("click", function (e) {
                    // check if any other answer was chosen and deselect it
                    var lc = $(".radio_check", odpowiedziForm);
                    if (lc.length != 0) {
                        lc.toggleClass("radio_check").next().removeAttr("selected");
                    }
                    var thisLabel = $(e.target);
                    var thisInput = thisLabel.next();
                    thisLabel.toggleClass("radio_check").next().attr("selected", "selected");
                    if (thisLabel.hasClass("radio_check")) {
                        thisLabel.next().attr("selected", "selected");
                        thisInput.after(tick);
                        $("#submitBtn").fadeIn(500);
                        tick.css({
                            "top": -10
                        }).stop().animate({
                            "top": 0
                        }, 500, "easeOutBounce");
                    }
            });
        },
        // end of wszechwiedzacy.gra.setupRadioButtons
        
        showNextQuestion: function () {
            wszechwiedzacy.gra.lc.fadeIn(250); // show the animation gif
            clearInterval(wszechwiedzacy.time.newInt);
            
            var url = wszechwiedzacy.site_url + "includes/pytanie.php";
            $.ajax({
                type: "POST",
                url: url,
                success: function (data) {
                    wszechwiedzacy.gra.loadTheQuestion(data);
                },
                error: function () {alert("The ajax call 'showNextQuestion' was a FAILURE!");}
                
            }); // end of ajax call to pytanie.php
        },
        // end of wszechwiedzacy.gra.showNextQuestion
        
        loadTheQuestion: function (data) {
            // appends data to #mainContent
            wszechwiedzacy.mc.append(data);
            // adjust visual style of input radio buttons
            wszechwiedzacy.gra.setupRadioButtons();
            $("#submitBtn").hide();
			var pytanie = $("#tresc > p").hide();
            var odpowiedzi = $("p.answer").hide();
            // next fades in #pytanieWrap and after finishes it starts the counter (callback)
            $("#pytanieWrap").fadeIn(1500, function () {
                pytanie.fadeIn(500, callback);
            });
            // hides the animation gif
            wszechwiedzacy.gra.lc.hide();            
            $("#progressbar").progressbar({value: 100});
            
            function callback() {
                wszechwiedzacy.gra.cdnum = 0;
                wszechwiedzacy.gra.countdown = setTimeout("wszechwiedzacy.time.showLabels()", 1500);                
            }
            // end callback
            
        }
        // end loadTheQuestion
        
    },
    // end of wszechwiedzacy.gra
    
    admin: {
        oTable: {},
        // empty var to store pytania table
        id: {},
        // question id to send to mysql
        trNumber: {},
        // oData row number
        init: function () {
            //alert("admin init");
            
            // initialize all edit and delete links
            wszechwiedzacy.admin.setEditLinks();
            wszechwiedzacy.admin.setDeleteLinks();
            
            // INITIALIZE DATATABLE
            wszechwiedzacy.admin.oTable = $("#pytaniaAdmin").dataTable({
                "bJQueryUI": true,
                "aoColumns": [{
                    "sWidth": "50px"},{
                    "sWidth": "60px"},{
                    "sWidth": "135px"},null, {
                    "sWidth": "55px"},{
                    "sWidth": "50px"},],
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
				dialogClass: 'dQ',
                beforeclose: function () {
                    // this function fires when close is pressed, resets warnings and
                    // clears input
                    var validator = $("#frmQuestionAdd").validate();
                    validator.resetForm();
                    $("input:filled").val("");
                    $("textarea:filled").val("");
                    $("select").val("");
                },
                buttons: {
                    Dodaj: function (e) {
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
                        else if ($("#poprawna").val() == 2) {
                            poprawna = $("#odp_b").val();
                        }
                        else if ($("#poprawna").val() == 3) {
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
                                success: function (data) {
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
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    alert("ajax call to add_question have failed, reason: " + textStatus);
                                }
                            }); // end ajax
                        }
                    },
                    Wyczyść: function () {
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
				dialogClass: 'dQ',
                beforeclose: function () {
                    // this function fires when close is pressed, resets warnings and clears input
                    var validator = $("#frmQuestionEdit").validate();
                    validator.resetForm();
                    $("input:filled").val("");
                    $("textarea:filled").val("");
                    $("select").val("");
                },
                buttons: {
                    Aktualizuj: function (e) {
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
                        var popEdit = $("#poprawna_edit").val();
                        switch(popEdit) {
                            case "1": poprawna = $("#odp_a_edit").val(); break;
                            case "2": poprawna = $("#odp_b_edit").val(); break;
                            case "3": poprawna = $("#odp_c_edit").val(); break;
                            case "4": poprawna = $("#odp_d_edit").val(); break;
                            default: break;
                        }
                        var link = $("#link_edit").val();
                        var question = "id=" + wszechwiedzacy.admin.id + "&runda=" + runda + "&autor=" + autor + "&kategoria=" + kategoria + "&tresc=" + tresc + "&odp_a=" + odp_a + "&odp_b=" + odp_b + "&odp_c=" + odp_c + "&odp_d=" + odp_d + "&poprawna=" + poprawna + "&link=" + link;
                        //alert(question);
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
                                success: function (data) {
                                    window.location.reload(true);
                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    alert("ajax call to edytuj_pytanie have failed, reason: " + textStatus);
                                }
                            }); // end ajax
                        } // end of form valid
                    },
                    // end of aktualizuj button
                    Wyczyść: function () {
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
				dialogClass: 'dQ',
                title: 'Usunięcie pytania',
                buttons: {
                    Tak: function () {
                        // sending to PHP the id of the question that will be deleted it is set by clicking the delete icon
                        wszechwiedzacy.admin.oTable.fnDeleteRow(wszechwiedzacy.admin.trNumber); // DataTable function to remove row
                        // $("#question_delete").dialog("close");
                        // alert(wszechwiedzacy.admin.id);
                        $.ajax({
                            type: "POST",
                            url: wszechwiedzacy.site_url + "includes/delete_question.php",
                            data: "id=" + wszechwiedzacy.admin.id,
                            dataType: "json",
                            success: function (data) {
                                $("#question_delete").dialog("close");
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                alert("ajax call to delete_question have failed, reason: " + textStatus);
                            }
                        }); // end of ajax
                    },
                    Nie: function () {
                        $(this).dialog("close");
                    }
                } // end of buttons
            }); // end of question delete dialog
            
            $("#newQuestion").click(function (e) {
                e.preventDefault();
                $("#frmQuestionAdd").dialog("open");
            });
            // selecting from list fills the input field
            $("#frmQuestionAdd #kategoria_select").change(function () {
                $("#frmQuestionAdd #kategoria").val($(this).val());
            });
            $("#frmQuestionEdit #kategoria_select_edit").change(function () {
                $("#frmQuestionEdit #kategoria_edit").val($(this).val());
            });
            $("#pytaniaAdmin tbody tr").click(function () { /* Get the position of the current data from the node */
                wszechwiedzacy.admin.trNumber = wszechwiedzacy.admin.oTable.fnGetPosition(this); /* Get the data array for this row */
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
		// end init
        addRow: function (id, runda, kategoria, tresc) {
            wszechwiedzacy.admin.oTable.fnAddData(["<td name=\"numer\">" + id + "</td>", "<td name=\"runda\">" + runda + "</td>", "<td class=\"tla\" name=\"" + kategoria + "\">" + kategoria + "</td>", "<td class=\"tla\" name=\"" + tresc + "\">" + tresc + "</td>", "<td class=\"edit\"><a id=\"" + id + "\" class=\"edit\" href=\"\"><span class=\"edit\" title=\"Edytuj pytanie\"></span></a></td>", "<td class=\"delete\"><a id=\"" + id + "\" class=\"delete\" href=\"\"><span class=\"delete\" title=\"Skasuj pytanie\"></span></a></td>"]);
        },
        // end of addRow
        setEditLinks: function () {
            // this function selects all links with class="edit" and assigns click function to edit
            // when we click AJAX gets information from database and fills the fields in #frmQuestionEdit
            $("a.edit").each(function () {
                $(this).click(function (e) {
                    e.preventDefault();
                    wszechwiedzacy.admin.id = $(this).attr("id");
                    //alert(wszechwiedzacy.admin.id);
                    var value = "id=" + wszechwiedzacy.admin.id;
                    $.ajax({
                        type: "POST",
                        url: wszechwiedzacy.site_url + "includes/get_question.php",
                        data: value,
                        dataType: "json",
                        success: function (data) {
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
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert("ajax call to get_question have failed, reason: " + textStatus);
                        }
                    }); // end ajax
                }); // end of $(this).click function
            }); // end of a.edit.each function
        },
        // end of setEditLinks
        setDeleteLinks: function () {
            // this function selects all links with class="delete" and assigns click function to delete them
            // when we click we assign the id to know which question is to be deleted and open confirmation dialog
            $("a.delete").each(function () {
                $(this).click(function (e) {
                    e.preventDefault();
                    wszechwiedzacy.admin.id = $(this).attr("id");
                    //alert(wszechwiedzacy.admin.id);
                    // alert("Do you really want to delete the question #" + id +"?");
                    $("#question_delete").dialog("open");
                });
            }); // end of click function
        },
        // end of setDeleteLinks
        pytania: function () {
            alert("empty pytania");
        } // end of admin.pytania
    },
    // end of wszechwiedzacy.admin
    
    kontakt: {
        init: function () {
            $("#frmKontakt").find("#name").focus();
            $("#kontakt_submit").click(function (e) {
                e.preventDefault();
                var validator = $("#frmKontakt").validate({
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
                            rangelength: "Min 15 znaków, max 255"
                        }
                    },
                    success: function (label) {
                        label.text("").addClass("success");
                    }
                }).form();
				
                if ($("#frmKontakt").valid()) {
                    var name = $("#kontakt_name").val();
                    var email = $("#kontakt_email").val();
                    var subject = $("#temat :selected").text();
                    var msg = $("#mainMessage").val();                    
                    var values = "name=" + name + "&email=" + email + "&temat=" + subject + "&msg=" + msg;
                    // console.log(values);
                    
                    $.ajax({
                        type: "POST",
                        url: wszechwiedzacy.site_url + "includes/email.php",
                        data: values,
                        dataType: "json",
                        success: function (data) {
                            // alert(data.msg);
                            if (data.msg == "valid") {
                                $(".formWrap").fadeOut(1000, function () {
                                    wszechwiedzacy.mc.html("<div class=\"emailConfirmation\"><span class=\"checkmark\"></span><h1>Email został wysłany.</h1><p>Dziękuję za kontakt. Spodziewaj się odpowiedzi w przeciągu 24h!</p><div class=\"clearfloat\"></div></div>").hide().fadeIn(500, function () {
                                        $(".checkmark").animate({top: +70}, 750, "easeOutBounce");
                                    }); // end of animate
                                });
                            } else {
                                console.log(data.msg);
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {console.log("błąd podczas przesyłania wiadomości: " + textStatus);}
                    }); // end of ajax
                    
                } // end of #frmKontakt valid
                
            }).hover(wszechwiedzacy.mHover).mousedown(wszechwiedzacy.mDownUp).mouseup(wszechwiedzacy.mDownUp);
            
        } // end of kontakt.init()
        
    },
    // end of wszechwiedzacy.kontakt
    
    ranking: {
        init: function () {
            
            // var htr = $("tr:contains("+wszechwiedzacy.session_pts+")").attr('id');
            // console.log("pts: "+wszechwiedzacy.session_pts+"htr = "+htr);
            
        } // end of ranking.init()        
    },
    // end of ranking
    
    twojaStrona: {
        init: function(){
            
            $("#edytuj_profil").click(function (e) {
                e.preventDefault();
                $("#edit_profile").dialog("open");
            });
            
            $("#edit_profile").dialog({
                autoOpen: false,
                draggable: false,
                resizable: false,
                bgiframe: true,
                modal: true,
				dialogClass: 'profil',
                width: 550,
                title: 'Edycja profilu',
                buttons: {
                    Aktualizuj: function () {
                        var wiek = $("#wiek:checked");
                        var sex = $("input:radio:checked");
                        var newsletter = $("#newsletter:checked");
                        var passwords = $("input:password");
                        var collected = "username=" + $("#userName").val() + "&wiek=" + wiek.val() + "&sex=" + sex.val() + "&city=" + $("#city").val() + "&degree=" + $("#degree").val() + "&newsletter=" + newsletter.val() + "&old_pass=" + passwords.eq(0).val() + "&new_pass=" + passwords.eq(1).val() + "&new_pass2=" + passwords.eq(2).val();
                        // console.log(collected);
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
                        } // end passwords.val() != ""
                        
                        if ($("#profile_form").valid()) {
                            $("#edit_profile .ajaxLoader").show();
                            $("#profile_form").hide();
                            
                            $.ajax({                                
                                type: "POST",
                                data: collected,
                                url: wszechwiedzacy.site_url + "includes/profil.php",
                                dataType: "json",
                                success: function (data) {
                                    
                                    if (data.msg == "ok") {
                                        
                                        window.location.reload();
                                        
                                    } else {
                                        $("#edit_profile .ajaxLoader").hide();
                                        $("#profile_form").show();
                                        
                                        if (data.msg == "wrong old password") {
                                            
                                            var validator = $("#profile_form").validate();
                                            validator.showErrors({"old_pass": "Obecne hasło jest niepoprawne!"});
                                            
                                        } else if (data.msg == "old equals new") {
                                            
                                            var validator = $("#profile_form").validate();
                                            validator.showErrors({"new_pass": "To jest stare hasło!"});
                                            
                                        } else {
                                            console.log(data.msg);
                                        }
                                        
                                    }
                                    
                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {alert("Błąd podczas aktualizacji danych w profilu: " + textStatus);}
                                
                            }); // end of ajax
                            
                        }
                        
                    },
                    Anuluj: function() {$(this).dialog("close");}
                    
                } // end of buttons
            }); // end of edit_profile dialog
            
            $link_sfs = $("#show_full_stats");
            $link_sfs.click(function(){                
                $("#full_stats").slideToggle(1000, function () {
                    ($link_sfs.text() == "pokaż dodatkowe statystyki") ? $link_sfs.text("ukryj dodatkowe statystyki") : $link_sfs.text("pokaż dodatkowe statystyki");
                });                
            });
            
        }
        
    },
    // end of twojaStrona
    
    changeFocus: function () {
        /**
         * changes focus from the default to first button work on jQuery UI dialog
         * component for now it only blurs the default button so that nothing is
         * selected on open
         */
        $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').remove();
        $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').focus();
        $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur();
    },
    // end of changeFocus
    
    time: {
        
        newInt: {},
        // interval for counting time
        sms: 2000,
        // starting value for miliseconds
        ms: {},
        // miliseconds used for counting
        pts: {},
        // points and also the percentage of progressbar
        init: function () {
            wszechwiedzacy.time.ms = wszechwiedzacy.time.sms; // every new question starts with the default time that is set by sms
            wszechwiedzacy.time.newInt = setInterval("wszechwiedzacy.time.countTime()", 100); // countTime function gets called every 100ms
        },
        
        countTime: function () {
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
                $("#submitBtn").trigger("click");
            }
            else {
                wszechwiedzacy.time.ms -= 10;
                wszechwiedzacy.time.pts = Math.round((wszechwiedzacy.time.ms * 100) / wszechwiedzacy.time.sms);
                $("#progressbar").progressbar('value', wszechwiedzacy.time.pts);                
            }
        },
        
        showLabels: function () {
            if (wszechwiedzacy.gra.cdnum < 4) {
                $("p.answer:eq(" + wszechwiedzacy.gra.cdnum + ")").fadeIn(750, function(){
					$(this).find("span").stop(true,true).animate({opacity:0}, 750, 'easeOutSine');
				});
                wszechwiedzacy.gra.cdnum++;
                if (wszechwiedzacy.gra.cdnum < 4) {
                    wszechwiedzacy.gra.countdown = setTimeout("wszechwiedzacy.time.showLabels()", 300);
                }
            }
            if (wszechwiedzacy.gra.cdnum == 4) {
                clearTimeout(wszechwiedzacy.gra.countdown);
                // starts the countdown
                wszechwiedzacy.time.init();
            }
            
        }
        // end of showLabels
        
    }
    //time
    
}; // end of wszechwiedzacy library
