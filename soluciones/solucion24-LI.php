<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'B',
    'p2' => 'C',
    'p3' => 'A',
    'p4' => 'D',
    'p5' => 'C',
    'p6' => 'A',
    'p7' => 'A',
    'p8' => 'A',
    'p9' => 'D',
    'p10' => 'A',
    'p11' => 'D',
    'p12' => 'C',
    'p13' => 'C',
    'p14' => 'B',
    'p15' => 'C',
    'p16' => 'B',
    'p17' => 'C',
    'p18' => 'D',
    'p19' => 'B',
    'p20' => 'C',
    'p21' => 'D',
    'p23' => 'C',
    'p24' => 'D',
    'p25' => 'D',
    'p26' => 'B',
    'p27' => 'D',
    'p28' => 'B',
    'p29' => 'C',
    'p30' => 'C',
    'p31' => 'B',
    'p32' => 'C',
    'p33' => 'A',
    'p34' => 'C',
    'p35' => 'C',
    'p36' => 'D',
    'p37' => 'D',
    'p38' => 'C',
    'p39' => 'D',
    'p40' => 'A',
    'p41' => 'D',
    'p42' => 'D',
    'p43' => 'A',
    'p44' => 'A',
    'p45' => 'C',
    'p46' => 'C',
    'p47' => 'A',
    'p48' => 'D',
    'p49' => 'B',
    'p50' => 'B',
    'p52' => 'D',
    'p53' => 'B',
    'p54' => 'B',
    'p55' => 'D',
    'p56' => 'A',
    'p57' => 'C',
    'p58' => 'C',
    'p59' => 'A',
    'p60' => 'A',
    'p61' => 'C',
    'p62' => 'C',
    'p63' => 'A',
    'p64' => 'A',
    'p65' => 'D',
    'p66' => 'A',
    'p67' => 'C',
    'p68' => 'A',
    'p69' => 'D',
    'p71' => 'B',
    'p72' => 'B',
    'p73' => 'D',
    'p74' => 'B',
    'p75' => 'B',
    'p76' => 'A',
    'p77' => 'A',
    'p78' => 'C',
    'p79' => 'D',
    'p80' => 'B',

    'pr1' => 'C',
    'pr2' => 'A',
    'pr3' => 'A',
    'pr4' => 'A',
    'pr5' => 'C',

    'sp1' => 'C',
    'sp2' => 'C',
    'sp3' => 'D',
    'sp4' => 'A',
    'sp5' => 'A',
    'sp6' => 'D',
    'sp7' => 'C',
    'sp8' => 'D',
    'sp9' => 'A',
    'sp10' => 'D',
    'sp11' => 'A',
    'sp12' => 'B',
    'sp13' => 'A',
    'sp14' => 'D',
    'sp15' => 'A',
    'sp16' => 'A',
    'sp17' => 'C',
    'sp18' => 'B',
    'sp19' => 'D',
    'sp20' => 'A',

    'spr1' => 'B',
    'spr2' => 'A',
    'spr3' => 'C',
    'spr4' => 'D',
    'spr5' => 'B',
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