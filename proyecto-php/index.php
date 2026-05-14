<?php 
// Incluimos la cabecera que contiene el menú y los estilos
include 'header.php'; 
?>

<section class="welcome-section">
    <h2>Benvingut a la Clínica Veterinària</h2>
    <p>Aquesta aplicació permet gestionar els propietaris, els animals i les seves visites de manera eficient.</p>
    
    <div class="main-actions">
        <p>Selecciona una opció del menú superior per començar:</p>
        <ul>
            <li><strong>Propietaris:</strong> Consulta, afegeix o edita les dades dels clients.</li>
            <li><strong>Animals:</strong> Gestiona les mascotes i les seves visites mèdiques.</li>
            <li><strong>Resum:</strong> Consulta les estadístiques generals de la clínica.</li>
        </ul>
    </div>
</section>

<?php 
// Incluimos el pie de página para cerrar las etiquetas HTML
include 'footer.php'; 
?>