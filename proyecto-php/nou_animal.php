<?php
// Incluimos la conexión y la cabecera
include 'db.php';
include 'header.php';

$missatge = "";

// 1. Obtener la lista de propietarios para el desplegable (select)
$sql_propietaris = "SELECT id, nom FROM Propietaris ORDER BY nom ASC";
$result_propietaris = mysqli_query($conn, $sql_propietaris);

// 2. Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Saneamiento de datos
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $especie = mysqli_real_escape_string($conn, $_POST['especie']);
    $raca = mysqli_real_escape_string($conn, $_POST['raca']);
    $data_naixement = mysqli_real_escape_string($conn, $_POST['data_naixement']);
    $id_propietari = mysqli_real_escape_string($conn, $_POST['id_propietari']);

    // Inserción en la tabla Animals
    $sql_insert = "INSERT INTO Animals (nom, especie, raca, data_naixement, id_propietari) 
                   VALUES ('$nom', '$especie', '$raca', '$data_naixement', '$id_propietari')";

    if (mysqli_query($conn, $sql_insert)) {
        $missatge = "<p style='color: green;'>Animal registrat correctament!</p>";
    } else {
        $missatge = "<p style='color: red;'>Error en registrar l'animal: " . mysqli_error($conn) . "</p>";
    }
}
?>

<section>
    <h2>Afegir Nou Animal</h2>
    
    <?php echo $missatge; ?>

    <form action="nou_animal.php" method="POST">
        <label for="nom">Nom de l'animal (obligatori):</label>
        <input type="text" name="nom" id="nom" required>

        <label for="especie">Espècie (ex: Gos, Gat, Conill...):</label>
        <input type="text" name="especie" id="especie" required>

        <label for="raca">Raça:</label>
        <input type="text" name="raca" id="raca">

        <label for="data_naixement">Data de Naixement:</label>
        <input type="date" name="data_naixement" id="data_naixement">

        <!-- Requisito: Desplegable de propietarios -->
        <label for="id_propietari">Propietari:</label>
        <select name="id_propietari" id="id_propietari" required>
            <option value="">-- Selecciona un propietari --</option>
            <?php
            while($prop = mysqli_fetch_assoc($result_propietaris)) {
                echo "<option value='" . $prop['id'] . "'>" . $prop['nom'] . "</option>";
            }
            ?>
        </select>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-add">Guardar Animal</button>
            <a href="animals.php" class="btn" style="background-color: #95a5a6;">Tornar al llistat</a>
        </div>
    </form>
</section>

<?php 
include 'footer.php'; 
?>