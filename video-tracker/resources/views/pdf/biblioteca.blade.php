<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 10pt; margin: 0; padding: 0; }
        .header { background: #7c3aed; color: white; padding: 24px 32px; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 22pt; font-weight: 900; letter-spacing: -1px; }
        .header p { margin: 4px 0 0; font-size: 9pt; opacity: 0.8; }
        .stats { display: flex; gap: 16px; padding: 0 32px; margin-bottom: 24px; }
        .stat { background: #f3f4f6; border-radius: 8px; padding: 12px 20px; flex: 1; text-align: center; }
        .stat .num { font-size: 20pt; font-weight: 900; color: #7c3aed; }
        .stat .lbl { font-size: 7pt; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; }
        .content { padding: 0 32px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #7c3aed; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 8pt; text-transform: uppercase; letter-spacing: 1px; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 7pt; font-weight: 700; text-transform: uppercase; }
        .badge-jugando    { background: #d1fae5; color: #065f46; }
        .badge-completado { background: #ede9fe; color: #5b21b6; }
        .badge-pendiente  { background: #f3f4f6; color: #4b5563; }
        .badge-abandonado { background: #fee2e2; color: #991b1b; }
        .nota-alta { color: #7c3aed; font-weight: 900; }
        .footer { margin-top: 32px; padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 8pt; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VideoTracker — Mi Biblioteca</h1>
        <p>{{ $user->name }} · Exportado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="num">{{ $stats['total'] }}</div>
            <div class="lbl">Total juegos</div>
        </div>
        <div class="stat">
            <div class="num">{{ $stats['completados'] }}</div>
            <div class="lbl">Completados</div>
        </div>
        <div class="stat">
            <div class="num">{{ $stats['jugando'] }}</div>
            <div class="lbl">Jugando</div>
        </div>
        <div class="stat">
            <div class="num">{{ number_format($stats['nota_media'], 1) }}</div>
            <div class="lbl">Nota media</div>
        </div>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Género</th>
                    <th>Plataforma</th>
                    <th>Estado</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videojuegos as $i => $vj)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $vj->game->titulo }}</strong></td>
                    <td>{{ $vj->game->genero }}</td>
                    <td>{{ $vj->plataforma }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($vj->estado) }}">{{ $vj->estado }}</span>
                    </td>
                    <td class="{{ $vj->puntuacion_personal >= 8 ? 'nota-alta' : '' }}">
                        {{ number_format($vj->puntuacion_personal, 1) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        VideoTracker · {{ $videojuegos->count() }} juegos exportados
    </div>
</body>
</html>
