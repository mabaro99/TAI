<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'D',
    'p2' => 'D',
    'p3' => 'A',
    'p4' => 'A',
    'p5' => 'C',
    'p6' => 'B',
    'p7' => 'B',
    'p8' => 'A',
    'p9' => 'A',
    'p10' => 'A',
    'p11' => 'A',
    'p12' => 'C',
    'p13' => 'D',
    'p14' => 'C',
    'p15' => 'B',
    'p16' => 'A',
    'p17' => 'B',
    'p18' => 'B',
    'p19' => 'B',
    'p20' => 'A',
    'p21' => 'D',
    'p22' => 'C',

    'p24' => 'C',
    'p25' => 'A',
    'p26' => 'C',
    'p27' => 'B',
    'p28' => 'A',
    'p29' => 'C',
    'p30' => 'D',
    'p31' => 'C',
    'p32' => 'A',
    'p33' => 'D',
    'p34' => 'C',
    'p35' => 'A',
    'p36' => 'A',
    'p37' => 'C',
    'p38' => 'B',
    'p39' => 'C',
    'p40' => 'B',
    'p41' => 'A',
    'p42' => 'C',
    'p43' => 'C',
    'p44' => 'C',
    'p45' => 'A',
    'p46' => 'B',
    'p47' => 'D',
    'p48' => 'D',
    'p49' => 'C',
    'p50' => 'D',
    'p51' => 'A',
    'p52' => 'B',
    'p53' => 'C',
    'p54' => 'D',
    'p55' => 'C',
    'p56' => 'B',
    'p57' => 'A',
    'p58' => 'C',
    'p59' => 'B',
    'p60' => 'A',
    'p61' => 'B',
    'p62' => 'D',
    'p63' => 'C',
    'p64' => 'A',
    'p65' => 'D',
    'p66' => 'A',
    'p67' => 'A',
    'p68' => 'B',
    'p69' => 'C',
    'p70' => 'A',
    'p71' => 'A',
    'p72' => 'B',
    'p73' => 'D',
    'p74' => 'A',
    'p75' => 'D',
    'p76' => 'D',
    'p77' => 'C',
    'p78' => 'A',
    'p79' => 'C',
    'p80' => 'D',

    'pr1' => 'C',
    'pr2' => 'A',
    'pr3' => 'A',
    'pr4' => 'C',
    'pr5' => 'D',

    'sp1' => 'A',
    'sp2' => 'D',
    'sp3' => 'A',
    'sp4' => 'D',
    'sp5' => 'D',
    'sp6' => 'D',
    'sp7' => 'A',
    'sp8' => 'C',
    'sp9' => 'B',
    'sp10' => 'C',
    'sp11' => 'B',
    'sp12' => 'D',
    'sp13' => 'C',
    'sp14' => 'C',
    'sp15' => 'B',
    'sp16' => 'D',
    'sp17' => 'A',
    'sp18' => 'B',
    'sp19' => 'D',
    'sp20' => 'D',

    'spr1' => 'C',
    'spr2' => 'B',
    'spr3' => 'A',
    'spr4' => 'A',
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