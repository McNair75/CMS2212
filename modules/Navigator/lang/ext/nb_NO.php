<?php
$lang['description'] = 'Denne modulen gir en enkel og lett måte å generere HTML som er nødvendig for et nettsteds navigasjon direkte, og dynamisk fra CMSMS sidestrukturen. Det tilbyr fleksibel filtrering, og malevne for å bygge kraftige, raske, og tiltalende nettside navigasjoner uten interaksjon fra innholdsredigererene.';
$lang['friendlyname'] = 'CMSMS Navigasjonsbygger';
$lang['help'] = '<h3>Hva gjør denne?</h3>
  <p>Denne "Navigator" modulen er en motor for å generere navigasjoner fra CMSMS-innholdstreet og en smart mal. Denne modulen gir fleksible filtreringsfunksjoner for å tillate å bygge mange navigasjoner basert på forskjellige kriterier, og et enkelt hierarkisk dataformat for å generere navigasjoner med full fleksibilitet.</p>
  <p>Denne modulen har ikke noe eget grensesnitt, i stedet bruker den DesignManager til å administrere Navigator-maler.</p>
<h3>Hvordan bruker jeg det?</h3>
<p>Den enkleste måten å bruke denne modulen er å sette inn <code>{Navigator}</code> tagg inn i en mal. Modulen godtar mange parametere for å endre atferd og filtrere dataene.</p>
<h3>Hvorfor bryr jeg meg om maler?</h3>
<p>Dette er kraften til CMSMS. Navigasjoner kan bygges automatisk ved hjelp av dataene fra innholdshierarkiet og en smart mal. Det er ikke nødvendig å redigere et navigasjonsobjekt hver gang en innholdsside legges til eller fjernes fra systemet. I tillegg kan navigasjonsmaler enkelt inkludere JavaScript eller avansert funksjonalitet og kan deles mellom nettsteder.</p>
<p>Denne modulen distribueres med noen få eksempelmaler, de er bare eksempler. Du er gratis og oppfordret til å kopiere dem og endre maler etter din smak. Styling av navigasjonen oppnås ved å redigere et CMSMS stilark. Stilark er ikke inkludert i Navigator-modulen.</p>
<h3>Node objektet:</h3>
  <p>Hver navmal er utstyrt med en rekke node objekter som samsvarer med kriteriene som er spesifisert på koden. Nedenfor er en beskrivelse av medlemmene av nodeobjektet:</p>
<ul>
  <li>$node->id -- Innholdsobjektets heltall-ID.</li>
  <li>$node->type -- Type node. i.e: innhold, lenke, pagelink, etc.</li>
  <li>$node->url -- URL til innholdsobjektet. Dette bør brukes når du bygger lenker.</li>
  <li>$node->accesskey -- Tilgangsnøkkel, hvis definert.</li>
  <li>$node->tabindex -- Tab-indeksen, hvis det er definert.</li>
  <li>$node->titleattribute -- Beskrivelse eller tittel attributt (tittel), hvis det er definert.</li>
  <li>$node->hierarchy -- Hierarki posisjon. (dvs. 1.3.3)</li>
  <li>$node->default -- TRUE hvis denne noden refererer til standard innholdsobjektet.</li>
  <li>$node->menutext -- Menytekst</li>
  <li>$node->raw_menutext -- Menytekst uten å ha konvertering av HTML-enheter</li>
  <li>$node->alias -- Side alias</li>
  <li>$node->extra1 -- Dette feltet inneholder verdien av egenskapen ekstra 1 side, med mindre loadprops-parameteren er satt til å IKKE laste inn egenskapene.</li>
  <li>$node->extra2 -- Dette feltet inneholder verdien til den ekstra2-siden egenskapen, med mindre loadprops-parameteren er satt til å IKKE laste inn egenskapene.</li>
  <li>$node->extra3 -- Dette feltet inneholder verdien til den ekstra3-siden egenskapen, med mindre loadprops-parameteren er satt til å IKKE laste inn egenskapene.</li>
  <li>$node->image -- Dette feltet inneholder verdien til bildesideegenskapen (hvis den ikke er tom), med mindre loadprops-parameteren er satt til å IKKE laste inn egenskapene.</li>
  <li>$node->thumbnail -- Dette feltet inneholder verdien til miniatyrsiden-egenskapen (hvis den ikke er tom), med mindre loadprops-parameteren er satt til å IKKE laste inn egenskapene.</li>
  <li>$node->target -- Dette feltet inneholder Mål for lenken (hvis ikke tom), med mindre loadprops-parameteren er satt til å IKKE laste inn egenskapene.</li>
  <li>$node->created -- Dato for oppretting av varen</li>
  <li>$node->modified -- Element endret dato</li>
  <li>$node->parent -- TRUE hvis denne noden er en overordnet del av den valgte siden</li>
  <li>$node->current -- TRUE hvis denne noden er den valgte siden</li>
  <li>$node->children_exist -- TRUE hvis denne noden har noen barn i det hele tatt.</li>
  <li>$node->children -- En rekke nodeobjekter som representerer de visbare barna til denne noden. Ikke angitt hvis noden ikke har barn å vise.</li>
  <li>$node->has_children -- TRUE hvis denne noden har noen barn som kan vises, men som ikke vises på grunn av andre filterparametere (antall nivåer osv.).</li>
</ul>
<h3>Eksempler:</h3>
<ul>
   <li>En enkel navigasjon som bare er to nivåer dypt, ved å bruke standardmalen:<br/>
     <pre><code>{Navigator number_of_levels=2}</code></pre>
   </li>
     <li>Vis en enkel navigering to nivåer dypt, og begynner med barna på den nåværende siden. Bruk standardmal:</li>
     <pre><code>{Navigator number_of_levels=2 start_page=$page_alias}</code></pre>
   </li>
   <li>Vis en enkel navigering to nivåer dypt, og begynner med barna på den nåværende siden. Bruk standardmal:</li>
     <pre><code>{Navigator number_of_levels=2 childrenof=$page_alias}</code></pre>
   </li>
   <li>Vis en navigasjon to nivåer dypt med start fra den gjeldende siden, dens jevnaldrende og alt under dem. Bruk standardmal:</li>
     <pre><code>{Navigator number_of_levels=2 start_page=$page_alias show_root_siblings=1}</code></pre>
   </li>
   <li>Vis en navigering av de spesifiserte menypunktene og barna deres. Bruk malen som heter mymenu</li>
     <pre><code>{Navigator items=\'alias1,alias2,alias3\' number_of_levels=3 template=mymenu}</code></pre>
   </li>
</ul>';
$lang['help_action'] = 'Angi handlingen av modulen. Denne modulen støtter to handlinger:
<ul>
   <li><em>standard</em> - Brukes for å bygge et primær navigasjonen. (denne handlingen er underforstått hvis ingen handling er spesifisert). </li>
   <li>brødsmuler - Brukes for å bygge en mini navigasjon bestående av banen fra roten av området ned til gjeldende side</li>
</ul>';
$lang['help_collapse'] = 'Når aktivert, vil bare elementer som er direkte knyttet til den nåværende aktive siden sendes ut';
$lang['help_childrenof'] = 'Dette alternativet vil gjøre at menyen bare viser elementer som er etterkommere av den valgte sidens id eller alias. dvs: <code>{menu childrenof=$page_alias}</code> vil bare vise barn av den gjeldende siden.';
$lang['help_excludeprefix'] = 'Ekskluder alle elementer (og deres barn) som har side alias som matcher et av de spesifiserte (kommaseparerte) prefikser. Denne parameteren skal ikke benyttes i forbindelse med den includeprefix parameteren.';
$lang['help_includeprefix'] = 'Omfatter bare de elementene som er side alias som matcher et av de spesifiserte (kommaseparerte) prefikser. Denne parameteren kan ikke kombineres med excludeprefix parameteren.';
$lang['help_items'] = 'Angi en kommaseparert liste med side aliaser som denne menyen skal vise.';
$lang['help_loadprops'] = 'Bruk denne parameteren når du ikke bruker avanserte egenskaper i din menymal. Dette deaktiverer lasting av alle innholdsegenskaper for hver node (for eksempel extra1, bilde, miniatyrbilde, etc.). Dette vil dramatisk redusere antall spørringer som kreves for å bygge en meny, og øke minnekrav, men vil fjerne muligheten for veldig avanserte menyer';
$lang['help_nlevels'] = 'Dette er alias for number_of_levels';
$lang['help_number_of_levels'] = 'Denne innstillingen vil begrense dybden av den genererte menyen til spesifisert antall nivåer. Som standard er verdien for denne parameteren underforstått å være ubegrenset, bortsett fra når du bruker items parameteren, i hvilket tilfelle number_of_levels parameteren er antydet å være 1';
$lang['help_root2'] = 'Kun brukt i "brødsmule" handlingen hvor denne parameteren indikerer at brødsmuler bør gå lenger opp på side-treet enn det angitte sidealias. Å angi et negativt heltall vil bare vise brødsmuler opp til øverste nivå, og vil ignorere standardsiden.';
$lang['help_show_all'] = 'Dette alternativet vil føre til at menyen viser alle noder selv om de er satt til å \'ikke vis i meny\'. Den vil fortsatt ikke vise inaktive sider.';
$lang['help_show_root_siblings'] = 'Dette alternativet blir bare nyttig hvis start_element eller start_page brukes. Det vil i utgangspunktet vise søsken langs siden av den valgte start_page/element.';
$lang['help_start_element'] = 'Starter meny som viser på den gitte start_element og viser det elementet og bare dets barn. Tar en hierarki posisjon (f.eks 5.1.2).';
$lang['help_start_level'] = 'Dette alternativet vil vise på menyen bare elementer som starter på et gitt nivå. Et enkelt eksempel ville være hvis du hadde en meny på siden med number_of_levels =\'1\'. Så som en andre meny, har du start_level =\'2 \'. Nå vil din andre meny vise poster basert på hva som er valgt i den første menyen.';
$lang['help_start_page'] = 'Starter menyen som viser den gitte start_page og viser det elementet og bare dets barn. Tar en side alias.';
$lang['help_template'] = 'Malen skal brukes for å vise menyen. Den navngitte malen må eksistere i DesignManager ellers vil en feil vil bli vist. Hvis denne parameteren ikke er angitt så er det standardmalen av typen Navigator::Navigation som vil bli brukt';
$lang['help_start_text'] = 'Nyttig bare i brødsmuler/breadcrumbs handlingen, hvor denne parameteren lar deg spesifisere valgfri tekst som skal vises på begynnelsen av brødsmulenavigasjonen. Et eksempel kan være "Du er her"';
$lang['type_breadcrumbs'] = 'Brødsmuler/breadcrumbs';
$lang['type_Navigator'] = 'Navigator';
$lang['type_navigation'] = 'Navigasjon';
$lang['youarehere'] = 'Du er her';
?>