<?php
// Incluimos la conexión y la cabecera
include 'db.php';
include 'header.php';

$missatge = "";
$error = false;

// Verificamos si se ha recibido un ID válido por la URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Iniciamos una transacción para asegurar que ambos borrados se realicen correctamente
    mysqli_begin_transaction($conn);

    try {
        // 1. PASO OBLIGATORIO: Borrar primero las visitas del animal [1]
        $sql_visites = "DELETE FROM Visites WHERE id_animal = $id";
        mysqli_query($conn, $sql_visites);

        // 2. PASO FINAL: Borrar el registro del animal [1]
        $sql_animal = "DELETE FROM Animals WHERE id = $id";
        mysqli_query($conn, $sql_animal);

        // Si todo ha ido bien, confirmamos los cambios
        mysqli_commit($conn);
        $missatge = "L'animal i el seu historial de visites han estat eliminats correctament.";
    } catch (Exception $e) {
        // Si hay algún error, deshacemos los cambios
        mysqli_rollback($conn);
        $error = true;
        $missatge = "Error en intentar esborrar el registre: " . mysqli_error($conn);
    }
} else {
    // Si no hay ID, redirigimos al listado de animals
    header("Location: animals.php");
    exit();
}
?>

<section>
    <h2>Gestió de Baixa d'Animal</h2>

    <!-- Mensaje de feedback con los colores amigables definidos en el CSS -->
    <div style="padding: 20px; border-radius: 8px; background-color: <?php echo $error ? '#f8d7da' : '#d4edda'; ?>; color: <?php echo $error ? '#721c24' : '#155724'; ?>; border: 1px solid <?php echo $error ? '#f5c6cb' : '#c3e6cb'; ?>; margin-bottom: 20px;">
        <p><strong><?php echo $error ? 'Error:' : 'Èxit:'; ?></strong> <?php echo $missatge; ?></p>
    </div>

    <a href="animals.php" class="btn" style="background-color: #5dade2;">Tornar al llistat d'animals</a>
</section>

<?php 
include 'footer.php'; 
?>