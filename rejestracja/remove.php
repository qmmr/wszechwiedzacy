<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<title>Usunięcie z listy mailingowej</title>
    <style>
    body {
        color: #292929;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 100.01%;
        
    }
    a:link, a:visited {color:#0073ea; text-decoration:none;}
    a:hover, a:active {color:#A1D700; text-decoration:underline;}
    #msg {
        width: 660px;
        padding: 20px;
        margin: 40px auto;
        background-color: #FFEFEF;
        border: 1px solid #FF8F8F;
        text-align: center;
        height: 66px;
    }
    p {
        margin: 0;
        line-height: 32px;
    }
    p.done {
        line-height: 66px;
    }
    .lg {
        display: none;
        background: url(../images/ajax-loader.gif) no-repeat scroll 0 0;
        height: 66px;
        width: 66px;
        margin: 0 auto;
    }
    </style>
    
    <script src="http://www.google.com/jsapi"></script>
    <script>google.load("jquery", "1.4.2");</script>
    <?php
	   
    ?>
    <script>
    $(function(){
        $("#rmv").click(function(e){
            
            e.preventDefault();
            $("#msg").html("<div class='lg'></div>")
                .css('backgroundColor','#FFF')
                .css('borderColor','#FFF')
                .find(".lg")
                .show();
            $.ajax({
                type: "POST",
                data: "email=<?php echo(isset($_GET['email'])) ? $email = $_GET['email'] : $email = ""; ?>",
                url: "remove_email.php",
                dataType: "json",
                success: function(data){
                    
                    if(data.outcome == "ok"){
                        
                        $("#msg").css('backgroundColor','#F3FFCF')
                        .css('borderColor','#A1D700')
                        .html("<p class='done'>Adres " + data.msg + " został usunięty z bazy mailingowej wszechwiedzacy.pl</p>")
                        .find("p")
                        .hide()
                        .fadeIn(1000);
                            
                    } else {
                        
                        $("#msg").html("<p class='done'>Błąd -> " + data['msg'] + "</p>")
                            .find("p")
                            .hide()
                            .fadeIn(1000);
                        
                    }
                                                             
                },
                error: function(){
                    console.log("could not remove your email");
                }  
            });                        

        });
    });
    </script>
</head>
    <body> 
        <div id="msg">   
            <?php if(isset($_GET['email'])): ?>
            <p>Rezygnujesz z informatora o nowościach w serwisie <a href="http://wszechwiedzacy.pl/">wszechwiedzacy.pl</a></p>
            <p>Aby potwierdzić usunięcie adresu <?php echo $_GET['email']; ?> z bazy mailingowej, <a id="rmv" href="#">kliknij tutaj</a></p>
            <?php else : ?>
            <p class="done">Nie podano adresu email!</p>
            <?php endif; ?>
        </div>
    </body>
</html>