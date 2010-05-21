<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Usunięcie z listy mailingowej</title>
</head>
    <body>    
        <?php if(isset($_GET['email'])): ?>
        <p>Adres <?php echo $_GET['email']; ?> został usunięty z bazy mailingowej. Przykro nam, że rezygnujesz z comiesięcznego informatora o nowościach w serwisie.</p>
        <?php else : ?>
        <p>Nie podano adresu email!</p>
        <?php endif; ?>
    </body>
</html>