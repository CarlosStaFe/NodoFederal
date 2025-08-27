<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h1 { color: #2c3e50; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Informe PDF de Operaciones</h1>
    <div class="section">
        <span class="label">Usuario:</span> {{ Auth::user()->name ?? '' }}<br>
        <span class="label">Email:</span> {{ Auth::user()->email ?? '' }}<br>
        <span class="label">Fecha:</span> {{ date('d/m/Y H:i') }}
    </div>
    <div class="section">
        <span class="label">Contenido de ejemplo:</span>
        <p>Este es un PDF generado desde Laravel DomPDF. Personaliza este archivo para mostrar los datos que necesites.</p>
    </div>
</body>
</html>
