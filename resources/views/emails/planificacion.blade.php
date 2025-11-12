<body style="background-color: #f3f4f6; font-family: Arial, sans-serif; margin:0; padding:0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 20px;">
        <tr>
            <td align="center">
                <!-- Contenedor principal -->
                <table width="400" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    
                    <!-- Saludo -->
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <h1 style="margin:0; font-size: 20px; color: #111827;">Hola {{ $usuario->name }} 👋</h1>
                            <p style="margin: 5px 0 0 0; font-size: 14px; color: #6b7280;">
                                Tu próximo viaje ha sido planificado. Aquí tienes los detalles:
                            </p>
                        </td>
                    </tr>

                    <!-- Destino -->
                    @foreach($planificaciones as $plan)
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #eff6ff; border-radius: 12px; padding: 12px;">
                                <tr>
                                    <td style="font-size: 12px; color: #6b7280;">Destino</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 16px; font-weight: bold; color: #111827;">{{ $plan->destino }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; color: #6b7280;">Aeropuerto Internacional</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Fecha y Hora -->
                    <tr>
                        <td style="padding-bottom: 15px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <!-- Fecha -->
                                    <td width="50%" style="background-color: #d1fae5; border-radius: 12px; padding: 10px; text-align: center;">
                                        <div style="font-size: 12px; color: #065f46;">Fecha</div>
                                        <div style="font-size: 14px; font-weight: bold; color: #065f46;">{{ $plan->dia }}</div>
                                    </td>

                                    <!-- Hora -->
                                    <td width="50%" style="background-color: #d1fae5; border-radius: 12px; padding: 10px; text-align: center;">
                                        <div style="font-size: 12px; color: #065f46;">Hora de salida</div>
                                        <div style="font-size: 14px; font-weight: bold; color: #065f46;">{{ $plan->hora ?? 'No definida' }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

         
                    @endforeach

                </table>
            </td>
        </tr>
    </table>
</body>
