<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'D',
    'p2' => 'D',
    'p3' => 'A',
    'p4' => 'C',
    'p5' => 'D',
    'p6' => 'D',
    'p7' => 'A',
    'p8' => 'D',
    'p9' => 'D',
    'p10' => 'C',
    'p11' => 'A',
    'p12' => 'A',
    'p13' => 'D',
    'p14' => 'A',
    'p15' => 'D',
    'p16' => 'D',
    'p17' => 'B',
    'p18' => 'B',
    'p19' => 'B',
    'p20' => 'A',
    'p21' => 'D',
    'p22' => 'A',
    'p23' => 'B',
    'p24' => 'B',
    'p25' => 'A',
    'p26' => 'D',
    'p27' => 'A',
    'p28' => 'A',
    'p29' => 'C',
    'p30' => 'D',
    'p31' => 'D',
    'p32' => 'C',
    'p33' => 'A',
    'p34' => 'B',
    'p35' => 'B',
    'p36' => 'C',

    'p38' => 'C',
    'p39' => 'C',
    'p40' => 'A',
    'p41' => 'B',
    'p42' => 'B',
    'p43' => 'B',
    'p44' => 'B',

    'p46' => 'A',
    'p47' => 'D',
    'p48' => 'B',
    'p49' => 'D',

    'p51' => 'C',
    'p52' => 'A',
    'p53' => 'C',
    'p54' => 'C',
    'p55' => 'D',
    'p56' => 'B',
    'p57' => 'D',
    'p58' => 'D',
    'p59' => 'D',
    'p60' => 'A',
    'p61' => 'C',
    'p62' => 'D',
    'p63' => 'B',
    'p64' => 'B',
    'p65' => 'D',
    'p66' => 'C',
    'p67' => 'D',
    'p68' => 'B',
    'p69' => 'A',
    'p70' => 'D',
    'p71' => 'A',
    'p72' => 'C',
    'p73' => 'C',
    'p74' => 'C',
    'p75' => 'B',
    'p76' => 'A',
    'p77' => 'D',
    'p78' => 'D',
    'p79' => 'B',

    'pr1' => 'C',
    'pr2' => 'B',
    'pr3' => 'C',
    'pr4' => 'A',
    'pr5' => 'D',
    'sp1' => 'C',
    'sp2' => 'A',
    'sp3' => 'D',
    'sp4' => 'C',
    'sp5' => 'B',
    'sp6' => 'A',
    'sp7' => 'A',
    'sp8' => 'B',
    'sp9' => 'A',
    'sp10' => 'D',
    'sp11' => 'B',
    'sp12' => 'C',
    'sp13' => 'B',
    'sp14' => 'A',

    'sp16' => 'B',
    'sp17' => 'D',
    'sp18' => 'C',
    'sp19' => 'A',
    'sp20' => 'A',

    'spr2' => 'B',
    'spr3' => 'A',
    'spr4' => 'C',
    'spr5' => 'A',
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