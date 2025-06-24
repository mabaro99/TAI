<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'A',
    'p2' => 'C',
    'p3' => 'C',
    'p4' => 'D',
    'p5' => 'C',
    'p6' => 'A',
    'p7' => 'C',
    'p8' => 'B',
    'p9' => 'A',
    'p10' => 'B',
    'p11' => 'B',
    'p12' => 'D',
    'p13' => 'C',
    'p14' => 'D',
    'p15' => 'B',
    'p16' => 'C',
    'p17' => 'C',
    'p18' => 'C',
    'p19' => 'A',
    'p20' => 'D',
    'p21' => 'C',
    'p22' => 'B',
    'p23' => 'D',
    'p24' => 'D',
    'p25' => 'B',
    'p26' => 'D',
    'p27' => 'C',
    'p28' => 'C',
    'p29' => 'A',
    'p30' => 'B',
    'p31' => 'B',
    'p32' => 'D',
    'p33' => 'A',
    'p34' => 'A',
    'p35' => 'A',
    'p36' => 'A',
    'p37' => 'C',
    'p38' => 'C',
    'p39' => 'A',
    'p40' => 'D',
    'p41' => 'C',
    'p42' => 'D',
    'p43' => 'A',
    'p44' => 'C',
    'p45' => 'D',
    'p46' => 'B',
    'p47' => 'B',
    'p48' => 'A',
    'p49' => 'C',
    'p50' => 'D',
    'p51' => 'C',
    'p52' => 'A',
    'p53' => 'C',
    'p54' => 'D',
    'p55' => 'B',
    'p56' => 'C',
    'p57' => 'D',
    'p58' => 'B',
    'p59' => 'A',
    'p60' => 'D',
    'p61' => 'D',
    'p62' => 'D',
    'p63' => 'A',
    'p64' => 'A',
    'p65' => 'D',
    'p66' => 'B',
    'p67' => 'A',
    'p68' => 'C',
    'p69' => 'A',
    'p70' => 'D',
    'p71' => 'A',
    'p72' => 'D',
    'p73' => 'B',
    'p74' => 'B',
    'p75' => 'B',
    'p76' => 'D',
    'p77' => 'D',
    'p78' => 'B',
    'p79' => 'A',
    'p80' => 'D',
    'p81' => 'C',
    'p82' => 'C',
    'p83' => 'C',
    'p84' => 'B',
    'p85' => 'A',
    'p86' => 'A',
    'p87' => 'A',
    'p88' => 'A',
    'p89' => 'C',
    'p90' => 'D',
    'p91' => 'B',
    'p92' => 'D',
    'p93' => 'A',
    'p94' => 'B',
    'p95' => 'C',
    'p96' => 'C',
    'p97' => 'B',
    'p98' => 'B',
    'p99' => 'A',
    'p100' => 'A',

    'pr1' => 'B',
    'pr2' => 'A',
    'pr3' => 'A',
    'pr4' => 'C',
    'pr5' => 'C',
];

// Definir los grupos
$grupos = [
    'Primera Parte' => array_filter($respuestas_correctas, fn($_, $k) => preg_match('/^p\d+$/', $k), ARRAY_FILTER_USE_BOTH),
    'Reserva 1º Parte' => array_filter($respuestas_correctas, fn($_, $k) => preg_match('/^pr\d+$/', $k), ARRAY_FILTER_USE_BOTH),
];

$resumenes = [];
$detalles = "";
$total_global = $acertadas_global = $falladas_global = $no_respondidas_global = 0;

// Procesar cada grupo
foreach ($grupos as $nombre_grupo => $grupo) {
    $acertadas = $falladas = $no_respondidas = 0;
    $total = count($grupo);
    $detalle_grupo = "<h3>Detalles $nombre_grupo</h3>";

    if ($nombre_grupo === 'Primera Parte') {
        // Dividir preguntas en 4 columnas de 20 consecutivas
        $columnas = [[], [], [], []];
        $i = 0;

        foreach ($grupo as $pregunta => $respuesta_correcta) {
            $respuesta_usuario = $_POST[$pregunta] ?? 'Sin responder';

            if (!isset($_POST[$pregunta])) {
                $no_respondidas++;
            } elseif ($_POST[$pregunta] === $respuesta_correcta) {
                $acertadas++;
            } else {
                $falladas++;
            }

            if ($respuesta_usuario === 'Sin responder') {
                $estado = "⚠️ No respondida";
                $clase = "no-respondida";
            } elseif ($respuesta_usuario === $respuesta_correcta) {
                $estado = "✔ Correcta";
                $clase = "correcta";
            } else {
                $estado = "✘ Incorrecta";
                $clase = "incorrecta";
            }

            $texto = "<p class='$clase'><strong>$pregunta:</strong>  $respuesta_usuario |  $respuesta_correcta → $estado</p>";

            // Calcular columna: p1-p20 (0), p21-p40 (1), p41-p60 (2), p61-p80 (3)
            $columna_idx = intdiv($i, 20); // 0 a 3
            $columnas[$columna_idx][] = $texto;
            $i++;
        }

        // Renderizar columnas
        $detalle_grupo .= "<div class='grid-bloques'>";
        foreach ($columnas as $bloque) {
            $detalle_grupo .= "<div class='bloque-columna'>" . implode("", $bloque) . "</div>";
        }
        $detalle_grupo .= "</div>";

    } else {
        // Grupos restantes se muestran normal, lista vertical
        foreach ($grupo as $pregunta => $respuesta_correcta) {
            $respuesta_usuario = $_POST[$pregunta] ?? 'Sin responder';

            if (!isset($_POST[$pregunta])) {
                $no_respondidas++;
            } elseif ($_POST[$pregunta] === $respuesta_correcta) {
                $acertadas++;
            } else {
                $falladas++;
            }

            if ($respuesta_usuario === 'Sin responder') {
                $estado = "⚠️ No respondida";
                $clase = "no-respondida";
            } elseif ($respuesta_usuario === $respuesta_correcta) {
                $estado = "✔ Correcta";
                $clase = "correcta";
            } else {
                $estado = "✘ Incorrecta";
                $clase = "incorrecta";
            }

            $detalle_grupo .= "<p class='$clase'><strong>$pregunta:</strong> $respuesta_usuario |  $respuesta_correcta → $estado</p>";
        }
    }

    $resumenes[] = [
        'titulo' => $nombre_grupo,
        'acertadas' => $acertadas,
        'falladas' => $falladas,
        'no_respondidas' => $no_respondidas,
        'total' => $total,
    ];

    $acertadas_global += $acertadas;
    $falladas_global += $falladas;
    $no_respondidas_global += $no_respondidas;
    $total_global += $total;

    $detalles .= $detalle_grupo . "<hr>";
}

// Mostrar resumenes
echo "<div class='resumenes'>";



// Resumenes por grupo
foreach ($resumenes as $resumen) {
    echo "<div class='resumen-box'>";
    echo "<h3>{$resumen['titulo']}</h3>";
    echo "✅ Correctas: {$resumen['acertadas']}<br>";
    echo "❌ Incorrectas: {$resumen['falladas']}<br>";
    echo "⚠️ Sin responder: {$resumen['no_respondidas']}<br>";
    echo "🎯 Puntaje: {$resumen['acertadas']} / {$resumen['total']}<br>";
    echo "</div>";
}

// Resumen general
echo "<div class='resumen-box'>";
echo "<h3>Resumen General</h3>";
echo "✅ Correctas: $acertadas_global<br>";
echo "❌ Incorrectas: $falladas_global<br>";
echo "⚠️ Sin responder: $no_respondidas_global<br>";
echo "🎯 Puntaje Total: $acertadas_global / $total_global<br>";
echo "</div>";

echo "<button type='button' onclick=\"window.location.href='../index.php';\" class='button_slide slide_right'>Volver a Inicio</button>";

echo "</div><hr>";

// Mostrar detalles
echo "<h2>Detalles por Pregunta</h2>";
echo $detalles;
?>