# proiect_daw
Platforma de rezervare bilete la filme

Website-ul dezvoltat este o platformă online pentru rezervarea biletelor la filme. Iată o prezentare generală a caracteristicilor și funcționalităților sale de bază:

Înregistrare și autentificare utilizatori: Utilizatorii se pot înregistra prin furnizarea unor detalii personale, cum ar fi numele, emailul, numărul de telefon și o parolă. Pentru a îmbunătăți securitatea, formularul de înregistrare include protecție CSRF, validare reCAPTCHA și sanitizează inputurile utilizatorilor. După înregistrare, utilizatorii primesc un email cu un cod de verificare, pe care trebuie să-l introducă pentru a-și verifica contul.

Gestionarea sesiunilor și securitate: Sistemul de autentificare încorporează token-uri CSRF și reCAPTCHA pentru o securitate suplimentară. Odată verificați, utilizatorii se pot autentifica, iar redirecționarea bazată pe roluri asigură că administratorii și utilizatorii obișnuiți sunt direcționați către paginile corespunzătoare.

Listarea filmelor și rezervarea biletelor: Utilizatorii autentificați pot vizualiza o listă de filme disponibile, inclusiv detalii precum titlul, regizorul și durata. Fiecare intrare a filmului are un buton „Cumpără bilet”, care permite utilizatorilor să rezerve bilete. Acțiunea de cumpărare actualizează baza de date și oferă feedback cu privire la rezervările de bilete efectuate cu succes.

Panouri de administrare și utilizator: Drepturile de acces sunt determinate de rolurile utilizatorilor. Administratorii sunt redirecționați către o pagină dedicată de administrare pentru a gestiona conținutul, în timp ce utilizatorii obișnuiți sunt redirecționați către pagina principală de achiziționare a biletelor.

Arhitectura aplicației: Site-ul are o arhitectură organizată, cu secțiuni structurate pentru listarea filmelor, descrierea aplicației, arhitectura aplicației și un diagramă UML sau diagramă a aplicației. Această structură ajută utilizatorii și administratori să înțeleagă funcționalitatea și structura tehnică a aplicației.

Design tehnic: Dezvoltat pe backend cu PHP, site-ul folosește tehnologii web standard (HTML, CSS) pentru frontend, permițând o interacțiune fără probleme. De asemenea, include validarea formularului, gestionarea erorilor și componente stilizate pentru o experiență prietenoasă utilizatorului.
