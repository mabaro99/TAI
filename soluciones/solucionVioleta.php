<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'D',
    'p2' => 'C',
    'p3' => 'D',
    'p4' => 'D',
    'p5' => 'B',
    'p6' => 'A',
    'p7' => 'C',
    'p8' => 'D',
    'p9' => 'A',
    'p10' => 'D',
    'p11' => 'C',
    'p12' => 'C',
    'p13' => 'C',
    'p14' => 'D',
    'p15' => 'B',
    'p16' => 'A',
    'p17' => 'A',
    'p18' => 'C',
    'p19' => 'C',
    'p20' => 'D',
    'p21' => 'C',
    'p22' => 'C',
    'p23' => 'B',
    'p24' => 'D',
    'p25' => 'B',
    'p26' => 'D',
    'p27' => 'B',
    'p28' => 'D',
    'p29' => 'C',
    'p30' => 'B',
    'p31' => 'D',
    'p32' => 'D',
    'p33' => 'B',
    'p34' => 'D',
    'p35' => 'A',
    'p36' => 'C',
    'p37' => 'D',
    'p38' => 'A',
    'p39' => 'A',
    'p40' => 'C',
    'p41' => 'B',
    'p42' => 'D',
    'p43' => 'D',
    'p44' => 'A',
    'p46' => 'B',
    'p47' => 'B',
    'p48' => 'A',
    'p49' => 'D',
    'p50' => 'A',
    'p51' => 'B',
    'p52' => 'A',
    'p53' => 'C',
    'p54' => 'A',
    'p55' => 'B',
    'p56' => 'D',
    'p57' => 'A',
    'p58' => 'A',
    'p59' => 'D',
    'p60' => 'D',
    'p61' => 'C',
    'p62' => 'A',
    'p63' => 'B',
    'p64' => 'B',
    'p65' => 'A',
    'p66' => 'B',
    'p67' => 'C',
    'p68' => 'C',
    'p69' => 'D',
    'p70' => 'D',
    'p71' => 'D',
    'p72' => 'B',
    'p73' => 'A',
    'p74' => 'B',
    'p75' => 'D',
    'p76' => 'B',
    'p77' => 'B',
    'p78' => 'A',
    'p79' => 'D',
    'p80' => 'C',

    'pr1' => 'B',
    'pr2' => 'A',
    'pr3' => 'A',
    'pr4' => 'A',
    'pr5' => 'D',

    'sp1' => 'B',
    'sp2' => 'D',
    'sp3' => 'C',
    'sp4' => 'D',
    'sp5' => 'B',
    'sp6' => 'D',
    'sp7' => 'D',
    'sp8' => 'A',
    'sp9' => 'C',
    'sp10' => 'B',
    'sp11' => 'B',
    'sp12' => 'D',
    'sp13' => 'B',
    'sp14' => 'A',
    'sp15' => 'A',
    'sp16' => 'C',
    'sp17' => 'D',
    'sp18' => 'D',
    'sp19' => 'C',
    'sp20' => 'C',

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