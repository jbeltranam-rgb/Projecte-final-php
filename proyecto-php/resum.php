<?php
include 'db.php';
include 'header.php';

// --- CONSULTAS (Lògica de dades) ---
// 1. Mètriques ràpides
$total_prop = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Propietaris"))['t'];
$total_anim = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Animals"))['t'];
$total_vis  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM Visites"))['t'];

// 2. Ingressos del mes (Tasca 7)
$sql_ing = "SELECT SUM(preu) as total FROM Visites WHERE MONTH(data_visita) = MONTH(CURRENT_DATE()) AND YEAR(data_visita) = YEAR(CURRENT_DATE())";
$ing_mes = mysqli_fetch_assoc(mysqli_query($conn, $sql_ing))['total'] ?? 0;

// 3. Rànquings (TOP 3)
$top_visites = mysqli_query($conn, "SELECT a.nom, COUNT(v.id) as qty FROM Animals a JOIN Visites v ON a.id = v.id_animal GROUP BY a.id ORDER BY qty DESC LIMIT 3");
$top_costos = mysqli_query($conn, "SELECT a.nom, SUM(v.preu) as total FROM Animals a JOIN Visites v ON a.id = v.id_animal GROUP BY a.id ORDER BY total DESC LIMIT 3");

// 4. Activitat Recent (Últimes 5 visites)
$ultimes_vis = mysqli_query($conn, "SELECT v.*, a.nom as animal FROM Visites v JOIN Animals a ON v.id_animal = a.id ORDER BY v.data_visita DESC LIMIT 5");
?>

<div class="dashboard-container">
    <h2 style="margin-bottom: 30px; font-weight: 700;">Panell de Control Veterinari</h2>

    <!-- FILA 1: Mètriques Clau -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card-metric" style="background: rgba(0, 113, 227, 0.05); border-left: 5px solid #0071e3; padding: 20px; border-radius: 15px;">
            <span style="color: #86868b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Propietaris</span>
            <div style="font-size: 2rem; font-weight: 700;"><?php echo $total_prop; ?></div>
        </div>
        <div class="card-metric" style="background: rgba(40, 205, 65, 0.05); border-left: 5px solid #28cd41; padding: 20px; border-radius: 15px;">
            <span style="color: #86868b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Animals</span>
            <div style="font-size: 2rem; font-weight: 700;"><?php echo $total_anim; ?></div>
        </div>
        <div class="card-metric" style="background: rgba(255, 159, 10, 0.05); border-left: 5px solid #ff9f0a; padding: 20px; border-radius: 15px;">
            <span style="color: #86868b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Visites Totals</span>
            <div style="font-size: 2rem; font-weight: 700;"><?php echo $total_vis; ?></div>
        </div>
        <div class="card-metric" style="background: #1d1d1f; color: white; padding: 20px; border-radius: 15px;">
            <span style="color: #86868b; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Facturació Mes</span>
            <div style="font-size: 1.8rem; font-weight: 700; color: #28cd41;"><?php echo number_format($ing_mes, 2); ?> €</div>
        </div>
    </div>

    <!-- FILA 2: Rànquings i Insights -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;"> Pacients més Actius</h4>
            <?php $i = 1; while($row = mysqli_fetch_assoc($top_visites)): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f5f5f7;">
                    <span><small style="color: #86868b;"><?php echo $i++; ?>.</small> <strong><?php echo $row['nom']; ?></strong></span>
                    <span class="btn" style="background: #f5f5f7; color: #1d1d1f;"><?php echo $row['qty']; ?> visites</span>
                </div>
            <?php endwhile; ?>
        </div>

        <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
            <h4 style="margin-top:0;"> Pacients que han gastat mes (Cost)</h4>
            <?php $i = 1; while($row = mysqli_fetch_assoc($top_costos)): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f5f5f7;">
                    <span><small style="color: #86868b;"><?php echo $i++; ?>.</small> <strong><?php echo $row['nom']; ?></strong></span>
                    <span style="font-weight: 600; color: #0071e3;"><?php echo number_format($row['total'], 2); ?> €</span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- FILA 3: Activitat Recent -->
    <div style="background: rgba(255,255,255,0.5); padding: 25px; border-radius: 20px; border: 1px solid var(--glass-border);">
        <h4 style="margin-top:0;">Activitat Recent (Últimes Visites)</h4>
        <table style="margin-top: 10px;">
            <thead>
                <tr style="font-size: 0.8rem; color: #86868b;">
                    <th>DATA</th>
                    <th>PACIENT</th>
                    <th>MOTIU</th>
                    <th style="text-align: right;">IMPORT</th>
                </tr>
            </thead>
            <tbody>
                <?php while($v = mysqli_fetch_assoc($ultimes_vis)): ?>
                <tr>
                    <td style="font-size: 0.85rem;"><?php echo $v['data_visita']; ?></td>
                    <td><strong><?php echo $v['animal']; ?></strong></td>
                    <td style="color: #515154; font-style: italic;"><?php echo $v['motiu']; ?></td>
                    <td style="text-align: right; font-weight: 600;"><?php echo number_format($v['preu'], 2); ?> €</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
