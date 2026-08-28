<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña - ViveGo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1E293B;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #F1F5F9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" width="600" style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 600px;">
                    
                    <!-- HEADER BRANDING -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1E1B4B, #0F172A); padding: 30px; text-align: center;">
                            @if(isset($message) && file_exists(public_path('images/logo-white.png')))
                                <img src="{{ $message->embed(public_path('images/logo-white.png')) }}" alt="ViveGo" style="max-height: 44px; width: auto; margin-bottom: 8px; display: inline-block;">
                            @else
                                <span style="font-size: 28px; font-weight: 900; color: #FFFFFF; letter-spacing: -0.5px;">
                                    Vive<span style="color: #FF5500;">Go</span>
                                </span>
                            @endif
                            <p style="color: #94A3B8; font-size: 11px; margin: 4px 0 0 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">
                                Plataforma Oficial de Entradas y Experiencias
                            </p>
                        </td>
                    </tr>

                    <!-- ACCENT STRIP -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #FF5500, #FF0055, #00D2C4);"></td>
                    </tr>

                    <!-- ICON AND TITLE -->
                    <tr>
                        <td style="padding: 30px 30px 15px 30px; text-align: center;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #FFF7ED; border: 2px solid #FFEDD5; color: #EA580C; font-size: 28px; line-height: 60px; margin: 0 auto 15px auto;">
                                🔑
                            </div>
                            <span style="background-color: #FFF7ED; color: #EA580C; font-size: 11px; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #FED7AA;">
                                SEGURIDAD Y ACCESO A TU CUENTA
                            </span>
                            <h1 style="color: #0F172A; font-size: 22px; font-weight: 800; margin: 15px 0 5px 0;">
                                Restablecimiento de Contraseña
                            </h1>
                            <p style="color: #64748B; font-size: 14px; margin: 0; line-height: 1.5;">
                                Hola <strong>{{ $name }}</strong>, recibimos una solicitud para acceder a tu cuenta en ViveGo mediante verificación de identidad.
                            </p>
                        </td>
                    </tr>

                    <!-- TEMPORARY PASSWORD BOX -->
                    <tr>
                        <td style="padding: 10px 30px 20px 30px;">
                            <div style="background: linear-gradient(145deg, #0F172A, #1E293B); border: 2px solid #334155; border-radius: 14px; padding: 25px; text-align: center; color: #FFFFFF; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                                <span style="font-size: 12px; font-weight: 700; color: #FF8844; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    Tu Contraseña Temporal de Acceso:
                                </span>
                                <div style="display: inline-block; background: rgba(255,255,255,0.08); border: 1.5px dashed #FF5500; border-radius: 10px; padding: 12px 28px; margin: 8px 0;">
                                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 26px; font-weight: 900; color: #FFFFFF; letter-spacing: 3px;">
                                        {{ $tempPassword }}
                                    </span>
                                </div>
                                <p style="color: #94A3B8; font-size: 12px; margin: 10px 0 0 0; line-height: 1.4;">
                                    🔒 Esta clave es provisional. Al iniciar sesión, el sistema te solicitará obligatoriamente ingresar una <strong>nueva contraseña personalizada</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- ACCOUNT DETAILS TABLE -->
                    <tr>
                        <td style="padding: 0 30px 25px 30px;">
                            <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden;">
                                <tr>
                                    <td colspan="2" style="background-color: #F1F5F9; padding: 10px 18px; border-bottom: 1px solid #E2E8F0;">
                                        <strong style="font-size: 12px; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                                            📋 Datos Asociados a tu Cuenta
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; font-size: 13px; color: #64748B; border-bottom: 1px solid #E2E8F0; width: 40%;">
                                        Nombre Completo:
                                    </td>
                                    <td style="padding: 12px 18px; font-size: 13px; font-weight: 700; color: #0F172A; border-bottom: 1px solid #E2E8F0;">
                                        {{ $name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; font-size: 13px; color: #64748B; border-bottom: 1px solid #E2E8F0;">
                                        Correo Electrónico:
                                    </td>
                                    <td style="padding: 12px 18px; font-size: 13px; font-weight: 700; color: #0F172A; border-bottom: 1px solid #E2E8F0;">
                                        {{ $email }}
                                    </td>
                                </tr>
                                @if(!empty($dni))
                                <tr>
                                    <td style="padding: 12px 18px; font-size: 13px; color: #64748B;">
                                        Documento / DNI:
                                    </td>
                                    <td style="padding: 12px 18px; font-size: 13px; font-weight: 700; color: #0F172A;">
                                        {{ $dni }}
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- HOW IT WORKS STEPS -->
                    <tr>
                        <td style="padding: 0 30px 25px 30px;">
                            <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 12px; padding: 16px; color: #92400E;">
                                <strong style="font-size: 13px; display: block; margin-bottom: 6px;">
                                    📌 ¿Cómo completar tu acceso?
                                </strong>
                                <ol style="margin: 0; padding-left: 20px; font-size: 12.5px; line-height: 1.6;">
                                    <li>Ingresa a ViveGo con tu correo o DNI y copia la contraseña temporal de arriba.</li>
                                    <li>En la pantalla que aparecerá, ingresa tu <strong>nueva contraseña definitiva</strong>.</li>
                                    <li>¡Listo! Podrás descargar tus boletos, consultar comprobantes y comprar entradas en 1 clic.</li>
                                </ol>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA BUTTON -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px; text-align: center;">
                            <a href="{{ route('web.login') }}" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; text-decoration: none; font-size: 15px; font-weight: 800; padding: 14px 36px; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.35);">
                                🚀 Iniciar Sesión en ViveGo
                            </a>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #0F172A; padding: 25px 30px; text-align: center; color: #64748B; font-size: 11.5px; line-height: 1.5;">
                            <p style="margin: 0 0 6px 0; color: #94A3B8; font-weight: 600;">
                                Si no solicitaste este cambio, por favor ignora este correo o contáctanos de inmediato.
                            </p>
                            <p style="margin: 0; color: #64748B;">
                                © {{ date('Y') }} ViveGo Perú. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
