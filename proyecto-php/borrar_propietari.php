<?php
// Incluimos la conexión y la cabecera
include 'db.php';
include 'header.php';

$missatge = "";
$error = false;

// Verificamos si se ha recibido un ID válido por la URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. VALIDACIÓN: Comprobar si el propietario tiene animales asignados
    $sql_check = "SELECT COUNT(*) as total FROM Animals WHERE id_propietari = $id";
    $result_check = mysqli_query($conn, $sql_check);
    $data = mysqli_fetch_assoc($result_check);

    if ($data['total'] > 0) {
        // Si tiene animales, bloqueamos el borrado y preparamos el mensaje de error
        $error = true;
        $missatge = "No es pot esborrar el propietari perquè encara té " . $data['total'] . " animals associats. Primer has de gestionar els seus animals.";
    } else {
        // 2. ACCIÓN: Si no tiene animales, procedemos a borrar el registro
        $sql_delete = "DELETE FROM Propietaris WHERE id = $id";
        
        if (mysqli_query($conn, $sql_delete)) {
            $missatge = "Propietari esborrat correctament.";
        } else {
            $error = true;
            $missatge = "Error en intentar esborrar el registre: " . mysqli_error($conn);
        }
    }
} else {
    // Si no hay ID, redirigimos al listado
    header("Location: propietaris.php");
    exit();
}
?>

<section>
    <h2>Gestió de Baixa de Propietari</h2>

    <div style="padding: 20px; border-radius: 8px; background-color: <?php echo $error ? '#f8d7da' : '#d4edda'; ?>; color: <?php echo $error ? '#721c24' : '#155724'; ?>; border: 1px solid <?php echo $error ? '#f5c6cb' : '#c3e6cb'; ?>; margin-bottom: 20px;">
        <p><strong><?php echo $error ? 'Atenció:' : 'Èxit:'; ?></strong> <?php echo $missatge; ?></p>
    </div>

    <a href="propietaris.php" class="btn" style="background-color: #5dade2;">Tornar al llistat de propietaris</a>
</section>

<?php 
include 'footer.php'; 
?>