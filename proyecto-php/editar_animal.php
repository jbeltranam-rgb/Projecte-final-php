<?php
// Incluimos la conexión y la cabecera [3]
include 'db.php';
include 'header.php';

$missatge = "";

// 1. Obtener el ID del animal desde la URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Consulta para obtener los datos actuales del animal [4]
    $sql_animal = "SELECT * FROM Animals WHERE id = $id";
    $result_animal = mysqli_query($conn, $sql_animal);
    $animal = mysqli_fetch_assoc($result_animal);

    if (!$animal) {
        die("Animal no trobat.");
    }

    // Obtener todos los propietarios para el desplegable [1]
    $sql_propietaris = "SELECT id, nom FROM Propietaris ORDER BY nom ASC";
    $result_propietaris = mysqli_query($conn, $sql_propietaris);
} else {
    header("Location: animals.php");
    exit();
}

// 2. Procesar la actualización cuando se envía el formulario (UPDATE) [1]
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $especie = mysqli_real_escape_string($conn, $_POST['especie']);
    $raca = mysqli_real_escape_string($conn, $_POST['raca']);
    $data_naixement = mysqli_real_escape_string($conn, $_POST['data_naixement']);
    $id_propietari = mysqli_real_escape_string($conn, $_POST['id_propietari']);

    $sql_update = "UPDATE Animals 
                   SET nom='$nom', especie='$especie', raca='$raca', 
                       data_naixement='$data_naixement', id_propietari='$id_propietari' 
                   WHERE id=$id";

    if (mysqli_query($conn, $sql_update)) {
        $missatge = "<p style='color: green;'>Dades de l'animal actualitzades correctament!</p>";
        // Refrescamos los datos para el formulario
        $result_animal = mysqli_query($conn, $sql_animal);
        $animal = mysqli_fetch_assoc($result_animal);
    } else {
        $missatge = "<p style='color: red;'>Error en actualitzar: " . mysqli_error($conn) . "</p>";
    }
}
?>

<section>
    <h2>Editar Animal: <?php echo $animal['nom']; ?></h2>
    
    <?php echo $missatge; ?>

    <form action="editar_animal.php?id=<?php echo $id; ?>" method="POST">
        <label for="nom">Nom de l'animal (obligatori):</label>
        <input type="text" name="nom" id="nom" value="<?php echo $animal['nom']; ?>" required>

        <label for="especie">Espècie (obligatori):</label>
        <input type="text" name="especie" id="especie" value="<?php echo $animal['especie']; ?>" required>

        <label for="raca">Raça:</label>
        <input type="text" name="raca" id="raca" value="<?php echo $animal['raca']; ?>">

        <label for="data_naixement">Data de Naixement:</label>
        <input type="date" name="data_naixement" id="data_naixement" value="<?php echo $animal['data_naixement']; ?>">

        <label for="id_propietari">Propietari:</label>
        <select name="id_propietari" id="id_propietari" required>
            <?php
            while($prop = mysqli_fetch_assoc($result_propietaris)) {
                $selected = ($prop['id'] == $animal['id_propietari']) ? "selected" : "";
                echo "<option value='" . $prop['id'] . "' $selected>" . $prop['nom'] . "</option>";
            }
            ?>
        </select>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-edit">Actualitzar Animal</button>
            <a href="animals.php" class="btn" style="background-color: #95a5a6;">Tornar al llistat</a>
        </div>
    </form>
</section>

<?php 
// Incluimos el pie de página [3]
include 'footer.php'; 
?>