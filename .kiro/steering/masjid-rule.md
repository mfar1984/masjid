---
inclusion: always
---

# Rules Projek Masjid

## Framework & Tools
- Guna Next.js, Node atau Laravel sahaja
- Jangan guna `php artisan migrate:fresh`
- Jangan install atau uninstall MySQL
- Jangan guna `git add .`
- Jangan guna `git commit -m`
- Jangan guna "open in browser"
- Jangan guna restore melainkan diminta

## UI/UX Standards
- Font: Poppins
- Saiz font: minimum 10px, maximum 14px
- Border radius: minimum 4px, maximum 8px
- Jangan overuse border radius

## Code Organization
- Asingkan code dalam components (box, container, dll) dalam folder components
- Asingkan code CSS untuk setiap file
- Boleh compare code sebelum jika perlu

## Laravel Best Practices
- Pastikan check relationship, logic, model, controller, route, webview setiap kali buat code
- Sama juga untuk Node dan React
- Pastikan kalau ada table, kena ada pagination dan rekod serta filter. pastikan design pattern table dan semua ini kena consistent baik dari segi button, icon, font size, warna dan lain-lain kena sama seperti yang sudah ada. jangan sesekali buat design lain dari yang lain.
- pastikan kalau tidak pasti bagaimana code tersebut. anda kena pastikan dengan pattern sedia ada.
- pastikan scope id, masjid id, role, crud kena buat untuk setiap page, menu, submenu, icon edit, show, approval, delete, rejected, create .
- pastikan setelah membuat crud ini semua, update dalam senarai kumpulan tapi pastikan senarai kumpulan kena follow menu dan submenu serta TAB . jangan buat cacar merba

## Testing & Debugging
- Pastikan bila code siap, test guna folder tests
- Bila debug selesai dan masalah solved, remove debug code
- Bila code berubah, deep check semula untuk pastikan file adalah clean code

## Tinker & Commands
- Bila guna `php artisan tinker`, guna dengan complete code untuk elak tunggu output
- Jangan run tinker tanpa complete execution

## Browser & Build
- Guna Chrome sebagai default browser (bukan Safari)
- `npm run build` untuk check code jika ada error
- `npm run start` atau `npm run dev` untuk development

## Prisma
- Jangan guna `prisma migrate reset`
- Guna `npx prisma migrate reset --force` jika perlu
