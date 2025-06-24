<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'D',
    'p2' => 'A',
    'p3' => 'B',
    'p4' => 'B',
    'p5' => 'A',
    'p6' => 'C',
    'p7' => 'B',
    'p8' => 'C',
    'p9' => 'B',
    'p10' => 'C',
    'p11' => 'D',
    'p12' => 'B',
    'p13' => 'A',
    'p14' => 'C',
    'p15' => 'D',
    'p16' => 'D',
    'p17' => 'B',
    'p18' => 'B',
    'p19' => 'B',
    'p20' => 'A',
    'p21' => 'D',
    'p22' => 'C',
    'p23' => 'B',
    'p24' => 'C',
    'p25' => 'A',
    'p26' => 'C',
    'p27' => 'C',
    'p28' => 'C',
    'p29' => 'C',
    'p30' => 'A',
    'p31' => 'D',
    'p32' => 'C',
    'p33' => 'B',
    'p34' => 'C',
    'p35' => 'B',
    'p36' => 'B',
    'p37' => 'B',
    'p38' => 'C',
    'p39' => 'A',
    'p40' => 'A',
    'p41' => 'C',
    'p42' => 'D',
    'p43' => 'A',
    'p44' => 'A',
    'p45' => 'C',
    'p46' => 'D',
    'p47' => 'B',
    'p48' => 'B',
    'p49' => 'D',
    'p50' => 'C',
    'p51' => 'D',
    'p52' => 'C',
    'p53' => 'C',
    'p54' => 'B',
    'p55' => 'C',
    'p56' => 'D',
    'p57' => 'C',
    'p58' => 'C',
    'p59' => 'A',
    'p60' => 'B',
    'p61' => 'D',
    'p62' => 'C',
    'p63' => 'A',
    'p64' => 'C',
    'p65' => 'B',
    'p66' => 'B',
    'p67' => 'C',
    'p68' => 'B',
    'p69' => 'B',
    'p70' => 'B',
    'p71' => 'A',
    'p72' => 'B',
    'p73' => 'B',
    'p74' => 'D',
    'p75' => 'D',
    'p76' => 'B',
    'p77' => 'A',
    'p78' => 'D',
    'p79' => 'C',
    'p80' => 'B',
    'pr1' => 'B',
    'pr2' => 'A',
    'pr3' => 'C',
    'pr4' => 'D',
    'pr5' => 'B',
    'sp1' => 'A',
    'sp2' => 'A',
    'sp3' => 'A',
    'sp4' => 'B',
    'sp5' => 'A',
    'sp6' => 'C',
    'sp7' => 'A',
    'sp8' => 'B',
    'sp9' => 'B',
    'sp10' => 'A',
    'sp11' => 'C',
    'sp12' => 'B',
    'sp13' => 'A',
    'sp14' => 'C',
    'sp15' => 'D',
    'sp16' => 'B',
    'sp17' => 'D',
    'sp18' => 'B',
    'sp19' => 'C',
    'sp20' => 'D',
    'spr1' => 'A',
    'spr2' => 'A',
    'spr3' => 'A',
    'spr4' => 'C',
    'spr5' => 'D',
];

// Definir los grupos
$grupos = [
    'Primera Parte' => array_filter($respuestas_correctas, fn($_, $k) => preg_match('/^p\d+$/', $k), ARRAY_FILTER_USE_BOTH),
    'Reserva 1º Parte' => array_filter($respuestas_correctas, fn($_, $k) => preg_match('/^pr\d+$/', $k), ARRAY_FILTER_USE_BOTH),
    'Supuesto Practico' => array_filter($respuestas_correctas, fn($_, $k) => preg_match('/^sp\d+$/', $k), ARRAY_FILTER_USE_BOTH),
    'Reserva Supuesto Practico' => array_filter($respuestas_correctas, fn($_, $k) => preg_match('/^spr\d+$/', $k), ARRAY_FILTER_USE_BOTH),
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