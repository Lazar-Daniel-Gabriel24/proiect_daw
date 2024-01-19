<?php
session_start();
include('connect.php');
include('functions.php');
require_once('fetch_and_insert_movie.php');

// Initialize session variables
if (!isset($_SESSION['formSubmitted'])) {
    $_SESSION['formSubmitted'] = false;
}

$message = ''; // Initialize an empty message variable

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_info = getUserById($user_id);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the fetchMovies button was clicked and form not previously submitted
    if (isset($_POST['fetchMovies']) && !$_SESSION['formSubmitted']) {
        // URL-ul pentru detaliile filmului pe Cinemacity
        $link = 'https://www.cinemacity.ro/films/familia-addams-2/4522d2r#/buy-tickets-by-film?for-movie=4522d2r&view-mode=list';

        // Fetch și inserați detaliile filmului în baza de date
        fetchAndInsertMovieDetails($conn, $link);

        // Set the message variable
        $message = 'Detaliile filmului au fost preluate și inserate cu succes în baza de date.';

        // Set session variable to mark form as submitted
        $_SESSION['formSubmitted'] = true;

        // Redirect to avoid resubmission on refresh
        header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertCinema'])) {
    $numeCinema = $_POST['nume_cinema'];
    $locatieCinema = $_POST['locatie_cinema'];

    $inserted = insertCinema($conn, $numeCinema, $locatieCinema);

    if ($inserted) {
        echo "Cinema a fost inserat cu succes.";
    } else {
        echo "Eroare la inserarea cinema-ului.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertSala'])) {
    $idCinema = $_POST['id_cinema']; 
    $numeSala = $_POST['nume_sala'];

    $inserted = insertSala($conn, $idCinema, $numeSala);

    if ($inserted) {
        echo "Sala a fost inserată cu succes.";
    } else {
        echo "Eroare la inserarea sălii.";
    }
}

if (isset($_POST['insertLoc'])) {
    $idSala = $_POST['id_sala']; 
    $idCinema = $_POST['id_cinema']; 

    $inserted = insertLoc($conn, $idSala, $idCinema);

    if ($inserted) {
        echo "Locul a fost inserat cu succes.";
    } else {
        echo "Eroare la inserarea locului.";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertProgram'])) {
    $idFilm = $_POST['id_film']; 
    $idCinema = $_POST['id_cinema']; 
    $idSala = $_POST['id_sala']; 
    $idLoc = $_POST['id_loc']; 
    $data = $_POST['data'];
    $ora = $_POST['ora'];

    $inserted = insertProgram($conn, $idFilm, $idCinema, $idSala, $idLoc, $data, $ora);

    if ($inserted) {
        echo "Programul de film a fost inserat cu succes.";
    } else {
        echo "Eroare la inserarea programului de film.";
    }
}




?>

<!DOCTYPE html>
<html lang="ro">
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pagina de administrare a cinematografului - inserare, actualizare și gestionare informații.">
    <meta name="keywords" content="admin, cinema, inserare, actualizare, gestionare, filme, sali, programe, bilete">
    <meta name="author" content="Numele Dvs.">
    
    <title>Admin Page - Gestiune Cinema</title>
    
    <!-- Adăugați în head pentru Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alte optimizări SEO -->
    <link rel="canonical" href="URL_CANONIC">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        h1 {
            margin: 0;
        }
        main {
            flex: 1;
            padding: 20px;
            text-align: center;
        }
        p {
            line-height: 1.6;
            font-size: 18px;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        #logout-btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #logout-btn:hover {
            background-color: #555;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bun venit pe pagina de admin, <?php echo $user_info['name']; ?>!</h1>
    </header>
    <!-- Afișare grafic -->
    <canvas id="myChart" width="400" height="200"></canvas>
    <script>
        
        // Exemplu simplu cu Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sala 1', 'Sala 2', 'Sala 4'],
                datasets: [{
                    label: 'Număr de locuri disponibile',
                    data: [50, 50, 23],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <main>
        <p>Aici poți adăuga orice informații sau funcționalități specifice utilizatorilor cu rol de admin.</p>
        
        <!-- Adaugă un formular pentru a prelua și insera detaliile filmului -->
        <form action="" method="post" autocomplete="off">
            <input type="hidden" name="fetchMovies" value="1">
            <input type="submit" value="Preluați și inserați detaliile filmului">
        </form>

        <!-- Afișează mesajul de succes, dacă există și formularul a fost trimis -->
        <?php if ($_SESSION['formSubmitted'] && !empty($message)): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>


        <form method="post" action="">
        <label for="nume_cinema">Nume Cinema:</label>
        <input type="text" name="nume_cinema" required><br>

        <label for="locatie_cinema">Locație Cinema:</label>
        <input type="text" name="locatie_cinema" required><br>

        <input type="submit" name="insertCinema" value="Inserare Cinema">
    </form>

    <!-- Formular pentru inserarea sălii -->
    <form method="post" action="">
            <label for="id_cinema">ID Cinema:</label>
            <input type="text" name="id_cinema" required><br>

            <label for="nume_sala">Nume Sală:</label>
            <input type="text" name="nume_sala" required><br>

            <input type="submit" name="insertSala" value="Inserare Sală">
        </form>
    

        <form method="post" action="" autocomplete="off">
        <label for="id_sala">ID Sala:</label>
        <input type="text" name="id_sala" required><br>

        <label for="id_cinema">ID Cinema:</label>
        <input type="text" name="id_cinema" required><br>

        <input type="submit" name="insertLoc" value="Inserare Loc">
    </form>

    </main>
    


<form method="post" action="" autocomplete="off">
    <label for="id_film">ID Film:</label>
    <input type="text" name="id_film" required><br>

    <label for="id_cinema">ID Cinema:</label>
    <input type="text" name="id_cinema" required><br>

    <label for="id_sala">ID Sala:</label>
    <input type="text" name="id_sala" required><br>

    <label for="id_loc">ID Loc:</label>
    <input type="text" name="id_loc" required><br>

    <label for="data">Data:</label>
    <input type="text" name="data" required><br>

    <label for="ora">Ora:</label>
    <input type="text" name="ora" required><br>

    <input type="submit" name="insertProgram" value="Inserare Program Film">
</form>


    <form action="logout.php" method="post">
        <input type="submit" id="logout-btn" name="logout" value="Logout">
    </form>

</body>
</html>
