<?php
session_start();

include('connect.php');
include('functions.php');

$user_id = $_SESSION['user_id']; // Move this line here

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buyTicket'])) {
    $filmIdToBuy = $_POST['buyTicket'];
    $buyTicketResult = buyTicket($conn, $filmIdToBuy, $user_id);
    echo '<p class="success-message">' . $buyTicketResult . '</p>';
}


$user_id = $_SESSION['user_id'];
$user_info = getUserById($user_id);



?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Pagina Web</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        main {
            flex: 1;
            padding: 20px;
        }
        section {
            margin-bottom: 30px;
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        p {
            line-height: 1.6;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: #fff;
        }
        /* Stiluri meniu */
        nav {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: #fff;
        }
        #logout-container {
            text-align: right;
            margin-top: 10px;
        }

        #logout-btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #logout-btn:hover {
            background-color: #555;
        }

        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
        <div id="logout-container">
            <form action="logout.php" method="post">
                <input type="submit" id="logout-btn" name="logout" value="Logout">
            </form>
        </div>
        <div style="text-align: center;">
            <h1>Proiect: Rezervare Bilete Online pentru Filme</h1>
        </div>
        
    </header>
    
    <nav>
        <ul>
            <li><a href="#descriere">Descriere Aplicație</a></li>
            <li><a href="#arhitectura">Arhitectură</a></li>
            <li><a href="#implementare">Implementare</a></li>
			<li><a href="#diagrama">Diagramă a Arhitecturii Aplicației</a></li>
        </ul>
    </nav>

    <main>
    <section id="filme">
        <h2>Filme Disponibile</h2>

        <?php
        // Extrage toate filmele din baza de date
        $filme = getAllMovies($conn);

        // Verifică dacă există filme înainte de a le afișa
        if ($filme) {
            echo '<ul>';
            foreach ($filme as $film) {
                // Verifică dacă există cheile înainte de a le utiliza
                $titlu = isset($film['titlu']) ? $film['titlu'] : 'N/A';
                $regizor = isset($film['regizor']) ? $film['regizor'] : 'N/A';
                $durata = isset($film['durata']) ? $film['durata'] : 'N/A';
                $idFilm = $film['id_film'];

                echo '<li>';
                echo $titlu . ' - Regizor: ' . $regizor . ' - Durată: ' . $durata . ' minute';
                echo '<form action="" method="post" autocomplete="off">';
                echo '<input type="hidden" name="buyTicket" value="' . $idFilm . '">';
                echo '<input type="submit" value="Cumpără Bilet">';
                echo '</form>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nu există filme disponibile în acest moment.</p>';
        }
        ?>

    </section>

    <?php if (isset($message)): ?>
        <p class="success-message"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>


        <section id="descriere">
            <h2>Descriere a aplicației</h2>
            <p>Rezervarea de bilete online pentru filme reprezintă o platformă care permite utilizatorilor să caute, vizualizeze și să-și rezerve bilete pentru filmele disponibile în cinematografele partenere. Utilizatorii pot vedea programul filmelor, săli de cinema disponibile și să-și selecționeze locurile dorite pentru vizionarea filmelor preferate.</p>
            <p>Aplicația va oferi, de asemenea, facilități pentru administratori pentru a gestiona filmele, programele, sălile și rezervările.</p>
        </section>

        <section id="arhitectura">
            <h2>Arhitectura Aplicației</h2>
            <p>Principalele roluri includ utilizatorii finali și administratorii. Entitățile-cheie sunt filmele, sălile de cinema, programul filmelor , utilizatorii, și biletele.</p>
            <p>Componentele principale includ interfața utilizator (UI), partea de backend care gestionează logica aplicației și baza de date care stochează detaliile despre filme, săli, programe și rezervări.</p>
            <p>Baza de date va include tabele pentru filme, săli, programul zilnic/săptămânal al filmelor și detalii despre rezervări, astfel încât să gestioneze informațiile necesare pentru funcționarea aplicației.</p>
        </section>

        <section id="implementare">
            <h2>Soluție de Implementare Propusă</h2>
            <p>Se propune utilizarea UML pentru a defini detalii despre modelele de date, relațiile între entități și fluxurile de procesare.</p>
            <p>Pe partea de implementare, se va utiliza un limbaj de programare (cum ar fi JavaScript cu Node.js sau Python cu Django/Flask) pentru dezvoltarea backend-ului și interfața utilizator pentru interacțiunea utilizatorului.</p>
        </section>
		<section id="diagrama">
		<h2>Diagramă a Arhitecturii Aplicației</h2>
		<img src="diagrama.drawio.png" alt="Diagrama Arhitecturii Aplicației">
		</section>
    </main>

    <footer>
        <p>&copy; 2023 Lazar Daniel Gabriel</p>
    </footer>
</body>
</html>
