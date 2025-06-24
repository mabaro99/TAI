<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'B',
    'p2' => 'C',
    'p3' => 'B',
    'p4' => 'A',
    'p5' => 'A',
    'p6' => 'C',
    'p7' => 'B',
    'p8' => 'D',
    'p9' => 'A',
    'p10' => 'C',
    'p11' => 'C',
    'p12' => 'C',
    'p13' => 'B',
    'p14' => 'D',
    'p15' => 'B',
    'p16' => 'C',
    'p17' => 'C',
    'p18' => 'C',
    'p19' => 'C',
    'p20' => 'C',
    'p21' => 'B',
    'p22' => 'C',
    'p23' => 'C',
    'p24' => 'D',
    'p25' => 'B',
    'p26' => 'B',
    'p27' => 'D',
    'p28' => 'A',
    'p29' => 'A',
    'p30' => 'D',
    'p31' => 'C',
    'p32' => 'B',
    'p33' => 'D',
    'p34' => 'C',
    'p35' => 'B',
    'p36' => 'A',
    'p37' => 'C',
    'p38' => 'C',
    'p39' => 'C',
    'p40' => 'C',
    'p41' => 'B',
    'p42' => 'C',
    'p43' => 'C',
    'p44' => 'A',
    'p45' => 'B',
    'p46' => 'A',
    'p47' => 'C',
    'p48' => 'C',
    'p49' => 'A',
    'p50' => 'A',
    'p51' => 'D',
    'p52' => 'B',
    'p53' => 'A',
    'p54' => 'A',
    'p55' => 'A',
    'p56' => 'C',
    'p57' => 'D',
    'p58' => 'A',
    'p59' => 'A',
    'p60' => 'B',
    'p61' => 'C',
    'p62' => 'B',
    'p63' => 'C',
    'p64' => 'B',
    'p65' => 'B',
    'p66' => 'A',
    'p67' => 'A',
    'p68' => 'A',
    'p69' => 'D',
    'p70' => 'C',
    'p71' => 'A',
    'p72' => 'B',
    'p73' => 'A',
    'p74' => 'B',
    'p75' => 'B',
    'p76' => 'A',
    'p77' => 'D',
    'p78' => 'A',
    'p79' => 'A',
    'p80' => 'C',
    'p81' => 'B',
    'p82' => 'A',
    'p83' => 'A',
    'p84' => 'C',
    'p85' => 'B',
    'p86' => 'B',
    'p87' => 'D',
    'p88' => 'D',
    'p89' => 'A',
    'p90' => 'A',
    'p91' => 'C',
    'p92' => 'C',
    'p93' => 'A',
    'p94' => 'C',
    'p95' => 'C',
    'p96' => 'A',
    'p97' => 'B',
    'p98' => 'D',
    'p99' => 'D',
    'p100' => 'D',

    'pr1' => 'D',
    'pr2' => 'B',
    'pr3' => 'B',
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