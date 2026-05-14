<?php
// Incluimos los archivos base necesarios [1, 3]
include 'db.php';
include 'header.php';

$missatge = ""; // Variable para mostrar alertas al usuario

// Comprobamos si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos y saneamos los datos del formulario
    $nom = mysqli_real_escape_with_string($conn, $_POST['nom']);
    $telefon = mysqli_real_escape_with_string($conn, $_POST['telefon']);
    $email = mysqli_real_escape_with_string($conn, $_POST['email']);
    $adreca = mysqli_real_escape_with_string($conn, $_POST['adreca']);

    // Sentencia SQL para insertar el nuevo propietario [2]
    $sql = "INSERT INTO Propietaris (nom, telefon, email, adreca) 
            VALUES ('$nom', '$telefon', '$email', '$adreca')";

    if (mysqli_query($conn, $sql)) {
        $missatge = "<p style='color: green;'>Propietari registrat correctament!</p>";
    } else {
        // Gestión de errores: por ejemplo, si el email ya existe (UNIQUE) [3]
        $error = mysqli_errno($conn) == 1062 ? "L'email ja està registrat." : mysqli_error($conn);
        $missatge = "<p style='color: red;'>Error en registrar: $error</p>";
    }
}
?>

<section>
    <h2>Afegir Nou Propietari</h2>
    
    <!-- Mostramos el mensaje de éxito o error si existe [3] -->
    <?php echo $missatge; ?>

    <form action="nou_propietari.php" method="POST">
        <label for="nom">Nom complet (obligatori):</label>
        <input type="text" name="nom" id="nom" required>

        <label for="telefon">Telèfon:</label>
        <input type="text" name="telefon" id="telefon">

        <label for="email">Correu electrònic (únic):</label>
        <input type="email" name="email" id="email">

        <label for="adreca">Adreça:</label>
        <input type="text" name="adreca" id="adreca">

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-add">Guardar Propietari</button>
            <a href="propietaris.php" class="btn" style="background-color: #95a5a6;">Tornar al llistat</a>
        </div>
    </form>
</section>

<?php 
include 'footer.php'; 
?>