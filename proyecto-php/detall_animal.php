<?php
include 'db.php';
include 'header.php';

$missatge = "";
$id_animal = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if (!$id_animal) {
    header("Location: animals.php");
    exit();
}

// 1. LÓGICA: Procesar ALTA de nueva visita (Tasca 6)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['afegir_visita'])) {
    $data_visita = mysqli_real_escape_string($conn, $_POST['data_visita']);
    $motiu = mysqli_real_escape_string($conn, $_POST['motiu']);
    $diagnostic = mysqli_real_escape_string($conn, $_POST['diagnostic']);
    $preu = mysqli_real_escape_string($conn, $_POST['preu']);

    $sql_ins_visita = "INSERT INTO Visites (data_visita, motiu, diagnostic, preu, id_animal) 
                       VALUES ('$data_visita', '$motiu', '$diagnostic', '$preu', '$id_animal')";
    
    if (mysqli_query($conn, $sql_ins_visita)) {
        $missatge = "<p style='color: green;'>Visita afegida correctament.</p>";
    } else {
        $missatge = "<p style='color: red;'>Error en afegir visita: " . mysqli_error($conn) . "</p>";
    }
}

// 2. LÓGICA: Procesar BORRADO de visita (Tasca 6)
if (isset($_GET['borrar_visita'])) {
    $id_visita = mysqli_real_escape_string($conn, $_GET['borrar_visita']);
    $sql_del_visita = "DELETE FROM Visites WHERE id = $id_visita";
    if (mysqli_query($conn, $sql_del_visita)) {
        $missatge = "<p style='color: orange;'>Visita eliminada.</p>";
    }
}

// 3. CONSULTA: Datos del animal y su propietario
$sql_animal = "SELECT a.*, p.nom as nom_propietari FROM Animals a 
               JOIN Propietaris p ON a.id_propietari = p.id 
               WHERE a.id = $id_animal";
$res_animal = mysqli_query($conn, $sql_animal);
$animal = mysqli_fetch_assoc($res_animal);

// 4. CONSULTA: Listado de visitas
$sql_visites = "SELECT * FROM Visites WHERE id_animal = $id_animal ORDER BY data_visita DESC";
$res_visites = mysqli_query($conn, $sql_visites);
?>

<section>
    <!-- Cabecera con datos del animal y propietario -->
    <h2>Fitxa de l'animal: <?php echo $animal['nom']; ?></h2>
    <div style="background: #f1f9f4; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <p><strong>Espècie:</strong> <?php echo $animal['especie']; ?> | <strong>Raça:</strong> <?php echo $animal['raca']; ?></p>
        <p><strong>Propietari:</strong> <?php echo $animal['nom_propietari']; ?></p>
    </div>

    <?php echo $missatge; ?>

    <!-- Tabla de visitas -->
    <h3>Historial de Visites</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Motiu</th>
                <th>Diagnòstic</th>
                <th>Preu</th>
                <th>Acció</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_gastat = 0;
            while ($v = mysqli_fetch_assoc($res_visites)) {
                $total_gastat += $v['preu'];
                echo "<tr>";
                echo "<td>" . $v['data_visita'] . "</td>";
                echo "<td>" . $v['motiu'] . "</td>";
                echo "<td>" . $v['diagnostic'] . "</td>";
                echo "<td>" . number_format($v['preu'], 2) . " €</td>";
                echo "<td><a href='detall_animal.php?id=$id_animal&borrar_visita=" . $v['id'] . "' class='btn btn-delete' style='padding: 2px 8px;'>X</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right;">Total Gastat:</th>
                <th colspan="2"><?php echo number_format($total_gastat, 2); ?> €</th>
            </tr>
        </tfoot>
    </table>

    <!-- Formulario para añadir nueva visita -->
    <h3 style="margin-top: 40px;">Registrar Nova Visita</h3>
    <form action="detall_animal.php?id=<?php echo $id_animal; ?>" method="POST">
        <input type="hidden" name="afegir_visita" value="1">
        
        <label for="data_visita">Data de la visita:</label>
        <input type="date" name="data_visita" id="data_visita" required value="<?php echo date('Y-m-d'); ?>">

        <label for="motiu">Motiu:</label>
        <input type="text" name="motiu" id="motiu" required>

        <label for="diagnostic">Diagnòstic:</label>
        <textarea name="diagnostic" id="diagnostic" rows="3"></textarea>

        <label for="preu">Preu (€):</label>
        <input type="number" step="0.01" name="preu" id="preu" required>

        <button type="submit" class="btn btn-add" style="margin-top: 15px;">Afegir Visita</button>
    </form>

    <br>
    <a href="animals.php" class="btn" style="background-color: #95a5a6;">Tornar al llistat</a>
</section>

<?php include 'footer.php'; ?>