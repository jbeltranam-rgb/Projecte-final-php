<?php
// Incluimos la conexión y la cabecera reutilizando los archivos base [3, 4]
include 'db.php';
include 'header.php';

// Consulta SQL con JOIN para obtener los datos del animal y el nombre del propietario [1]
$sql = "SELECT a.*, p.nom as nom_propietari 
        FROM Animals a 
        JOIN Propietaris p ON a.id_propietari = p.id";
$result = mysqli_query($conn, $sql);
?>

<section>
    <h2>Llistat d'Animals</h2>
    
    <!-- Botón para añadir un nuevo animal (Tasca 5) [1, 5] -->
    <a href="nou_animal.php" class="btn btn-add">+ Nou Animal</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Espècie</th>
                <th>Raça</th>
                <th>Data Naixement</th>
                <th>Propietari</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Patrón de visualización mediante bucle while(fetch_assoc) [4]
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    // Enlace opcional a la página de detalle (Tasca 6) [1]
                    echo "<td><a href='detall_animal.php?id=" . $row['id'] . "'>" . $row['nom'] . "</a></td>";
                    echo "<td>" . $row['especie'] . "</td>";
                    echo "<td>" . $row['raca'] . "</td>";
                    echo "<td>" . $row['data_naixement'] . "</td>";
                    echo "<td>" . $row['nom_propietari'] . "</td>";
                    echo "<td>
                            <a href='editar_animal.php?id=" . $row['id'] . "' class='btn btn-edit'>Editar</a>
                            <a href='borrar_animal.php?id=" . $row['id'] . "' class='btn btn-delete' onclick='return confirm(\"Estàs segur d’esborrar l’animal i totes les seves visites?\")'>Esborrar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hi ha animals registrats.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<?php
// Incluimos el pie de página común [3]
include 'footer.php';
?>