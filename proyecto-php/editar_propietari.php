<?php
// Incluimos la conexión y la cabecera
include 'db.php';
include 'header.php';

$missatge = "";

// 1. Obtener los datos actuales del propietario para rellenar el formulario
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql_select = "SELECT * FROM Propietaris WHERE id = $id";
    $result = mysqli_query($conn, $sql_select);
    $propietari = mysqli_fetch_assoc($result);

    if (!$propietari) {
        die("Propietari no trobat.");
    }
} else {
    header("Location: propietaris.php");
    exit();
}

// 2. Procesar la actualización cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adreca = mysqli_real_escape_string($conn, $_POST['adreca']);

    $sql_update = "UPDATE Propietaris 
                   SET nom='$nom', telefon='$telefon', email='$email', adreca='$adreca' 
                   WHERE id=$id";

    if (mysqli_query($conn, $sql_update)) {
        $missatge = "<p style='color: green;'>Dades actualitzades correctament!</p>";
        // Refrescamos los datos para que el formulario muestre los cambios
        $result = mysqli_query($conn, $sql_select);
        $propietari = mysqli_fetch_assoc($result);
    } else {
        $error = mysqli_errno($conn) == 1062 ? "L'email ja està registrat per un altre usuari." : mysqli_error($conn);
        $missatge = "<p style='color: red;'>Error en actualitzar: $error</p>";
    }
}
?>

<section>
    <h2>Editar Propietari: <?php echo $propietari['nom']; ?></h2>
    
    <?php echo $missatge; ?>

    <form action="editar_propietari.php?id=<?php echo $id; ?>" method="POST">
        <label for="nom">Nom complet (obligatori):</label>
        <input type="text" name="nom" id="nom" value="<?php echo $propietari['nom']; ?>" required>

        <label for="telefon">Telèfon:</label>
        <input type="text" name="telefon" id="telefon" value="<?php echo $propietari['telefon']; ?>">

        <label for="email">Correu electrònic (únic):</label>
        <input type="email" name="email" id="email" value="<?php echo $propietari['email']; ?>">

        <label for="adreca">Adreça:</label>
        <input type="text" name="adreca" id="adreca" value="<?php echo $propietari['adreca']; ?>">

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-edit">Actualitzar Dades</button>
            <a href="propietaris.php" class="btn" style="background-color: #95a5a6;">Tornar al llistat</a>
        </div>
    </form>
</section>

<?php 
include 'footer.php'; 
?>