# Mis 7 Ángeles — Berries & Avocados

Landing page estática, adaptable y sin dependencias de ejecución.

## Uso local

```bash
npm run build
python -m http.server 4173 --directory dist
```

Abre `http://localhost:4173`.

## Formulario

El formulario usa FormSubmit y envía a `bryanedilberto@hotmail.com`. FormSubmit exige que el dueño del correo confirme el primer mensaje de activación antes de entregar consultas. Para cambiar el destinatario, sustituye el correo en el atributo `action` del formulario en `index.html` y activa la nueva dirección. No hay credenciales en el código.

## Imágenes

El logo SVG de navegación es una reinterpretación vectorial del archivo original suministrado. La restauración del logo original está en `assets/images/logo-original-hd.png` (1452 × 1083 px); la página usa una copia WebP optimizada. Se produjo con la herramienta integrada de edición de imágenes a partir del JPEG original con este encargo: *«Restaurar y ampliar el logo exacto; conservar sol amarillo, piña de pino, frutos, aguacates, óvalo gris, fondo blanco y textos “MIS 7 ANGELES” y “Berries & Avocados”; mejorar nitidez y reducir artefactos sin rediseñarlo»*. Las fotografías son reales, tomadas de Pexels bajo su licencia gratuita; representan productos y trabajo de campo de forma ilustrativa, no instalaciones ni personal de la empresa.

- Frutos rojos: https://www.pexels.com/photo/close-up-shot-of-berries-6978242/
- Aguacates: https://www.pexels.com/photo/fresh-cut-avocado-on-table-in-kitchen-4352091/
- Cultivo de fresas: https://www.pexels.com/photo/strawberry-plantation-under-blue-sky-7457179/
- Otros cultivos: https://www.pexels.com/photo/assorted-fruits-and-vegetables-on-a-wooden-table-6653587/

La galería usa las 15 fotografías proporcionadas por la empresa. Las copias WebP optimizadas están en `assets/images/gallery/`; los archivos originales permanecen en su carpeta de origen.

Contenido empresarial basado en la presentación entregada. El sitio evita atribuir certificaciones específicas a la empresa, ya que las diapositivas incluyen certificados de productores terceros.
