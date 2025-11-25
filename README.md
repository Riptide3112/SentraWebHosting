# 🌐 SENTRA - Platformă de Hosting și Domenii

## 📋 Descriere
Lucrarea de față își propune să prezinte procesul de concepere, dezvoltare și implementare a aplicației web Sentra, o platformă dedicată administrării serviciilor de web hosting și gestionării clienților. Alegerea acestei teme a fost motivată de dorința de a crea o soluție practică și modernă, care să îmbine noțiunile teoretice învățate în cadrul specializării de „Analist Programator” cu aplicabilitatea reală a acestora într-un proiect complex.

Proiectul reunește concepte din mai multe domenii ale informaticii: programare web, baze de date, design de interfață și securitate informatică. Folosind tehnologii precum PHP, MySQL, JavaScript și TailwindCSS, aplicația oferă o interfață intuitivă, adaptată dispozitivelor moderne și orientată spre experiența utilizatorului.
Dezvoltarea acestei aplicații a permis aprofundarea principiilor de lucru cu structuri de date, interacțiunea dintre client și server, dar și înțelegerea modului în care se poate asigura protecția informațiilor în aplicațiile web.

Lucrarea reprezintă rezultatul unui proces de învățare activă, bazat pe practică, analiză și documentare continuă. Ea reflectă nu doar aplicarea cunoștințelor dobândite pe parcursul studiilor, ci și dorința de a dezvolta o platformă funcțională, scalabilă și relevantă pentru mediul digital actual.
Sentra este, în acest sens, mai mult decât un simplu proiect școlar – este o demonstrație a capacității de a îmbina teoria cu practica, într-o soluție software modernă și eficientă.

## ✨ Caracteristici Principale

### Pentru Clienți
- 🏠 **Dashboard Personalizat** - Gestionare completă a serviciilor
- 🌐 **Înregistrare și Transfer Domenii** - Proces simplificat
- 💻 **Pachete de Hosting** - Shared, Business și VPS
- 🎫 **Sistem de Ticketing** - Suport rapid și eficient
- 💳 **Facturare Automată** - Istoric și gestionare plăți
- ⚙️ **Setări Personalizate** - Control complet asupra contului

### Pentru Staff
- 👥 **Gestionare Clienți** - Administrare utilizatori
- 📢 **Anunțuri** - Comunicare cu clienții
- 🎫 **Management Tickete** - Sistem de suport centralizat
- 📊 **Panel Administrare** - Control complet asupra platformei

## 📁 Structura Proiectului

\\\
SENTRA/
├── api/              # Endpoint-uri API (AJAX, REST)
├── assets/           # Resurse statice
│   ├── css/          # Stiluri CSS
│   ├── js/           # JavaScript
│   └── images/       # Imagini
├── client/           # Panoul clientului
├── staff/            # Panoul staff-ului
├── includes/         # Fișiere PHP reutilizabile
├── pages/            # Pagini publice
└── index.php         # Pagina principală
\\\

## 🚀 Instalare

### Cerințe
- PHP 7.4 sau mai nou
- MySQL 5.7 sau mai nou
- Apache/Nginx cu mod_rewrite activat

### Pași de Instalare

1. **Clonează repository-ul:**
   \\\Bash
   git clone https://github.com/Riptide3112/Sentra-1.0.git
   cd Sentra-1.0
   \\\

2. **Configurează baza de date:**
   - Creează o bază de date MySQL
   - Importă schema SQL
   - Copiază \config.example.php\ în \config.php\
   - Completează datele de conectare

3. **Setează permisiunile:**
   \\\Bash
   chmod 755 -R *
   chmod 644 .htaccess
   \\\

4. **Accesează aplicația:**
   - Deschide browserul la \http://localhost/sentra\

## 🛠️ Tehnologii Folosite

- **Backend:** PHP 7.4+
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **AJAX:** Pentru interacțiuni asincrone
- **Security:** Prepared Statements, Input Validation

## 📂 Module Principale

### API (\/api\)
- Gestionare tickete și notificări
- Funcții mail
- Actualizare setări
- Ajax cu functie de integrare in timp real al facturilor si abonamentelor

### Client (\/client\)
- Dashboard personal
- Gestionare servicii
- Sistem de facturare
- Ticketing

### Staff (\/staff\)
- Panel administrare
- Gestionare clienți
- Management tickete
- Anunțuri

### Pages (\/pages\)
- Pagini publice (about, contact, etc.)
- Informații servicii
- Autentificare și înregistrare

## 🔒 Securitate

- ✅ Prepared Statements pentru preveni SQL Injection
- ✅ Validare și sanitizare input
- ✅ Autentificare și autorizare
- ✅ Protecție CSRF
- ✅ Hash-uri sigure pentru parole

## 👤 Autor

**Riptide3112**

## 🤝 Contribuții


**🌟 Dacă îți place proiectul, lasă un star pe GitHub!**

Dezvoltat cu ❤️ de Riptide3112
