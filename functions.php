<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/registration/vendor/autoload.php';
require 'C:/xampp/htdocs/registration/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/registration/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'C:/xampp/htdocs/registration/vendor/phpmailer/phpmailer/src/Exception.php';

// functions.php

// Adaugă această funcție în fișierul functions.php
function buyTicket($conn, $idFilm, $idUtilizator) {
    // Obține programul disponibil pentru filmul respectiv
    $program = getProgramForMovie($conn, $idFilm);

    if ($program) {
        // Inserează un nou bilet în baza de date
        $dataBilet = date('Y-m-d H:i:s');
        $result = insertBilet($conn, $program['id_program'], $idUtilizator, $idFilm, $dataBilet);

        if ($result) {
            return "Biletul a fost cumpărat cu succes!";
        } else {
            return "Eroare la cumpărarea biletului.";
        }
    } else {
        return "Nu există program disponibil pentru acest film.";
    }
}

// Adaugă această funcție pentru a obține programul disponibil pentru un film
function getProgramForMovie($conn, $idFilm) {
    $sql = "SELECT * FROM program_filme WHERE id_film = ? AND data > NOW() LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $idFilm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }

    return false;
}


function getAllReservations($conn) {
    // Definește interogarea SQL
    $sql = "SELECT * FROM bilet";

    // Execută interogarea
    $result = mysqli_query($conn, $sql);

    // Verifică dacă interogarea a fost realizată cu succes
    if ($result) {
        // Extrage rezultatele într-un array asociativ
        $rezervari = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Eliberează rezultatele interogării
        mysqli_free_result($result);

        return $rezervari;
    } else {
        // În caz de eroare, returnează false
        return false;
    }
}


function insertProgram($conn, $idFilm, $idCinema, $idSala, $idLoc, $data, $ora) {
    $sql = "INSERT INTO program_filme (id_film, id_cinema, id_sala, id_loc, data, ora) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'iiisss', $idFilm, $idCinema, $idSala, $idLoc, $data, $ora);
        mysqli_stmt_execute($stmt);

        // Verifică dacă s-a realizat cu succes inserarea
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        return false;
    }
}

function insertLoc($conn, $idSala, $idCinema) {
    $sql = "INSERT INTO loc (id_sala, id_cinema) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $idSala, $idCinema);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            echo "Eroare la executarea declarației: " . mysqli_error($conn);
        }
    } else {
        echo "Eroare la pregătirea declarației: " . mysqli_error($conn);
    }

    return false;
}



function insertSala($conn, $idCinema, $numeSala) {
    $sql = "INSERT INTO sali (id_cinema, nume_sala) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("is", $idCinema, $numeSala);
        $stmt->execute();

        // Verifică dacă inserarea a avut succes
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    } else {
        return false;
    }
}



function insertCinema($conn, $nume, $locatie) {
    $stmt = mysqli_prepare($conn, "INSERT INTO `cinema` (`nume_cinema`, `locatie`) VALUES (?, ?)");

    if (!$stmt) {
        die('Error in prepared statement: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $nume, $locatie);

    $query = mysqli_stmt_execute($stmt);

    if (!$query) {
        die('Error in execution: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);

    return $query;
}


function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        return $users;
    } else {
        return false;
    }
}

function getAllTickets($conn) {
    $sql = "SELECT * FROM bilet";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $tickets = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tickets[] = $row;
        }
        return $tickets;
    } else {
        return false;
    }
}




function getProgramsForCinema($conn, $idCinema) {
    $sql = "SELECT * FROM program_filme WHERE id_cinema = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idCinema);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $programs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $programs[] = $row;
        }
        return $programs;
    } else {
        return false;
    }
}


function getAllCinemas($conn) {
    $sql = "SELECT * FROM cinema";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $cinemas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $cinemas[] = $row;
        }
        return $cinemas;
    } else {
        return false;
    }
}

function getRoomsForCinema($conn, $idCinema) {
    $sql = "SELECT * FROM sali WHERE id_cinema = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idCinema);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $rooms = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
        return $rooms;
    } else {
        return false;
    }
}

function getSeatsForRoom($conn, $idRoom) {
    $sql = "SELECT * FROM loc WHERE id_sala = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idRoom);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $seats = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $seats[] = $row;
        }
        return $seats;
    } else {
        return false;
    }
}


function getAllMovies($conn) {
    $sql = "SELECT * FROM filme";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $filme = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Actualizați cheia 'titlu' la 'Titlu'
            $row['titlu'] = $row['Titlu'];
            unset($row['Titlu']); // Ștergeți vechea cheie 'Titlu' dacă este necesar
            $filme[] = $row;
        }
        return $filme;
    } else {
        return false;
    }
}

function insertBilet($conn, $idProgram, $idUtilizator, $idFilm, $dataBilet) {
    // Inserează un rând în tabela 'bilet' și obține id-ul biletului inserat
    $queryBilet = "INSERT INTO bilet (id_program, id_utilizator, data_bilet) VALUES (?, ?, ?)";
    $stmtBilet = mysqli_prepare($conn, $queryBilet);

    if ($stmtBilet) {
        mysqli_stmt_bind_param($stmtBilet, 'iis', $idProgram, $idUtilizator, $dataBilet);
        $resultBilet = mysqli_stmt_execute($stmtBilet);

        // Verifică dacă inserarea în tabela 'bilet' a avut succes
        if ($resultBilet) {
            $idBilet = mysqli_insert_id($conn); // Obține id-ul biletului inserat

            // Inserează un rând în tabela 'bilet_utilizator_filme' cu id-ul biletului și id-ul filmului
            $queryBiletUtilizatorFilm = "INSERT INTO bilet_utilizator_filme (id_bilet, id_utilizator, id_film) VALUES (?, ?, ?)";
            $stmtBiletUtilizatorFilm = mysqli_prepare($conn, $queryBiletUtilizatorFilm);

            if ($stmtBiletUtilizatorFilm) {
                mysqli_stmt_bind_param($stmtBiletUtilizatorFilm, 'iii', $idBilet, $idUtilizator, $idFilm);
                $resultBiletUtilizatorFilm = mysqli_stmt_execute($stmtBiletUtilizatorFilm);
                mysqli_stmt_close($stmtBiletUtilizatorFilm);

                return $resultBiletUtilizatorFilm;
            }
        }

        mysqli_stmt_close($stmtBilet);
    }

    return false;
}








function addGenres($conn, $genres) {
    foreach ($genres as $genreName) {
        // Verifică dacă genul există deja în tabela 'genuri'
        $sqlCheckGenre = "SELECT * FROM genuri WHERE nume = ?";
        $stmtCheckGenre = $conn->prepare($sqlCheckGenre);
        $stmtCheckGenre->bind_param("s", $genreName);
        $stmtCheckGenre->execute();
        $resultCheckGenre = $stmtCheckGenre->get_result();
        $stmtCheckGenre->close();

        if ($resultCheckGenre->num_rows === 0) {
            // Dacă genul nu există, îl adăugăm în tabela 'genuri'
            $sqlAddGenre = "INSERT INTO genuri (nume) VALUES (?)";
            $stmtAddGenre = $conn->prepare($sqlAddGenre);
            $stmtAddGenre->bind_param("s", $genreName);
            $stmtAddGenre->execute();
            $stmtAddGenre->close();
        }
    }
}




function sendVerificationCode($email, $verificationCode) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'verificare372@gmail.com';
        $mail->Password   = 'xkzempazckdxxnxf';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('verificare372@gmail.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Account Verification';
        $mail->Body    = 'Your verification code is: ' . $verificationCode;

        $mail->send();

        return $verificationCode;
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        return false;
    }
}

function generateVerificationCode() {
    return bin2hex(random_bytes(6)); // Generates a 12-character hexadecimal code
}







function getUserByEmail($email) {
    $conn = connectToDatabase();
    $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE `email` = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    mysqli_close($conn);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        return $user;
    } else {
        echo 'Error: ' . mysqli_error($conn);  // Add this line to check for errors
        return null;
    }
}



function updateUserStatus($userId, $status) {
    $conn = connectToDatabase();
    $stmt = mysqli_prepare($conn, "UPDATE `users` SET `status` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_close($conn);
}


function connectToDatabase() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'proiect';

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}





function processMovieDetails($conn, $details) {
    $sql = "INSERT INTO filme (titlu, regizor, durata) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifică dacă prepararea statement-ului a avut succes
    if ($stmt) {
        // Leagă parametrii și setează valorile
        $stmt->bind_param("sss", $details['titlu'], $details['regizor'], $details['durata']);

        // Execută statement-ul
        $stmt->execute();

        // Verifică dacă inserarea a avut succes
if ($stmt->affected_rows > 0) {
    // Obțineți id-ul filmului inserat
    $filmId = $conn->insert_id;

    // Adaugă genurile în tabela 'genuri'
    addGenres($conn, $details['genuri']);

    // Inserează legăturile dintre filme și genuri în tabela gen_filme
    foreach ($details['genuri'] as $genreName) {
        // Obține id-ul genului
        $sqlGetGenreId = "SELECT id_gen FROM genuri WHERE nume = ?";
        $stmtGetGenreId = $conn->prepare($sqlGetGenreId);
        $stmtGetGenreId->bind_param("s", $genreName);
        $stmtGetGenreId->execute();
        $resultGetGenreId = $stmtGetGenreId->get_result();
        $genreId = $resultGetGenreId->fetch_assoc()['id_gen'];
        $stmtGetGenreId->close();

        // Inserează legătura în tabela gen_filme
        $sqlGenFilm = "INSERT INTO gen_filme (id_film, id_gen) VALUES (?, ?)";
        $stmtGenFilm = $conn->prepare($sqlGenFilm);
        $stmtGenFilm->bind_param("ii", $filmId, $genreId);
        $stmtGenFilm->execute();
        $stmtGenFilm->close();
    }
} else {
    echo "Eroare la inserarea detaliilor filmului în tabela 'filme'.";
}


        // Închide statement-ul
        $stmt->close();
    } else {
        echo "Eroare la pregătirea statement-ului: " . $conn->error;
    }
}




function validateEmail($email) {
    // Folosește filter_var pentru a valida adresa de email
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function addUser($name, $email, $phone, $message, $hashedPassword, $status = 'user') {
    $conn = connectToDatabase();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use prepared statement to avoid SQL injection
    $stmt = mysqli_prepare($conn, "INSERT INTO `users` (`name`, `email`, `phone`, `message`, `password`, `status`) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die('Error in prepared statement: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $message, $hashedPassword, $status);


    $query = mysqli_stmt_execute($stmt);

    if (!$query) {
        die('Error in execution: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $query;
}



function verifyPassword($inputPassword, $storedPassword) {
    // Folosește o funcție de hashing (de exemplu, password_hash) pentru a stoca și verifica parolele
    return password_verify($inputPassword, $storedPassword);
}



function getUserById($id) {
    $conn = connectToDatabase();

    $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_close($conn);

    return mysqli_fetch_assoc($result);
}
?>
