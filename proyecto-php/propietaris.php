<?php
// Incluimos la conexión y la cabecera
include 'db.php';
include 'header.php';

// Consulta para obtener todos los propietarios
$sql = "SELECT * FROM Propietaris";
$result = mysqli_query($conn, $sql);
?>

<section>
    <h2>Llistat de Propietaris</h2>
    
    <!-- Botón para añadir un nuevo propietario (Tasca 4) -->
    <a href="nou_propietari.php" class="btn btn-add">+ Nou Propietari</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Telèfon</th>
                <th>Email</th>
                <th>Adreça</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Comprobamos si hay registros
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nom'] . "</td>";
                    echo "<td>" . $row['telefon'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['adreca'] . "</td>";
                    echo "<td>
                            <a href='editar_propietari.php?id=" . $row['id'] . "' class='btn btn-edit'>Editar</a>
                            <a href='borrar_propietari.php?id=" . $row['id'] . "' class='btn btn-delete' onclick='return confirm(\"Estàs segur?\")'>Esborrar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hi ha propietaris registrats.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<?php
// Incluimos el pie de página
include 'footer.php';
?>