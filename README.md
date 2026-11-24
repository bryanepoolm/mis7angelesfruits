# Mis 7 Ángeles — Berries & Avocados

Landing page estática, adaptable y sin dependencias de ejecución.

## Uso local

```bash
npm run build
python -m http.server 4173 --directory dist
```

Abre `http://localhost:4173`.

## Formulario

En Plesk, `contact.php` envía las consultas desde `oscaradriangutierrezsantos@gmail.com` a esa misma cuenta mediante `smtp.gmail.com`, STARTTLS y el puerto 587. La contraseña de aplicación **de la cuenta de Óscar** se guarda como único contenido de `httpdocs/contact.smtp.txt`, fuera de la raíz pública `httpdocs/dist` y excluida de Git. Se puede pegar con o sin los espacios que muestra Google. Esa cuenta debe tener activada la verificación en dos pasos para poder crear una contraseña de aplicación. La antigua contraseña de la cuenta de Bryan no autentica como Óscar y debe sustituirse en Plesk antes de activar esta versión. Si falta la configuración o Gmail rechaza el mensaje, el formulario muestra un error; nunca confirma un envío que solo quedó en la cola local. Para cambiar el destinatario, sustituye la dirección en `contact.php`. El administrador Git de Plesk despliega la rama `main` del repositorio.

## Imágenes

El logo SVG de navegación es una reinterpretación vectorial del archivo original suministrado. La restauración del logo original está en `assets/images/logo-original-hd.png` (1452 × 1083 px); la página usa una copia WebP optimizada. Se produjo con la herramienta integrada de edición de imágenes a partir del JPEG original con este encargo: *«Restaurar y ampliar el logo exacto; conservar sol amarillo, piña de pino, frutos, aguacates, óvalo gris, fondo blanco y textos “MIS 7 ANGELES” y “Berries & Avocados”; mejorar nitidez y reducir artefactos sin rediseñarlo»*. Las fotografías son reales, tomadas de Pexels bajo su licencia gratuita; representan productos y trabajo de campo de forma ilustrativa, no instalaciones ni personal de la empresa.

- Frutos rojos: https://www.pexels.com/photo/close-up-shot-of-berries-6978242/
- Aguacates: https://www.pexels.com/photo/fresh-cut-avocado-on-table-in-kitchen-4352091/
- Cultivo de fresas: https://www.pexels.com/photo/strawberry-plantation-under-blue-sky-7457179/
- Otros cultivos: https://www.pexels.com/photo/assorted-fruits-and-vegetables-on-a-wooden-table-6653587/

La galería usa las 15 fotografías proporcionadas por la empresa. Las copias WebP optimizadas están en `assets/images/gallery/`; los archivos originales permanecen en su carpeta de origen.

Contenido empresarial basado en la presentación entregada. El sitio evita atribuir certificaciones específicas a la empresa, ya que las diapositivas incluyen certificados de productores terceros.
