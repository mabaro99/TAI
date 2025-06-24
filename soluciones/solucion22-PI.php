<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'A',
    'p2' => 'A',
    'p3' => 'D',
    'p4' => 'A',
    'p5' => 'B',
    'p6' => 'B',
    'p7' => 'D',
    'p8' => 'A',
    'p9' => 'C',
    'p10' => 'D',
    'p11' => 'D',
    'p12' => 'B',
    'p13' => 'A',
    'p14' => 'D',
    'p15' => 'C',
    'p16' => 'D',
    'p17' => 'A',
    'p18' => 'C',
    'p19' => 'C',
    'p20' => 'A',
    'p21' => 'B',
    'p22' => 'B',
    'p23' => 'B',
    'p24' => 'B',
    'p25' => 'C',
    'p26' => 'C',
    'p27' => 'D',
    'p28' => 'A',
    'p29' => 'A',
    'p30' => 'D',
    'p31' => 'C',
    'p32' => 'A',
    'p33' => 'D',
    'p34' => 'A',
    'p35' => 'D',
    'p36' => 'B',
    'p37' => 'C',
    'p38' => 'C',
    'p39' => 'B',
    'p40' => 'D',
    'p41' => 'D',
    'p42' => 'D',
    'p43' => 'C',
    'p44' => 'C',
    'p45' => 'C',
    'p46' => 'C',
    'p47' => 'C',
    'p48' => 'A',
    'p49' => 'C',
    'p50' => 'A',
    
    'pr1' => 'C',
    'pr2' => 'A',
    'pr3' => 'A',
    'pr4' => 'D',
    'pr5' => 'A',

    'sp1' => 'C',
    'sp2' => 'C',
    'sp3' => 'D',
    'sp4' => 'B',
    'sp5' => 'B',
    'sp6' => 'A',
    'sp7' => 'C',
    'sp8' => 'B',
    'sp9' => 'A',
    'sp10' => 'C',
    'sp11' => 'D',
    'sp12' => 'C',

    'spr1' => 'D',
    'spr2' => 'A',
    'spr3' => 'B',
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