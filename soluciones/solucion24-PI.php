<link rel="stylesheet" href="estilos.css">

<?php
// Respuestas correctas (clave = número de pregunta, valor = respuesta correcta)
$respuestas_correctas = [
    'p1' => 'C',
    'p2' => 'A',
    'p3' => 'A',
    'p4' => 'C',
    'p5' => 'C',
    'p6' => 'B',
    'p7' => 'D',
    'p8' => 'A',
    'p9' => 'B',
    'p10' => 'B',
    'p11' => 'B',
    'p12' => 'A',
    'p13' => 'B',
    'p14' => 'D',
    'p15' => 'C',
    'p16' => 'D',
    'p17' => 'C',
    'p18' => 'B',
    'p19' => 'A',
    'p20' => 'B',
    'p21' => 'A',
    'p22' => 'A',
    'p23' => 'A',
    'p24' => 'D',
    'p25' => 'A',
    'p26' => 'A',
    'p27' => 'C',
    'p28' => 'D',
    'p29' => 'D',
    'p30' => 'D',
    'p31' => 'D',
    'p32' => 'C',
    'p33' => 'C',
    'p34' => 'D',
    'p35' => 'A',
    'p36' => 'C',
    'p37' => 'C',
    'p38' => 'A',
    'p39' => 'C',
    'p40' => 'C',
    'p41' => 'A',
    'p42' => 'A',
    'p43' => 'D',
    'p44' => 'B',
    'p45' => 'B',
    'p46' => 'A',
    'p47' => 'A',
    'p48' => 'B',
    'p49' => 'B',
    'p50' => 'C',
    
    'pr1' => 'C',
    'pr2' => 'A',
    'pr3' => 'B',
    'pr4' => 'B',
    'pr5' => 'D',

    'sp1' => 'B',
    'sp2' => 'A',
    'sp3' => 'A',
    'sp4' => 'B',
    'sp5' => 'D',
    'sp6' => 'A',
    'sp7' => 'B',
    'sp8' => 'A',
    'sp9' => 'B',
    'sp10' => 'D',
    'sp11' => 'C',
    'sp12' => 'A',

    'spr1' => 'A',
    'spr2' => 'C',
    'spr3' => 'A',
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