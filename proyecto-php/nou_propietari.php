<?php
// Tasca 4: Formulari d'alta de propietaris
// Incluim la connexió a la base de dades i la capçalera (reutilització de codi) [1, 2]
include 'db.php';
include 'header.php';

$missatge = ""; 

// Comprovem si el formulari s'ha enviat mitjançant el mètode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    /* 
       CORRECCIÓ: Utilitzem mysqli_real_escape_string() per sanejar les dades.
       Això evita l'error "Call to undefined function" i protegeix la BD [2].
    */
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adreca = mysqli_real_escape_string($conn, $_POST['adreca']);

    // Sentència SQL per inserir el nou propietari segons l'esquema definit [3]
    $sql = "INSERT INTO Propietaris (nom, telefon, email, adreca) 
            VALUES ('$nom', '$telefon', '$email', '$adreca')";

    if (mysqli_query($conn, $sql)) {
        $missatge = "<p style='color: #28cd41; font-weight: bold;'>Propietari registrat correctament!</p>";
    } else {
        // Gestió d'errors: Compte amb la restricció UNIQUE de l'email [2, 3]
        if (mysqli_errno($conn) == 1062) {
            $missatge = "<p style='color: #ff3b30; font-weight: bold;'>Error: Aquest correu electrònic ja està registrat.</p>";
        } else {
            $missatge = "<p style='color: #ff3b30; font-weight: bold;'>Error en registrar: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<main>
    <section>
        <h2>Afegir Nou Propietari</h2>
        
        <!-- Zona de missatges per a l'usuari [2] -->
        <?php echo $missatge; ?>

        <!-- Formulari d'alta amb els camps requerits a la Tasca 4 [1, 3] -->
        <form action="nou_propietari.php" method="POST">
            <label for="nom">Nom complet (obligatori):</label>
            <input type="text" name="nom" id="nom" required placeholder="Ex: Joan Pere">

            <label for="telefon">Telèfon:</label>
            <input type="text" name="telefon" id="telefon" placeholder="600000000">

            <label for="email">Correu electrònic (únic):</label>
            <input type="email" name="email" id="email" placeholder="usuari@exemple.com">

            <label for="adreca">Adreça:</label>
    <input type="text" name="adreca" id="adreca" placeholder="Carrer Major, 1">

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-add">Guardar Propietari</button>
                <a href="propietaris.php" class="btn" style="background: rgba(0,0,0,0.05); color: #1d1d1f;">Tornar al llistat</a>
            </div>
        </form>
    </section>
</main>

<?php 
// Incluim el peu de pàgina per tancar les etiquetes HTML [1]
include 'footer.php'; 
?>