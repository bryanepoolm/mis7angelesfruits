import { cp, mkdir, rm } from 'node:fs/promises';

await rm('dist', { recursive: true, force: true });
await mkdir('dist', { recursive: true });
await cp('index.html', 'dist/index.html');
await cp('contact.php', 'dist/contact.php');
await cp('assets', 'dist/assets', { recursive: true });
console.log('Sitio generado en dist/');
