<?php
$lang['help_group_permissions'] = '<h4>CMSMS Admin Tillatelse Modell</h4>
<ul>
<li>CMSMS bruker et system av navngitte rettigheter. Tilgang til disse tillatelsene bestemmer en brukers evne til å utføre forskjellige funksjoner i CMSMS administrasjonskonsollen .</li>
<li>CMSMS kjernen skaper flere tillatelser på installasjon <em>(tidvis tillatelser legges til eller slettes under en oppgraderingsprosessen)</em>.  Tredjeparts moduler kan skape ytterligere tillatelser.</li>
<li>Tillatelser er forbundet med brukergrupper.  En autorisert bruker kan justere tillatelsene som er forbundet med visse medlemsgrupper <em>(Inkludert tilgang til å endre en gruppe tillatelser)</em>.<strong>Admin</strong> gruppen er en spesiell gruppe. Medlemmer av denne gruppen vil ha alle tillatelser.</li>
<li>Admin brukerkontoer kan være medlemmer av ingen eller flere grupper. Det kan være mulig for en brukerkonto som ikke er medlem av noen grupper å fortsatt utføre ulike funksjoner<em>(Les om eierskap og ekstra-redaktører i Content Manager hjelp, og design hjelp Manager).</em>.  Den første brukerkontoen <em>(uid == 1)</em>, som vanligvis er navngitt "Admin" er en spesiell brukerkonto og vil ha alle tillatelser.</li>
</ul>';
$lang['help_cmscontentmanager_help'] = '<h3>Innledning</h3>
<p>Dette dokumentet beskriver CMS Content Manager-modulen. Den er primært rettet mot nettsteddesigneren eller utvikleren og beskriver i store trekk hvordan innholdselementer fungerer i CMS Made Simple.</p>
<p>Det primære grensesnittet til Content Manager-modulen er innholdslisten. Den viser innholdselementer i tabellformat og gir muligheten til raskt å søke, navigere og administrere flere innholdselementer. Dette er en dynamisk liste. Displayet justeres avhengig av enkelte konfigurasjonselementer på nettstedet, noen globale innstillinger, brukerrettigheter og individuelle innholdselementer. Følgende tekst beskriver hva innholdselementer er, og hvordan innholdslisten oppfører seg med dem.</p>
<h3>Innholdshierarki og navigasjoner</h3>
<p>CMS Made Simple bygger frontendnavigasjoner dynamisk fra innholdslisten, de enkelte typer innholdselementer, innholdet i disse innholdselementene og navigasjonsmalen. Organiseringen av navigasjoner styres først og fremst av foreldre-barn/hierarki-forholdet til innholdsartiklene dine. Fra det øverste <em> (rot)</em> nivået, nedover.</p>
<p>Å legge til et nytt element i navigasjonsmenyen er så enkelt som å opprette et nytt innholdselement, plassere det på ønsket sted i hierarkiet, og <em>(avhengig av innholdstypen)</em> spesifisere de forskjellige egenskaper som innholdstypen gir.</p>
<h3>Innholdselementtyper:</h3>
  <p>CMSMS distribueres med flere forskjellige typer innholdsemner <em> (og mer er tilgjengelige via tredjeparts tilleggsmoduler)</em>. Disse innholdselementtypene tjener forskjellige formål når en navigasjon genereres. Noen inneholder ikke innhold, men brukes bare til å administrere navigasjoner. For eksempel har separatorens innholdselementtype ikke noe eget innhold, og eksisterer utelukkende for å organisere innholdselementer og gi en synlig separator i den genererte navigasjonen.
  <p>Nedenfor er en kort beskrivelse av hver innholdselementtype som distribueres med CMS Made Simple</p>
<Ul>
  <li>Innholdsside
  <p>Denne innholdstypen ligner mest på en HTML-side, og brukes vanligvis til det formålet. Når redaktører oppretter innholdssideelementer, velger de et design og mal som kontrollerer utseendet på siden, spesifiserer en tittel og skriver inn innholdet for siden.</p>
    <p>Innholdselementer kan også inneholde skjemaer, logikk, vise dynamiske data fra moduler eller brukerdefinerte koder (UDT-er). Denne fleksibiliteten gjør det mulig å lage spesialiserte applikasjoner, eller ekstremt fleksible og dynamiske nettsteder.</p>
  </li>
  <li>Link
    <p>Denne innholdstypen brukes i navigasjoner for å generere en kobling til en side på et eksternt nettsted.</p>
  </li>
  <li>Sidelink
  <p>Denne innholdstypen brukes også i navigasjoner. Den genererer en sekundær lenke til en eksisterende innholdsside. Denne innholdselementtypen kan brukes hvis du får tilgang til et innholdselement fra flere steder i navigasjonen.</p>
  </li>
  <li>Separator
  <p>Denne innholdstypen brukes også i navigasjoner. Det brukes vanligvis til å generere en horisontal (eller vertikal) skillelinje mellom navigasjonselementer. Noen typer navigasjoner <em> (bestemt av navigasjonsmalen)</em> vil kanskje ikke vise separatorer i det hele tatt.</p>
  </li>
  <li>Seksjonstittel
    <p>Seksjonsoverskriften vises også bare i navigasjoner. Det brukes til å organisere innholdselementer. Den gir en teksttekst over, eller mellom andre innholdselementer. Seksjonsoverskrifter har ikke URL-er og kan ikke vanligvis klikkes på. Noen navigasjonsmaler kan style seksjonsoverskrifter på en annen måte enn andre innholdselementer.</p>
  </li>
  <li>Feilside
    <p>Feilsiden er en spesiell type innholdselementtype. Det brukes når en bruker prøver å navigere til et innholdselement som enten ikke er navigerbart eller ikke eksisterer.</p>
  </li>
</Ul>
<p>Mange tredjepartsmoduler gir flere innholdstyper for å tjene forskjellige formål. For eksempel å vise kataloger over produkter, eller begrense innholdet til autoriserte brukere.</p>
<h3>Innholdslisten</h3>
<p>Innholdslisten er hovedgrensesnittet til modulen. Dette skjemaet gir hovedstyringsgrensesnittet for innholdet ditt. Herfra kan du opprette, redigere, slette, kopiere, deaktivere og organisere innholdselementene dine. Denne skjermen er sterkt optimalisert for større nettsteder som tilbyr paginasjons- og søkemekanismer for bare å vise en liten mengde sider om gangen, men for raskt å finne elementer som skal administreres.</p>
<h4>kolonner</h4>
<p>Hvert innholdselement vises som en rad i en tabell. Det er et antall kolonner for raskt å vise forskjellige attributter for hvert innholdselement, og noen praktiske handlingsikoner. Noen kolonner kan være skjult fra visningen helt, eller bare for noen rader avhengig av en rekke faktorer:</p>
  <Ul>
    <li>Dine tilgangstillatelser og sideeierskap:
      <p>Hvis kontoen din er begrenset til visse tillatelser, kan det hende at noen kolonner ikke vises, eller de kan være deaktivert.</p>
    </li>
    <li>Systemvalg og nettstedskonfigurasjon
      <p>Noen systempreferanser, og stedkonfigurasjonsalternativer vil føre til at noen kolonner blir deaktivert. For eksempel & quot; url & quot; kolonne</p>
    </li>
    <li>Innholdselementtypen
      <p>Avhengig av innholdselementtype, kan visse kolonner bli irrelevante. For eksempel er det ikke mulig for & quot; Seksjonsoverskrifter & quot; eller & quot; Separatorer & quot; for å bli standardsiden, så blir ingenting vist i & quot; standard & quot; kolonne for disse innholdselementene.</p>
    </li>
    <li>Hvorvidt innholdselementet redigeres
      <p>Når andre brukere <em> (eller til og med deg selv) redigerer et innholdselement, blir noen kolonner skjult i raden for hver innholdstype for å forhindre endring, sletting eller kopiering av innholdssiden.</p>
    </li>
  </Ul>
  <h5> Kolonneliste </h5>
      <p>Content Manager-modulen gir en fleksibel mekanisme for å skjule og vise forskjellige kolonner i innholdslisten. I tillegg kan noen kolonner være skjult basert på konfigurasjonen av nettstedet. For eksempel er URL-kolonnen skjult hvis pen <em> (søkemotorvennlig)</em> URL-er ikke er konfigurert.</p>
      <p>Hver kolonne i innholdslistedisplayet har et spesielt formål:</p>
   <Ul>
     <li>Utvid/skjul kolonnen
      <p>Når et innholdselement har barn, vil denne kolonnen fylles med et ikon som lar utvide listen for å vise elementet barn, eller kollapse listen for å skjule dem. Tilstanden for hvilke elementer som utvides og hvilke som blir kollapset, lagres på en per brukerbasis. Slik at når du besøker innholdssjefen, vil den utvidede / kollapsede tilstanden til sidene dine være den samme.</p>
     </li>

     <li>Hierarkikolonne
      <p>Hierarkikolonnen viser plasseringen av hvert innholdselement i hierarkiet på en numerisk måte. Hierarkiet til den første siden av rotnivået begynner med 1 og øker trinnvis for hver gruppe. Hvert barn begynner med 1, og dets jevnaldrende øker trinnvis. Derfor vil det andre barnebarnet til det tredje barnet til det første elementet i innholdslisten ha et hierarki på 1.3.2.</p>
      <p>Hierarkimekanismen er en betydelig del av det som gir muligheten for CMS Content Manager til å organisere innholdselementer, og deretter bygge navigasjoner fra dem.</p>
     </li>

     <li>Sidetittel/menytekstkolonne
      <p>Denne kolonnen kan enten vise sidetittelen eller sidemenyteksten. Dette avhenger av en innstilling i & quot; Nettstedsadministrator & raquo; Innstillinger for Content Manager & quot; side. </ p>
      <p>Denne kolonnen vil inneholde en kobling for å tillate redigering av innholdselementet <em> (med mindre innholdselementet er låst)</em>. Når du holder musepekeren over teksten i denne kolonnen, vises tilleggsinformasjon om innholdselementet, for eksempel den unike numeriske innholds-IDen, og om siden er hurtigbar eller ikke.
      <p>Hvis innholdselementet er låst, vil du holde musepekeren over teksten i kolonnen informasjon om hvem som låste elementet, og når låsen utløper.</p>
     </li>

     <li>URL-kolonne
      <p>Hvis dette er aktivert, vil denne kolonnen vise hvilken som helst alternativ URL for dette innholdet. <em> (Merk: Bare visse innholdstyper støtter en alternativ URL).</em></p>
     </li>

     <li>Side alias-kolonne
      <p>Denne kolonnen viser det unike aliaset som er knyttet til hver side. Aliaser er tekststrenger som identifiserer innholdet på en unik måte. Du bruker innholdselementene alias (eller numerisk id) når du trenger å henvise til en side i systemet. <em> (Merk: Noen innholdstyper har ikke aliaser.)</em></p>
     </li>

     <li>Malkolonne
      <p>Denne kolonnen viser designet og malen som brukes til å vise innholdet for elementet. Se hjelpen for & quot; Design Manager & quot; modul for en forklaring av hvordan CMSMS administrerer design, inkludert stilark og maler. <em> (Merk: Noen typer innhold inneholder ikke design eller mal.)</em></p>
     </li>

     <li>Skriv inn kolonne
       <p>Denne kolonnen indikerer innholdstypen (dvs. innhold, seksjonsoverskrift, separator, etc.). <p>
     </li>

     <li>Eierspalte
       <p>Eierkolonnen viser brukernavnet til eieren av innholdselementet. Når du holder musepekeren over teksten i denne kolonnen, vises informasjon om når innholdet ble opprettet og sist redigert.</p>
     </li>

     <li>Aktiv kolonne
       <p>Denne kolonnen viser ikoner for å vise den aktive statusen til innholdselementet. Aktive elementer kan navigeres til, og vises i navigasjonsmenyer på fronten. Hvis brukerkontoen din har tilstrekkelig privilegium til innholdselementet, kan du klikke på ikonet for å bytte aktiv status.</p>
     </li>

     <li>Standardkolonne
       <p>Denne kolonnen viser om innholdselementet er standardsiden eller ikke. Standardinnholdet er hjemmesiden til nettstedet ditt. Bare noen innholdstyper lar innholdstypen være standard.</p>
       <p>Hvis brukerkontoen din har tilstrekkelig privilegium, og innholdstypen støtter å være standardinnholdet for nettstedet, kan du klikke på ikonet for å endre standardflagget til en annen side.</p>
     </li>

     <li>Flytt & quot; Kolonne
       <p>Avhengig av tilgangsrettighetene dine, kan det hende du ser ikoner som gjør det mulig å endre rekkefølgen på innholdselementene i forhold til de nærmeste jevnaldrende. Dette er en enkel mekanisme for raskt å ombestille innholdselementer blant sine jevnaldrende. & Quot; Ombestillingssider & quot; alternativet tillater omorganisering av sider i massen, og når du redigerer et innholdselement kan du raskt tilordne elementet til en annen overordnet.</p>
     </li>

     <li>Handlingsikoner
       <p>Avhengig av tilgangsrettighetene dine, innholdstypen og dens nåværende låsestatus, kan det hende du ser forskjellige ikoner på hver innholdsrekke som gir forskjellige funksjoner:</p>
       <Ul>
         <li>Vis - Åpne et nytt nettleservindu <em> (eller fane)</em> og se innholdselementet slik de besøkende vil se det. </li>
         <li>Kopier - Kopier innholdet til et nytt innholdselement.
           <p>Et nytt innholdselement opprettes med en ny sidetittel, og alias, og du får presentert muligheten til å redigere den nye siden.</p>
         </li>
         <li>Slett - Slett innholdet
           <p>Avhengig av tilgangsrettighetene dine, og om innholdselementet har barn eller ikke, kan alternativet for å slette innholdselementet være skjult eller deaktivert.</p>
         </li>
         <li>Stjelås
           <p>For innholdselementer som for øyeblikket redigeres, men som låsen er utløpt for <em> (redaktøren har ikke gjort en endring i skjemaet på en stund)</em> Dette alternativet lar deg stjele låsen . </ p>
         </li>
         <li>Avkrysningsrute for bulkoperasjoner
           <p>Avkrysningsruten for bulkoperasjoner lar deg velge flere innholdselementer for å operere på en-masse
         </li>
       </Ul>
     </li>
   </Ul>

<h4> Rediger evne </h4>
   <p>Muligheten til å redigere et innholdselement bestemmes enten med tillatelse <em> (se Administrer alt innhold, og endre eventuelle sidetillatelser nedenfor)</em>, eller ved å være eier, eller tilleggsredigerer av et innholdselement . </ p>

<h4>eier</h4>
   <p>Som standard er eieren av et innholdselement brukeren som opprinnelig opprettet det. Eiere, eller brukere med & quot; Administrer alt innhold & quot; tillatelse kan gi eierskap til en side til en annen bruker.</p>

<h4>Ytterligere redaktører </h4>
    <p>Når du redigerer et innholdselement som eier eller som bruker med & quot; Administrer alt innhold & quot; tillatelse, kan brukeren velge andre administrative brukere eller Administrasjonsgrupper som også har lov til å redigere innholdet.</p>

<h4>Relevante tillatelser. </h4>
    <p>Det er noen få tillatelser som påvirker hvilke kolonner som vises i innholdslisten og muligheten til å samhandle med innholdslisten:</p>
    <Ul>
      <li>Legg til sider
    <p>Denne tillatelsen lar brukerne lage nye innholdselementer. I tillegg kan brukere med denne tillatelsen kopiere innholdselementer som de har redigeringsevne på.</p>
      </li>
      <li>Endre hvilken som helst side
        <p>Brukere med denne posisjonen vil kunne redigere ethvert innholdselement. Det ligner på å være en "tilleggsredaktør" på alle innholdselementer.</p>
      </li>
      <li>Fjern sider
        <p>Denne tillatelsen lar brukere fjerne innholdselementer som de har redigeringsevne på. Uten denne tillatelsen vil sletteikonet på hver rad i innholdsartiklene være skjult.</p>
      </li>
      <li>Omorganiser sider
        <p>Denne tillatelsen lar brukere som har redigeringsevne til alle søsken av et innholdselement, ordne innholdselementer mellom sine jevnaldrende.</p>
        <p>ie: En bruker i en gruppe som har redigeringsevne til innholdselementet med hierarki 1.3 og alle dets direkte søsken <em> (1.1, 1.2, 1.3, 1.4, osv.).</em> vil kunne ordne disse elementene i navigasjonen på nytt. Brukere uten denne tillatelsen vil ikke se ikonene for å bevege seg opp / ned i listet innhold.</p>
      </li>
      <li>Administrer alt innhold
        <p>Denne tillatelsen gir superbrukerfunksjon på alle innholdselementer. Brukere med denne tillatelsen kan legge til, redigere, slette og bestille på nytt innhold. De har også muligheten til å angi standardinnholdet og utføre bulkhandlinger som å endre eierskap som kanskje eller ikke er tilgjengelig for brukere med andre tillatelser.</p>
      </li>
    </Ul>
   <p>Det er mulig at en Admin-brukerkonto ikke er medlem av noen grupper, og for den Admin-brukerkontoen har fremdeles muligheten <em> (som eier eller tilleggsredigerer)</p> til å redigere noen innholdselementer . </ p>

<h4> Innlåsing </h4>
   <p>Innlåsing er en mekanisme som forhindrer to redaktører fra å redigere det samme elementet samtidig, og derfor ødelegger hverandres arbeid. Administratorbrukere får eksklusiv tilgang til et innholdselement inntil de sender inn endringene.</p>
   <p>Hvis et innholdselement er låst, kan du ikke redigere det før låsen er utløpt. Se nedenfor for informasjon om utløp av lås. Når en lås har gått ut, har en bruker muligheten til å stjele låsen fra den opprinnelige redigereren og starte en ny redigeringsøkt.</p>
   <p>Et spesielt ikon vises på raden for innholdsartikler for å indikere at låsen kan bli stjålet.</p>

 <H4>-konfigurasjon</h4>
   <p>Noen konfigurasjonselementer påvirker synligheten til visse elementer i innholdslisten:</p>

<h4>Annen funksjonalitet </h4>
   <Ul>
     <li>paginering
       <p>Innholdslisten kan pagineres. Dette er en ytelsesfunksjon for store nettsteder med mye innholdselementer. Standardgrensen er 500 elementer, men denne grensen kan senkes ved å justere verdien i alternativdialogen.</p>
     </li>
     <li>Utvid / skjul alt
       <p>Disse alternativene gjør det mulig å utvide alle innholdselementer med barn slik at barna er synlige. Eller, omvendt, kollaps alle innholdselementer med barn slik at barna ikke blir synlige. Det er nyttig å enkelt finne et innholdselement, eller å få oversikt over nettstedets struktur. Hver innholdselement med barn kan fremdeles utvides, eller kollapses individuelt.</p>
     </li>
     <li>Søker
       <p>& quot; Finn & quot; tekstboks i øverste venstre hjørne av innholdslisten lar brukerne raskt og enkelt finne et innholdselement etter tittelen eller menyteksten. Dette skjemaet bruker ajax og autofullfør for å vise en rullegardinliste over alle elementene som samsvarer med den angitte strengen (minimum tre tegn er påkrevd).</p>
     </li>
     <li>Masse handlinger
       <p>& quot; Med utvalgte & quot; skjemaet nederst til høyre på innholdslisten gir brukere med passende tilgang til å endre eller samhandle med innholdselementer masse. Flere alternativer er tilgjengelige (avhengig av både valgte elementer og brukerens tilgangstillatelse):</p>
       <Ul>
<li>Slett
           <p>Dette alternativet lar deg slette flere innholdselementer (og deres barn) i noen få trinn. Alle de valgte innholdsartiklene og deres etterkommere vil bli analysert for å kunne slettes. Brukere blir deretter bedt om å få en liste over innholdselementene som har bestått analysen <em> (hvis noen)</em> og for å bekrefte handlingen.</p>
<p>Bare brukere med tillatelse til å fjerne sider og endre hvilken som helst side, eller Administrere alt innhold, kan bruke dette alternativet.</p>
           <p><strong> Merk: </strong> Når du velger mange innholdselementer, eller innholdselementer med mange etterkommere, kan dette være en veldig minne-, database- og tidkrevende operasjon.</p>
         </li>
         <li>Sett aktiv
           <p>Dette alternativet vil sikre at de valgte innholdsartiklene er merket som & quot; Aktiv & quot ;. Brukere vil bli bedt om å bekrefte operasjonen. Denne operasjonen fungerer ikke på etterkommeren av de valgte sidene.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Angi inaktiv
           <p>dette alternativet analyserer de valgte elementene for kvalifisering, og vil sette alle kvalifiserte innholdselementer til inaktive. Inaktive sider kan ikke navigeres til og kan ødelegge et fungerende nettsted. Standardsiden kan ikke settes til inaktiv.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Still inn cache
           <p>Dette alternativet setter de valgte innholdselementene til "cache". Dette kan ha forskjellige effekter basert på konfigurasjonen av nettstedet: <p>
           <p>Hvis aktivert i & quot; Nettstedsadministrator >> Globale innstillinger & quot; deretter innholdselementer som er merket som "cachbart" kan bufres av nettleseren <em> (dette reduserer belastningen på webserveren din for brukere som besøker samme side ofte).</em>
           <p>Også i & quot; Nettstedsadministrator >> Globale innstillinger & quot; Smarty hurtigbufring gir hurtigbufrende sider. Dette er et avansert verktøy som vil cache den genererte HTML-koden til a for gjentatt bruk, og kan dramatisk redusere serverbelastningen og forbedre ytelsen. Imidlertid er det et avansert emne og kan negativt ha den dynamiske karakteren til noen innholdselementer.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Sett ikke Cache
           <p>Dette alternativet sikrer at de valgte innholdselementene ikke er cachbare. <p>
         </li>
<li>Vis i meny
           <p>Dette alternativet sikrer at de valgte innholdselementene er synlige i navigasjonsmenyene foran.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Skjul fra menyen
           <p>Dette alternativet sikrer at de valgte innholdselementene ikke blir synlige (som standard) i navigasjonsmenyene foran. Ulike alternativer for navigasjonsgenerasjonsmoduler kan overstyre & quot; Show In Menu & quot; innstilling. </ p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Angi sikker (HTTPS)
           <p>Dette alternativet vil sikre at HTTPS blir brukt når de valgte innholdselementene vises.</p>
           <p><strong> Merk: </strong> Du må kanskje justere de sikre URL-innstillingene i CMSMS config.php-filen, og kontakte verten din om riktig SSL-konfigurasjon.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Angi usikker (HTTP)
           <p>Dette alternativet fjerner HTTPS-flagget fra de valgte innholdselementene.</p>
           <p><strong> Merk: </strong> Innholdselementer uten sikker <em> (HTTPS)</em> kan fremdeles nås via HTTPS-protokollen.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Sett design og mal
           <p>Dette alternativet vil vise et skjema for å angi design og mal som er tilknyttet de valgte innholdselementene. Bare noen typer innholdsartikler har en design og malforening. i.e: "innholdet" elementtype, og de som er levert av andre moduler som gir lignende funksjonalitet.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
         <li>Angi eier
           <p>Dette alternativet viser et skjema som gjør det mulig å endre eierskapet til de valgte innholdselementene.</p>
<p>Bare brukere med & quot; Administrer alt innhold & quot; tillatelse kan bruke dette alternativet.</p>
         </li>
       </Ul>
     </li>
<li>endre rekkefølgen
       <p>Brukere med & quot; Administrer alt innhold & quot; tillatelse har muligheten til å organisere innholdsemner på en masse ved å velge & quot; Omorganiser sider & quot; element fra alternativmenyen på innholdslistedisplayet. Dette gir et skjema der innholdselementer kan bestilles på nytt med enkle dra-og-slipp-operasjoner.</p>
       <p><strong> Merk: </strong> Dette kan være en veldig minne- og databasekrevende operasjon, og vi foreslår ikke å bruke dette alternativet på nettsteder med mer enn noen få hundre innholdselementer.</p>
     </li>
   </Ul>

<h3> Legge til og redigere innholdselementer</h3>
 <p>Muligheten til å legge til innholdselementer avhenger av at du enten har & quot; Administrer alt innhold & quot; tillatelse, eller "Legg til sider" tillatelse. Brukere med & quot; Administrer alt innhold & quot; tillatelse kan administrere alle aspekter av innholdselementet. Brukere uten denne tillatelsen vil ha betydelig mindre evner.</p>
 <p>Skjemaet for å legge til (eller redigere) innholdssiden er delt inn i mange faner; mange egenskaper for innholdselementet vil vises på forskjellige faner. Listen over faner som er synlige, og & quot; egenskapene & quot; på disse kategoriene er påvirket av flere faktorer:</p>
   <Ul>
     <li>Innholdselementtypen
 <p>Noen typer innholdsartikler (for eksempel separatorer og seksjonsoverskrifter) krever ikke mye informasjon, derfor vil veldig få faner og egenskaper vises.</p>
     </li>
     <li>Tillatelsesnivået ditt
       <p>Hvis brukerkontoen din ikke har & quot; Administrer alt innhold & quot; tillatelsesnivå, har du bare lov til å administrere <em> (som standard)</em> de grunnleggende egenskapene til innholdselementet. Nok til å redigere innhold, og velg en side i navigasjonen. Du kan også være begrenset til hvor nye innholdselementer kan plasseres i innholdshierarkiet.</p>
     </li>
     <li>Nettstedsinnstillinger <em> (dvs.: & quot; Grunnleggende egenskaper & quot; -feltet i & quot; Globale innstillinger & quot; -vinduet og andre)</em>.
       <p>Noen sideinnstillinger <em> (og til og med konfigurasjonsinnstillinger)</em> kan påvirke hvilke egenskaper som vises på hvilken fane. Den "grunnleggende egenskapene" innstillingen i & quot; Nettstedsadministrator >> Globale innstillinger & quot; siden utvider listen over innholdselementegenskaper som brukere med begrensede tillatelser kan redigere.</p>
     </li>
     <li>Malen som er valgt.
       <p>Tagger i maler definerer flere egenskaper <em> (kalt innholdsblokker)</em> som autoriserte brukere kan redigere når de redigerer et innholdselement som bruker malene. Disse innholdsblokkene kan være områder med ren tekst, WYSIWYG-testområder, bildevalgere eller andre elementer. Malutviklere kan spesifisere fanen som redigeringsfeltet for hver innholdsblokk vises på.</p>
     </li>
   </Ul>
<H4> Egenskaper</h4>
    <p>Her vil vi kort beskrive de vanlige egenskapene for & quot; Innhold & quot; innholdstype. Noen innholdstyper bruker betydelig færre egenskaper, og noen innholdstyper som leveres av tredjepartsmoduler kan oppføre seg helt annerledes.</p>
  <Ul>
    <li>Tittel
      <p>Dette feltet beskriver tittelen på innholdselementet (hvis relevant). Tittelen vises vanligvis i & lt; tittelen & gt; tag i HTML-sidehodet, og et sted på et fremtredende sted i HTML-sideinnholdet. Nettstedsutvikleren har full kontroll over hvordan disse dataene brukes eller vises.</p>
    </li>

    <li>alias
      <p>Sidealiaset er en streng som identifiserer dette innholdselementet på en unik måte, og er vanligvis lettere å huske enn heltalers id-ID. Aliaset brukes mange steder når du bygger CMSMS nettsted. Det kan brukes til å lage koblinger til innholdselementer, til å lage spesialiserte navigasjoner, eller som atferdshenvisninger til andre moduler som indikerer på hvilket innholdselement de skal vise data.
      <p>Som standard genereres sidealiaset unikt fra tittelen når du legger til et nytt innholdselement, men brukerne kan imidlertid spesifisere sitt eget alias når de legger til eller redigerer innholdet, så lenge det er unikt blant alle andre innholdselementer. Noen innholdsartikler krever ikke sidealias.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å spesifisere aliaset når de legger til eller redigerer et innholdselement.</p>
    </li>

    <li>Forelder
      <p>Overordnet egenskap spesifiserer innholdselementet som er den nærmeste overordnede til innholdselementet som redigeres i innholdshierarkiet. Brukere med begrensede tillatelser har kanskje ikke muligheten til å redigere denne egenskapen, eller kan ha en begrenset liste over alternativer for denne egenskapen.</p>
    </li>

    <li>Innhold
      <p>Hver sidemal er pålagt å inkludere minst standardinnholdseiendommen <em> (a.k.a-blokkering)</em>. Imidlertid kan de definere mange flere og forskjellige typer innholdsblokker. Standardblokken vises vanligvis i redigeringsinnholdsskjemaet som et wWYSIWYG-aktivert tekstområde som lar redaktøren angi noe standardinnhold for siden.</p>
      <p>Nettstedsutviklere har betydelig kontroll over fanen som denne vises i, etiketten, makslengden, påkrevd og andre attributter for å kontrollere atferden til denne egenskapen i redigeringsskjemaet, og når den vises.</p>
<p>Hvis WYSIWYG-redigereren er aktivert for denne innholdsblokken og innholdselementet <em> (se nedenfor)</em>, og en eller flere WYSIWYG-redigeringsmoduler er aktivert, og brukeren har valgt en WYSIWYG-redigerer i sine preferanser da en WYSIWYG-redigeringsprogram vises. Ulike WYSIWYG-redaksjoner har forskjellige evner, men de fleste gir muligheten til å formatere tekst på forskjellige måter. I tillegg tillater de fleste WYSIWYG-redaksjoner å sette inn bilder og opprette lenker til andre innholdselementer på nettstedet ditt.</p>
    </li>

    <li>Menytekst
      <p>Denne egenskapen brukes når du bygger navigasjoner. Innholdet i dette feltet brukes som teksten som skal vises for dette innholdet i navigasjonen.</p>
    </li>

    <li>Vis i meny
      <p>Ofte er det nyttig å ha innholdselementer til spesielle formål (for eksempel å vise nettkart, søkeresultater, påloggingsskjemaer osv.) som ikke vises <em> (som standard)</em> i navigasjonsmenyer. Denne egenskapen gjør at hvert innholdselement kan skjules for navigasjonselementer, med mindre det overstyres andre steder.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Tittelattributt
      <p>Denne egenskapen definerer en valgfri tekststreng som kan brukes til å vise tilleggsinformasjon for innholdet i navigasjonen. Det brukes vanligvis i & quot; tittelen & quot; attributt for koblingen som genereres i navigasjonsmenyer.</p>
      <p>Nettstedsutvikleren har muligheten til å vise disse dataene annerledes, eller ignorere dem fullstendig ved å endre den aktuelle navigasjonsmenymalen. I tillegg kan disse dataene vises på sideinnholdet ved å endre den aktuelle sidemalen. Denne egenskapen er kanskje ikke viktig for innholdselementer på nettstedet ditt.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

<li>Tilgangsnøkkel
      <p>Denne egenskapen definerer et valgfritt tilgangsnøkkeltegn <em> vanligvis bare ett eller to tegn</em> som kan brukes i navigasjonsmenyer for å raskt få tilgang til dette innholdselementet i navigasjonen. Dette er en nyttig funksjon når du bygger tilgjengelige navigasjoner.</p>
      <p>Nettstedsutvikleren har fullstendig mulighet til å inkludere eller ekskludere bruken av denne egenskapen i sine navigasjonsmaler. Og det kan være at det ikke er nødvendig for nettstedet ditt.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Kategoriindeks
      <p>Denne egenskapen brukes til å spesifisere et heltallindeks for hjelpemann i navigasjonen til dette innholdselementet i menyer. Det er nyttig når du oppretter tilgjengelige nettsteder.</p>
      <p>Nettstedsutvikleren har fullstendig mulighet til å inkludere eller ekskludere bruken av denne egenskapen i sine navigasjonsmaler. Og det kan være at det ikke er nødvendig for nettstedet ditt.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Target
      <p>Denne egenskapen brukes til å spesifisere & quot; mål & quot; attributt i lenker til innholdselementer. Det lar deg lage navigasjoner som kan åpne innholdssider i forskjellige nettleservinduer eller faner.</p>
      <p>Nettstedsutvikleren har fullstendig mulighet til å inkludere eller ekskludere bruken av denne egenskapen i sine navigasjonsmaler. Og det kan være at det ikke er nødvendig for nettstedet ditt.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Url
      <p>Denne egenskapen brukes til å spesifisere en primær URL til dette innholdet. Brukere kan spesifisere en komplett bane eller en enkel flat streng. <em> (dvs.: sti / til / min side eller nøkkelordstuffedpageurl)</em>. Denne egenskapen (hvis spesifisert) brukes når du bygger navigasjoner og andre koblinger til dette innholdselementet, når & quot; ganske URLer & quot; er aktivert i config.php. Hvis ikke spesifisert, kontrollerer sidealiaset og andre innstillinger den primære ruten til innholdselementet.</p>
      <p>For SEO-formål er det viktig å merke seg at dette bare er en primær URL <em> (rute)</em> til innholdselementene. Besøkende på nettstedet kan fremdeles navigere til dette innholdet på andre måter, dvs. mysite.com/index.php?page=alias eller mysite.com/random/random/alias eller mysite.com/alias. Nettsteder som er opptatt av rangeringer av søkemotorer, bør sikre at & lt; linken rel = & quot; kanonisk & quot; & gt; -koden er riktig konfigurert i sidemaler.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Aktiv <em> (dvs.: deaktivert)</em>
      <p>Denne egenskapen brukes til å indikere om denne innholdselementet i det hele tatt kan navigeres.</p>
      <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

<li>Secure (HTTPS)
<p>Denne egenskapen brukes til å indikere om dette innholdet skal få tilgang til ved hjelp av HTTPS-protokollen. På et nettsted som er konfigurert riktig for HTTPS, hvis denne attributtet er angitt for et innholdselement, og det blir gjort et forsøk på å få tilgang til denne siden via den usikre HTTP-protokollen, vil brukeren bli omdirigert til samme side ved hjelp av den sikrere HTTPS-protokollen. I tillegg, hvis dette flagget er satt, vil noen koblinger til dette innholdet spesifisere HTTPS-protokollen.</p>
        <p>Det er viktig å vite at innholdselementer uten det sikre flaggsettet fremdeles kan navigeres til å bruke HTTPS-protokollen, og ingen viderekoblinger vil finne sted. Derfor, for søkemotorrangeringsformål, bør den kanoniske lenken konfigureres riktig i hver sidemal.</p>
    </li>

    <li>Cachable
<p>Denne egenskapen spesifiserer om den kompilerte formen for dette innholdselementet kan bufres på serveren for å redusere serverbelastningen <em> (hvis smart cache er aktivert i globale innstillinger)</em> OG om nettleseren kan cache denne siden < em> (hvis hurtigbufring av nettlesere er aktivert i globale innstillinger)</em>. For stort sett statiske nettsteder som muliggjør smart cache og nettleserbufring, kan du redusere serverbelastningen betydelig og forbedre den generelle ytelsen til nettstedet.
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Bilde
<p>Denne egenskapen gjør det mulig å knytte et tidligere opplastet bilde til dette innholdet. Redaktører kan velge en bildefil fra katalogen for opplastinger / bilder. Dette bildet kan vises på den genererte HTML-siden (hvis relevant), eller brukes når du bygger navigasjonen.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>miniatyr
        <p>Denne egenskapen gjør det mulig å knytte et tidligere opprettet miniatyrbilde til dette innholdet. Redaktører kan velge en miniatyrfil fra katalogen for opplastinger / bilder. Denne miniatyrbildet kan vises på den genererte HTMLO-siden, eller brukes når du bygger navigasjonen.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Eier
        <p>Eieregenskapen er et rullegardinfelt som indikerer hvilken admin-brukerkonto som har hovedansvaret for innholdselementet. Som standard er eieren av innholdselementet brukeren som først opprettet det. Brukere med betydelig tillatelse kan tildele eierskapet til en vare til en annen bruker.</p>
    </li>

    <li>Ytterligere redaktører
        <p>Denne egenskapen spesifiserer en liste over andre administratorbrukere eller administratorgrupper som har lov til å redigere dette innholdet. Det implementeres som et flervalgsfelt. Igjen kan det hende at brukere med begrensede tillatelser ikke har muligheten til å justere denne egenskapen.</p>
    </li>

    <li>Design
        <p>Egenskapen gjør det mulig å knytte et design til innholdselementet. Et design brukes til å bestemme stilarkene og andre elementer som bidrar til utseendet til innholdselementer. Designet er assosiert med forskjellige maler. Endring av designegenskaper kan føre til at malegenskapen automatisk endres. Som standard er & quot; standarddesign & quot; valgt i Design Manager er valgt her. Noen begrensede redaktører har kanskje ikke muligheten til å justere denne egenskapen.</p>
    </li>

<li>Mal
        <p>Sidemalen-egenskapen brukes til å bestemme den generelle utformingen av innholdselementet (for de innholdselementene som genererer HTML). Det bestemmer også bruken av metakoder og innholdsblokker. Endring av denne malen vil oppdatere siden og vise passende innholdsegenskaper (blokker) som er spesifisert i den nylig valgte malen.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>søkbar
        <p>Denne egenskapen kontrollerer om innholdsegenskapene til dette innholdselementet kan indekseres av søkemodulen.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Deaktiver WYSIWYG
        <p>Denne egenskapen vil deaktivere WYSIWYG-redigereren for alle innholdsblokkene på dette innholdet. Dette overstyrer alle innstillinger på innholdsblokkene og eventuell brukerinnstilling. Dette er nyttig for innholdselementer som inneholder ren logikk i innholdsblokkene, eller kaller strengt tatt andre moduler. Dette forhindrer at logikken eller utdataene fra modulene blir utført ved stylingen som er injisert av WYSIWYG.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Sidemetadata
        <p>Hovedformålet med denne egenskapen er å injisere metaegenskaper i & lt; head & gt; delen av den gjengitte HTML-siden. Vanligvis er det nyttig å injisere en metabeskrivelseskode.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>Sidedata
        <p>Denne egenskapen brukes først og fremst til å sette inn data, eller logikk i smarty-prosessen for bruk av sidemalen. Det er et avansert felt for bruk i fleksible oppsett som endrer oppførsel dynamisk.</p>
        <p>Brukere med begrensede tillatelser har kanskje ikke muligheten til å justere eller spesifisere denne egenskapen.</p>
    </li>

    <li>ekstra1, ekstra2 og ekstra3 </li>
        <p>Ytterligere egenskaper for bruk i enten å vise data eller påvirke oppførselen til sidemalen.</p>
    </li>
  </Ul>';
$lang['help_designmanager_help'] = '<h3>Hva er dette?</h3>
<p>Design Manager er en kjernemodul <em>(en modul distribuert med CMSMS)</em> som forener funksjonalitet for å håndtere utformingen av CMSMS nettsider. Det gir en komplett løsning for å administrere og redigere alle typer smartmaler, og for å organisere malene. Du kan også opprette, redigere, slette og administrere stilark. Maler og stilark kan deretter organiseres i "Designer".</p>

<h3>Hva er et "Design" ?</h3>
<p>Et design er en slags beholder. Den inneholder en løs sammenslutning av stilark og maler. Det gjør det mulig å administrere alle stilarkene og malene som kreves for å implementere et utseende. Motiver kan eksporteres til en enkelt fil som deles og importeres fra en enkelt fil.</p>
<p>Hver innholdssider som gjengir HTML er forbundet med et design for å finne ut hvilke stilark som skal brukes, og i hvilken rekkefølge. I tillegg er hver innholdsside som gjengir HTML forbundet med en mal; selv om malen ikke trenger å være tilknyttet den spesifiserte designen.</p>
<p>Maler og stilark trenger ikke å bli assosiert med et design, men er bare maler og stilark som er forbundet med et design eksporteres med design.</p>

<h3>Hva er maltyper?</h3>
  <p>Mal typer er en måte å løst organisere maler på. Noen funksjoner kan vise lister med maler som er av en bestemt type for å gjøre valg lettere. For eksempel viser redigerings innholdssiden en liste over "Side" maler.</p>


  <p>Kjernen lager noen få standardmaltyper ved installasjon. Tredjepartsmoduler vil trolig skape mer. Definisjoner av maltype har normalt to deler, opphavsmannen<em>(modulen eller funksjonaliteten som opprettet dem)</em>, og selve navnet. dvs:  Core::Page indikerer en sidemaltype opprettet av kjernen.  News::Summary er navnet på maltypen for maler for nyhetssammendrag. Selv om nettstedutviklere ikke kan opprette nye maltyper, kan de spesifisere typen for en mal når du legger til eller redigerer en mal.
</p>
  <p>De fleste maltypene ha en prototype mal som brukes til å gi et skjelett layout å lage en ny mal av den typen.</p>
  <p>Noen maltyper (for eksempele "Core::Page"maltype støtter tanken om en "default template".  Standardmalen for en type brukes vanligvis av moduler slik at en mal av en bestemt type kan brukes i tilfelle at en bestemt mal ikke heter. I tillegg er standardmalen av typen "Core::Page" brukes som standardmal når du oppretter et nytt innholdselement av typen "Content"</p>
  <p>Administrasjonsgrensesnittet i Design Manager gjør det mulig å filtrere maler etter opphavsmann, eller deres type for enkelt å finne maler å redigere eller administrere.</p>

  <h4>Generiske maler <em>(tidligere Global Content Blocks)</em></h4>
    <p>En standard maltype heter "Core::generic".  Dette er en generisk mal som kan brukes til hva som helst. Den erstatter "Global Content Blocks" <em>(GCB)</em> ffra tidligere versjoner av CMSMS.</p>

<h3>Hva er "Categories" for ?</h3>
  <p>Kategorier er en metode nettstedutviklere kan bruke til å organisere maler ytterligere. Nettstedsutviklere kan opprette, gi nytt navn og slette kategorier og knytte forskjellige maler til dem. Når du administrerer maler, kan nettstedutviklere filtrere maler etter sin kategori.</p>

<h3>Det primære grensesnittet</h3>
  <p>Design Manager Admin-panelet er lokalisert i CMSMS Admin-navigasjon som "Design Manager" under "Layout" seksjonen.</p>
  <p>Administrasjonspanelet for design manager har mange faner. Hver fane tjener et bestemt formål, og kan kreve spesielle rettigheter. Bare en bruker med en av de nødvendige tillatelsene <em>(eller eierskap / tilleggsredigeringsstatus på en eller flere maler)</em> vil være i stand til å se design manager i CMSMS administrasjonskonsollen.</p>
  <ul>
    <li>Fanen med maler
      <p>Kategorien maler gir all funksjonalitet til praktisk og enkelt opprette og administrere maler. Det er synlig for administratorer med "Modify Templates" tillatelse, eller som er eiere eller tilleggsredaktører av en eller flere maler.</p>
      <p>Noen av funksjonene i denne fanen inkluderer:</p>
      <ul>
        <li>Et tabellformat som viser sammendragsinformasjon om hver mal, og som gir praktiske handlinger for å jobbe med maler individuelt eller masse.</li>
        <li>Evnen til å stjele en låst mal</li>
        <li>Avansert filtrering</li>
        <li>Sideinndelingen</li>
      </ul>
    </li>

    <li>The Categories Tab
      <p>The categories tab is visible to all Admin users with the "Modify Templates" permission.  It provides the ability to add, edit, delete, rename and re-order categories.</p>
      <p>When adding or editing a category it is possible to provide a description of the use of the category for reference purposes.</p>
    </li>

    <li>The Template Types Tab
      <p>This tab is visible to all Admin users with the "Modify Templates" permission.  It provides the ability to edit information about the template type including the prototype template.  And to create a new template of each type.</p>
    </li>

    <li>The Stylesheets Tab
      <p>This tab is visible to all Admin users with the "Manage Stylesheets" permission.  It provides the ability to create, delete, edit, and manage stylesheets.<p>
    </li>

    <li>The Designs Tab
      <p>This admin panel tab is visible to all Admin users with the "Manage Designs" permission.  <em>(Note: users with that permission and no others may not have access to the full functionality of this tab)</em>.
      <p>This tab provides the ability to import, export, create, edit, and delete designs.</p>
    </li>
  </ul>
<h3>Managing Templates</h3>
 <p>The templates tab displays a list of templates matching the current filter <em>(if applied)</em> in a tabular format, with pagination.  Each row of the table represents a single template.  The columns of the table displays summary information about the template, and provides some ability to interact with it.</p>
 <p>A dropdown providing the ability to switch between pages of templates that match the current filter will appear if more than one page of templates match the current filter.</p>
 <p>An options menu exists providing the ability to adjust the current filter, or to create a new template <em>(depending upon permissions)</em>.  The filter dialogue allows filtering the displayed templates by a number of criteria, as well as changing the page limit, and sorting of displayed templates.</p>
   <h4>Table Columns:</h4>
   <ul>
     <li>Id:
       <p>This displays the unique numeric id for the template.  Clicking on the link in this column will bring up the edit template form.  Hovering over the link will display a tooltip with further information about the template.</p>
     </li>
     <li>Name:
       <p>This displays a unique textual name for the template.  Clicking on the link in this column will bring up the edit template form.  Hovering over the link will display a tooltip with further information about the template.</p>
     </li>
     <li>Type:
       <p>This displays the template type.  Hovering over the type name will display a tooltip with further information about the template type.</p>
     </li>
     <li>Design:
       <p>This column displays the design(s) that this template is associated with (if any).  If the template is associated with multiple designs a tooltip will display a list of the first few designs that this template is associated with.</p>
     </li>
     <li>Default:
       <p>This column displays an icon indicating if the template is the default for its type.</p>
     </li>
     <li>Actions:
       <p>Depending upon user privileges there will be one or more icons displayed in this column to perform various actions on, or with the template:</p>
       <ul>
          <li>Edit - Display a form to edit the contents and attributes of the template.</li>
          <li>Copy - Display a form to allow copying the selected template to a new name.  For convenience a default new name will be provided.</li>
          <li>Delete - Display a form to allow deleting the selected template.  Extra confirmation is required for this action as no checks are possible to see if the template is in use by any page, or recursively by any other template.</li>
       </ul>

     </li>
     <li>Multiselect:
	  <p>This column (depending upon permissions) will display a checkbox allowing the selection of multiple templates to perform actions on all of them simultaneously.</p>
     </li>
   </ul>
   <h4>Bulk Actions:</h4>
    <p>This is a dropdown with options <em>(currently only delete)</em> to perform on multiple templates at one time.  Use extreme caution when performing bulk actions as doing so could severely effect a working website.</p>
  <h4>Editing Templates</h4>
    <p>The edit template form is a complex form that allows management of all of the attributes of a template.  For convenience the form is divided into numerous tabs.</p>
    <p>This form supports the "dirtyform" functionality to reduce the chances of accidentally losing unsaved changes.  Users will be notified if attempting to navigate away from this page if the template has not been saved.</p>
    <p>This form locks the selected template so that other authorized editors will not have the ability to edit the template at the same time.  This prevents somebody else from accidentally overwriting changes of another editor.</p>
    <ul>
      <li>Name:
         <p>This text string uniquely identifies the template.  The system will generate an error when saving the template if the name is already used on another template.</p>
      </li>
      <li>Template Content:
        <p>This text area displays the actual smarty template.  If a syntax highlighter module is installed, and enabled, and the user has enabled it in his settings, then it will be enabled in this area to provide advanced editing capabilities.</p>
      </li>
      <li>Description:
        <p>This text area provides the ability to describe the purpose of the templates, and notes that may be useful to editors in the future.</p>
      </li>
      <li>Designs:
        <p>Depending upon permission levels, this tab will allow associating the template with zero or more designs.</p>
      </li>
      <li>Advanced:
        <p>This tab displays fields that allow specifying the template category, its type, and whether it is the default template for the type.  This tab is only available with the appropriate permissions.</p>
      </li>
      <li>Permissions:
        <p>If the user account is the owner of the template, or has the "Modify Templates" permissions this tab will allow changing the ownership of the template, and/or specifying additional editors.</p>
      </li>
      <li>Set All Pages:
        <p>Users with the "Modify Templates" permission will see a button which will allow setting all content pages to use this template.</p>
      </li>
    </ul>
<h3>Managing Categories</h3>
  <p>The "Modify Templates" permission is required to see this tab, and its associated actions.</p>
  <p>The categories tab is a simple interface that allows creating, editing, removing and re-ordering categories.  Categories can be re-ordered by dragging and dropping them into the desired order.</p>
  <p>Editing a category allows specifying a description for the category.  The description is useful for keeping a note as to the purpose of the category.</p>
<h3>Managing Template Types</h3>
  <p>The "Modify Templates" permission is required to see this tab, and its associated actions.</p>
  <p>Users with sufficient privilege can adjust the prototype template, and description for each template type.  The prototype template will be used as the default contents for the template when creating a new template of that type.</p>
<h3>Managing Stylesheets</h3>
    <p>The stylesheets tab is available to users with the "Manage Stylesheets" permission. It displays a paginated list of all stylesheets matching the current filter <em>(if applied)</em> in a tabular format.  Each row of the table represents a single stylesheet.  The columns of the table displays summary information about the stylesheet and provides some ability to interact with it.</p>
  <p>A dropdown providing the ability to switch between pages of stylesheets that match the current filter will appear if more than one page of stylesheets match the current filter.</p>
  <p>An options menu exists providing the ability to adjust the current filter, or to create a new stylesheet <em>(depending upon permissions).</em>  The filter dialogue allows filtering, sorting, and paginating the displayed stylesheets by a number of criteria.</p>
  <h4>Table Columns:</h4>
  <ul>
    <li>Id:
     <p>This displays a link containing unique numeric id for the stylesheet.   Clicking on this link will display the edit stylesheet form.  Hovering over the link will display a tooltip with further information about the stylesheet.</p>
    </li>
    <li>Name:
     <p>This displays the unique textual name for the stylesheet as a link.  Clicking on this link will display the edit stylesheet form.  Hovering over the link will display a tooltip with further information about the stylesheet.</p>
    </li>
    <li>Design:
      <p>This column displays the design(s) that this stylesheet is associated with (if any).  If the stylesheet is associated with multiple designs a tooltip will display a list of the first few designs.</p>
    </li>
    <li>Modified Date:
      <p>This column displays the date that the stylesheet was last modified.</p>
    </li>
    <li>Actions:
      <ul>
        <li>Edit - Clicking on this icon will display the edit stylesheet form.</li>
        <li>Delete - Clicking on this icon will display a form to allow deleting the stylesheet.  Extra confirmation is required for this action.</li>
      </ul>
    </li>
    <li>Multiselect:
      <p>This column displays a checkbox allowing the selection of multiple stylesheets to perform bulk actions on all of them simultaneously.</p>
    </li>
  </ul>
  <h4>Bulk Actions:</h4>
    <p>This mini form contains a dropdown with options <em>(currently only delete)</em> to perform on the selected stylesheets.  Use extreme caution when performing bulk actions as doing so could severely effect a working website.</p>
  <h4>Editing Stylesheets</h4>
    <p>The edit stylesheet form is a complex form that allows management of all of the attributes of a stylesheet.  for convenience the form is divided into numerous tabs.  It supports the "dirtyform" functionality to reduce the chances of accidentally losing unsaved changes, and supports locking to prevent other authorized editors from accidentally overwriting changes.</p>
    <p>Here are some of the attributes of a stylesheet that can be edited:</p>
    <ul>
      <li>Name:
        <p>This text string uniquely identifies the stylesheet.  The system will generate an error when saving the stylesheet if the name is already used by another stylesheet.</p>
      </li>
      <li>Stylesheet Content:
        <p>This text area displays the actual CSS code.  If a syntax highlighter module is installed, supports hi-lighting CSS code, is enabled, and the user has enabled it in his settings, then it will be enabled in this area to provide advanced editing capabilities.</p>
      </li>
      <li>Media Types <em style="color: red;">(deprecated)</em>:
        <p>This tab provides numerous checkbox allowing you to select media types to associate with the stylesheet.  It is preferred to use media queries instead, and this functionality may be removed at a later date.</p>
      </li>
      <li>Media Query:
        <p>This tab provides a text area where a media query can be associated with the stylesheet.</p>
      </li>
      <li>Description:
        <p>The text area in this tab provides the ability to describe the purpose of the stylesheets, and any notes that may be useful to editors in the future.</p>
      </li>
      <li>Designs:
        <p>This tab provides the ability to associate the stylesheet with one or more designs.  If any new design associations are detected this stylesheet will be placed at the end of the stylesheet list for that design.</p>
      </li>
    </ul>
<h3>Managing Designs</h3>
  <p>The designs tab is available to users with the "Manage Designs" permission.  It displays a list of all of the known designs in a tabular format.  Each row of the table represents a single design.  The columns of the table displays summary information about the design and provides some ability to interact with it.</p>
  <p>This tab does not provide filtering, pagination, or bulk actions as it is intended that the number of designs associated with a website should normally be kept small and manageable.</p>
  <p>An options menu exists providing the ability to create a new design, or to import a design from XML format.</p>
  <h4>Table Columns</h4>
  <ul>
    <li>Id:
      <p>This column displays a link containing the unique numeric id for the design.  Clicking on this link will display the edit design form.</p>
    </li>
    <li>Name:
      <p>This column displays a link containing the name for the design.  Clicking on this link will display the edit design form.</p>
    </li>
    <li>Default:
      <p>This column an icon represent whether or not this design is the "default" design.  The default design is selected first when creating a new content item of type "Content Page" and may be used for other purposes.  Only one design can be the default.</p>
    </li>
    <li>Actions:
      <p>This column displays various links and icons representing actions that can be performed with designs:</p>
      <ul>
        <li>Edit - Display a form to allow editing the design.</li>
        <li>Export - Export the design to an XML file that can be imported into other websites.</li>
        <li>Delete - Display a form that asks for confirmation about deleting the design.</li>
      </ul>
    </li>
  </ul>
  <h4>Editing Designs:</h4>
    <p>The edit design form is a complex form that allows management of all of the attributes of a design.  The form is divided into numerous tabs.  Unlike editing stylesheets and templates, this form does not support "dirtyform" or locking functionality.</p>
    <p>Some of the attributes of a design that can be edited are:</p>
    <ul>
      <li>Name:
      </li>
      <li>Templates:
        <p>This tab allows selecting different templates to associate with the design. You can drag and drop templates between the "Available Templates" list and the "Attached Templates" list and to order templates within the attached list. At this time, ordering of templates within the attached template list is not significant.</p>
      </li>
      <li>Stylesheets:
	  <p>This tab allows selecting different stylesheets to associate with the design. You can drag and drop stylesheets between the "Available Stylesheets" list and the "Attached Stylesheets" list and to order stylesheets within the attached list.  The order of stylesheets within the attached list determines the order that they will be included in the rendered page content for content items of type "Content Page".</p>
      </li>
      <li>Description:
        <p>This tab provides a free form text area where a description of the design, and additional notes can be entered.  the description is also useful to other users when deciding to share a design.</p>
      </li>
    </ul>
  <h4>Importing Designs</h4>
     <p>The Design Manager module is capable of importing XML themes that were exported from CMSMS Design Manager, or from the older CMSMS theme manager.  It expands the uploaded XML file, and extracts templates, stylesheets, and other useful information from the file.  It also performs some minor transformation on the extracted data to try to adjust for overlapping names, etc.</p>
     <p>The import process is divided into a few steps:</p>
     <ul>
      <li>Step 1: Upload the file:
        <p>This step manages uploading the user selected XML file and validating its contents.  This step is vulnerable to PHP limits for file size, memory limits, and time limits for form processing.  You may need to increase those limits on overly restricted sites when uploading larger theme files.</p>
        <p>Once the XML file has passed the validation process, it is copied to a temporary location for processing in step 2.</p>
      </li>
      <li>Step 2: Verification:
        <p>The second step is for verifying and previewing the new design that will be created from the XML file.  From here you can display, and edit various aspects of the design or theme.</p>
     </ul>
  <h4>Deleting Designs</h4>
<h3>Using Templates</h3>
<h3>Options and Preferences</h3>
<h3>Upgrade Notes</h3>';
$lang['help_myaccount_admincallout'] = 'Hvis aktivert administrative bokmerker <em>(shortcuts)</em> vil bli aktivert slik at du kan administrere en liste over ofte brukte handlinger i administrasjonskonsollen .';
$lang['help_myaccount_admintheme'] = 'Velg et administrasjons tema å bruke. Ulike administrasjons temaer har forskjellige menyoppsett, fungerer bedre for mobile skjermer, og har ulike tilleggsfunksjoner .';
$lang['help_myaccount_ce_navdisplay'] = 'Velg hvilke innholdsfeltet skal vises i innholdslister. Alternativene inkluderer sidetittelen , eller menytekst . Hvis " Ingen" er valgt, så området preferanse vil bli brukt';
$lang['help_myaccount_dateformat'] = 'Spesifiser et datoformat som skal brukes når datoer vises. Dette formatet bruker <a href="https://php.net/manual/en/function.strftime.php" class="external" target="_blank">strftime</a> format. <strong>Merk:</strong> noen tredjeparts tillegg kan ikke følge denne innstillingen.</strong>';
$lang['help_myaccount_dfltparent'] = 'Angi standard siden for å opprette en ny innholdsside . Bruken av denne innstillingen avhenger også på innholdsredigeringstillatelser.<br/><br/>Bor ned til den valgte standard ordnede siden ved å velge den øverste overordnede , og påfølgende ordnede sider fra den medfølgende rullegardinlistene .<br/><br/>Tekstfeltet til høyre vil alltid indikere hvilken side som er valgt.';
$lang['help_myaccount_email'] = 'Angi en e-postadresse. Dette brukes for glemt passord funksjonalitet, og for noen typer e-post sendt av systemet (eller tilleggs moduler).';
$lang['help_myaccount_enablenotifications'] = 'Hvis aktivert, vil systemet vise ulike meldinger om ting som må tas vare på i navigasjon';
$lang['help_myaccount_firstname'] = 'Eventuelt oppgi fornavn. Dette kan brukes i Admin tema, eller å personlig håndtere e-post til deg';
$lang['help_myaccount_hidehelp'] = 'Hvis dette er aktivert vil systemet skjule modul hjelpe lenker fra administrasjonskonsollen . I de fleste tilfeller for å gi hjelp med moduler er rettet mot nettstedet utviklere og kan ikke være nyttig for innhold redaktører.';
$lang['help_myaccount_homepage'] = 'Du kan velge en side som du laster automatisk til når du logger på CMSMS administrasjonskonsollen . Dette kan være nyttig når du primært bruker en funksjon.';
$lang['help_myaccount_ignoremodules'] = 'Hvis Admin varslinger er aktivert kan du velge å ignorere meldinger fra enkelte moduler';
$lang['help_myaccount_indent'] = 'Dette alternativet vil rykke innholdet listevisning  for å illustrere den overordnedes  og underordnedes siden forhold';
$lang['help_myaccount_language'] = 'Velg språket som skal vises for Admin grensesnittet. Listen over tilgjengelige språk kan variere på hver CMSMS installere';
$lang['help_myaccount_lastname'] = 'Eventuelt spesifisere ditt etternavn. Dette kan brukes i Admin tema, eller å personlig håndtere e-post til deg';
$lang['help_myaccount_password'] = 'Skriv inn et unikt, og sikkert passord for dette nettstedet. Passordet bør være mer mer enn seks tegn, og bør bruke en kombinasjon av store bokstaver, små bokstaver, ikke alfanumerisk , og sifre. Vennligst la dette feltet stå tomt hvis du gjør noe som ikke trenger passord endring.';
$lang['help_myaccount_passwordagain'] = 'For å redusere feil, må du skrive inn passordet ditt på nytt. La dette feltet stå tomt hvis du ikke ønsker å endre passordet.';
$lang['help_myaccount_syntax'] = 'Velg hvilke syntax highlighting modul for å bruke når du redigerer HTML, eller Smarty kode. Listen over tilgjengelige moduler kan endres avhengig av hva nettstedet administratoren har konfigurert';
$lang['help_myaccount_username'] = 'Brukernavnet ditt er unikt navn på CMSMS Admin panelet. Bruk bare alfanumeriske tegn og understrek';
$lang['help_myaccount_wysiwyg'] = 'Velg hvilken WYSIWYG <em>(Det du ser er hva du får)</em> Modul som skal brukes når du redigerer HTML-innhold. Du kan også velge "None"Hvis du er komfortabel med html. Listen over tilgjengelige WYSIWYG-redaktører kan endres avhengig av hva nettstedet administrator har konfigurert.';
$lang['settings_adminlog_lifetime'] = 'Denne innstillingen angir maksimumstiden som oppføringer i Admin loggen bør beholdes.';
$lang['settings_autoclearcache'] = 'Dette alternativet lar deg angi maksimal alder <em>(I dager)</em> Før filer i buffer mappen blir slettet.<br/><br/>Dette alternativet er nyttig for å sikre at arkiverte filer regenereres periodisk, og at filsystemet ikke blir forurenset av gamle og unødvendige filer. En ideell verdi for dette feltet er 14 eller 30 dager.<br /><br /><strong>Merk:</strong>Cached filer blir ryddet maksimalt en gang per dag.';
$lang['settings_autocreate_flaturls'] = 'Hvis SEF/vakre nettadresser er aktivert, og alternativet for å opprette nettadresser er aktivert, angir dette alternativet at de automatisk opprettede nettadressene skal være flate <em>(i.e: Identisk med sidens alias)</em>.  <strong>Merk:</strong> De to verdiene trenger ikke å forbli identiske, URL-verdien kan endres for å være annerledes enn sidens alias i senere sidedigeringer';
$lang['settings_autocreate_url'] = 'Når du redigerer innholdssider , bør SEF / pen webadresser bli automatisk laget? Auto skape webadresser vil ikke ha noen effekt dersom pene webadresser ikke er aktivert i CMSMS config.php filen.';
$lang['settings_badtypes'] = 'Velg hvilke innholdstyper du vil fjerne fra rullegardinmenyen for innholdstype når du redigerer eller legger til innhold. Denne funksjonen er nyttig hvis du ikke vil at redaktører skal kunne lage bestemte typer innhold.  Bruk CTRL + Klikk for å velge, avmarkere elementer. Å ha ingen utvalgte elementer vil indikere at alle innholdstyper er tillatt. <em>(gjelder for alle brukere)</em>';
$lang['settings_basicattribs2'] = 'Dette feltet lar deg spesifisere hvilke innholdsegenskaper som brukere uten "administrer alt innhold" tillatelse kan endres.<br />Denne funksjonen er nyttig når du har innholdsredaktører med begrenset tillatelse og vil tillate redigering av ytterligere innholdsegenskaper.';
$lang['settings_browsercache'] = 'Gjelder bare å bufres sider, indikerer denne innstillingen at nettlesere skal få lov til å bufres sidene for en tidsperiode. Hvis aktivert gjenta besøkende til nettstedet ditt, kan ikke umiddelbart se endringer i innholdet på sidene, men å aktivere dette alternativet kan alvorlig forbedre ytelsen til nettstedet ditt.';
$lang['settings_browsercache_expiry'] = 'Angi hvor lang tid (i minutter) som nettlesere bør bufre sider for. Konfigurere denne verdien til Verdien 0 skrur av funksjonaliteten. I de fleste tilfeller bør du angi en verdi som er større enn 30';
$lang['settings_checkversion'] = 'Hvis aktivert, vil systemet utføre en daglig sjekk for en ny utgave av CMSMS';
$lang['settings_contentimage_path'] = 'Denne innstillingen brukes når en sidemal inneholder {content_image} stikkord.  Den katalogen som er spesifisert her, brukes til å gi et utvalg av bilder som skal knyttes til stikkordet.<br /><br />I forhold til opplastingsbanen, angi et katalognavn som inneholder stiene som inneholder filer for {content_image} stikkord. Denne verdien brukes som standard for dir-parameteren';
$lang['settings_cssnameisblockname'] = 'Hvis aktivert, navnet på innholdsblokken <em>(id)</em> vil bli brukt som en standardverdi for cssname-parameteren for hver innholdsblokk.<br/><br/>Dette er nyttig for WYSIWYG redaktører. Stilarket (block name) kan lastes inn av WYSIWYG-editoren og gi et utseende som er nærmere det på forsiden av nettsiden.<br/><br/><strong>Merk:</strong> WYSIWYG Redaktører kan ikke lese informasjon fra de medfølgende stilarkene (hvis de finnes), avhengig av deres innstillinger og muligheter.';
$lang['settings_disablesafemodewarn'] = 'Dette alternativet deaktiverer en advarsel hvis CMSMS oppdager det <a href="https://php.net/manual/en/features.safe-mode.php" class="external" target="_blank">PHP Sikker Modus</a> har blitt oppdaget.<br /><br /><strong>Note:</strong> Sikker modus har blitt avviklet fra PHP 5.3.0 og fjernet for PHP 5.4.0. CMSMS støtter ikke operasjonen i sikker modus, og vårt supportteam gir ingen teknisk assistanse for installasjoner der sikker modus er aktiv';
$lang['settings_enablenotifications'] = 'Dette alternativet vil aktivere varsler blir vist øverst på siden i hvert Admin forespørsel. Dette er nyttig for viktige varsler om system som kan kreve handling fra brukeren. Det er mulig for hver Admin bruker å slå av varsler i sine preferanser.';
$lang['settings_enablesitedown'] = 'Dette alternativet lar deg veksle nettstedet er "nede for vedlikehold" for nettstedet besøkende';
$lang['settings_enablewysiwyg'] = 'Aktiver WYSIWYG-editor i tekstområdet nedenfor';
$lang['settings_imagefield_path'] = 'Denne innstillingen brukes når du redigerer innhold. Den katalogen som er spesifisert her, brukes til å gi en liste over bilder hvorfra du skal knytte et bilde til innholdssiden.<br/></br/>I forhold til bildeopplastings-banen, angi et katalognavn som inneholder stiene som inneholder filer for bildefeltet';
$lang['settings_lock_timeout'] = 'Skriv inn en standardverdi (i minutter) for låser til tidsavbrudd. Denne brukes hvis et stykke av funksjonalitet ikke gir en tilpasset lås tidsavbrudd verdi';
$lang['settings_mailprefs_from'] = 'Dette alternativet styrer <em>standard<em> adresse som CMSMS vil bruke til å sende e-postmeldinger. Dette kan ikke bare være en e-postadresse. Den må samsvare med det domenet CMSMS leverer. Angi en personlig e-postadresse fra et annet domene er kjent som "<a href="https://en.wikipedia.org/wiki/Open_mail_relay" class="external" target="_blank">relaying</a>" og vil trolig føre til at e-post ikke sendes, eller ikke mottas av mottakerens e-postserver. Et typisk godt eksempel på dette feltet er noreply@mydomain.com';
$lang['settings_mailprefs_fromuser'] = 'Her kan du angi et navn for å bli assosiert med den e-postadressen som er angitt ovenfor. Dette navnet kan være hva som helst, men det er rimelig samsvarer med e-postadressen. dvs: " Ikke svar"';
$lang['settings_mailprefs_mailer'] = 'Dette valget styrer hvordan CMSMS vil sende e-post. Bruk PHPs postfunksjon, sendmail, eller ved å kommunisere direkte med en SMTP-server.<br/><br/>Det "mail" alternativet skal fungere på de fleste delte verter, men det nesten helt sikkert ikke vil fungere på de fleste selv vert Windows-installasjoner.<br/><br/>Det "sendmail" alternativet bør fungere på de fleste riktig konfigurert selvbetjente Linux-servere. Det kan imidlertid ikke fungere på delte verter.<br/><br/>SMTP-alternativet krever konfigurasjonsinformasjon fra verten din.';
$lang['settings_mailprefs_sendmail'] = 'Hvis du bruker"sendmail" mailer-metoden, må du angi hele banen til sendmail-binærprogrammet. En typisk verdi for dette feltet er "/usr/sbin/sendmail".  Dette alternativet brukes vanligvis ikke på Windows-verter.<br/><br/><strong>Merk:</strong> Hvis du bruker dette valget, må verten tillate popen og pclose PHP-funksjonene som ofte er deaktivert på delte verter.';
$lang['settings_mailprefs_smtpauth'] = 'Når du bruker SMTP-maileren, indikerer dette alternativet at SMTP-serveren krever godkjenning for å sende e-post. Du må da spesifisere <em>(på et minimum)</em> et brukernavn og passord. Verten din bør angi om SMTP-godkjenning er nødvendig, og hvis så gi deg et brukernavn og passord, og eventuelt en krypteringsmetode.<br/><br/><strong>Merk:</strong> SMTP-godkjenning kreves dersom domenet ditt bruker Google-apper for e-post.';
$lang['settings_mailprefs_smtphost'] = 'Når du bruker SMTP mailer, dette alternativet Spesifisere vertsnavnet <em>(eller IP-adresse)</em> på SMTP-serveren til å brukes når du sender e-post. Du trenger å kontakte din vert for riktig verdi.';
$lang['settings_mailprefs_smtppassword'] = 'Dette er passordet for tilkobling til SMTP-serveren hvis SMTP-autentisering er aktivert.';
$lang['settings_mailprefs_smtpport'] = 'Når du bruker SMTP mailer dette alternativet angir heltall portnummer for SMTP-serveren. I de fleste tilfeller denne verdien er 25, selv om du kanskje må kontakte din vert for riktig verdi.';
$lang['settings_mailprefs_smtpsecure'] = 'Dette alternativet, når du bruker SMTP-autentisering angir en krypteringsmekanisme til bruk ved kommunikasjon med SMTP-serveren. Verten din bør gi denne informasjonen hvis SMTP-autentisering er nødvendig.';
$lang['settings_mailprefs_smtptimeout'] = 'Når du bruker SMTP-maileren, angir dette alternativet antall sekunder før et forsøk på tilkobling til SMTP-serveren vil mislykkes. En typisk verdi for denne innstillingen er 60.<br/><br/><strong>Merk:</strong> Hvis du trenger en lengre verdi her, indikerer det sannsynligvis et underliggende DNS-, rutingen eller brannmurproblem, og du må kanskje kontakte verten din.';
$lang['settings_mailprefs_smtpusername'] = 'Dette er brukernavnet for å koble til SMTP-serveren hvis SMTP-autentisering er aktivert.';
$lang['settings_mailtest_testaddress'] = 'Angi en gyldig e-postadresse som skal motta test epost';
$lang['settings_mandatory_urls'] = 'Hvis SEF/pene URLer er aktivert, angir dette alternativet om side urls er et obligatorisk felt i innholdet editor.';
$lang['settings_nosefurl'] = 'For å konfigurere <strong>H</strong>ver <strong>M</strong>otor <strong>V</strong>ennlig <em>(pene)</em> URL-er Må du redigere noen linjer i config.php filen din og muligens redigere en .htaccess-fil eller konfigurasjonen av webservere.   Du kan lese mer om hvordan du konfigurerer pene urls <a href="https://docs.cmsmadesimple.org/configuration/pretty-url" class="external" target="blank"><u>her</u></a> »';
$lang['settings_pseudocron_granularity'] = 'Denne innstillingen angir hvor ofte systemet vil forsøke å håndtere regelmessig planlagte oppgaver.';
$lang['settings_searchmodule'] = 'Velg modul som skal brukes til å indeksere ord for å søke, og vil gi stedet søkefunksjoner';
$lang['settings_sitedownexcludeadmins'] = 'Må vise nettsiden til Administrator brukere innlogget til CMSMS administrasjonskonsollen';
$lang['settings_sitedownexcludes'] = 'Må vise nettsiden til disse IP-adressene';
$lang['settings_sitedownmessage'] = 'Meldingen til din nettside, når nettsiden er nede for vedlikehold';
$lang['settings_smartycaching'] = 'Når den er aktivert, vil produksjonen fra forskjellige plugins bli lagret for å øke ytelsen. I tillegg blir de fleste porsjonene av kompilerte maler bufret. Dette gjelder bare utdata på innholdssider merket som cachable, og bare for brukere uten administrasjon. Merk, denne funksjonaliteten kan forstyrre oppførselen til enkelte moduler eller plugins, eller plugins som bruker ikke-inline-skjemaer.<br/><br/><strong>Merk:</strong> Når Smarty caching er aktivert, globale innholdsblokker <em>(GCBs)</em> Er alltid cached av smarty og brukerdefinerte koder <em>(UDTs)</em>Blir aldri bufret. I tillegg blir innholdsblokkene aldri bufret.';
$lang['settings_smartycompilecheck'] = 'Hvis deaktivert, vil Smarty ikke sjekke modifikasjons datoene for maler for å se om de har blitt endret. Dette kan forbedre ytelsen. Men utfører noen mal endring (eller enda noen endringer i innholdet) kan kreve en buffer rensing';
$lang['settings_thumbfield_path'] = 'Denne innstillingen brukes når du redigerer innhold. Katalogen er angitt her brukes til å gi en liste over bilder for å knytte et miniatyrbilde med innholdssiden.<br/><br/>I forhold til bildeopplastingsbanen, angi et katalognavn som inneholder stiene som inneholder filer for bildefeltet. Vanligvis vil dette være det samme som banen ovenfor.';
$lang['settings_umask'] = 'Den &quot umask" er en octal verdi som brukes til å angi standard tillatelse til nyopprettede filer (dette er brukt for filer i cache katalogen, og opplastede filer For mer informasjon se riktig <a href = "http://en.wikipedia.org/wiki/Umask" class="external" target="_blank">Wikipedia artikkel.</a>';
$lang['siteprefs_lockrefresh'] = 'Dette feltet angir minimumsfrekvensen (i minutter) Ajax basert låsemekanismen skal "kontakt" en lås. En ideell verdi for dette feltet er fem.';
$lang['siteprefs_locktimeout'] = 'Dette feltet angir antall minutter med inaktivitet før en lås blir tidsavbrutt. Etter en lås blir tidsavbrutt, kan andre brukere stjele låsen. For at en lås ikke skal bli tidsavbrutt, må den være "rørt"  før utløps tid. Dette tilbakestiller utløpstid av låsen. I de fleste tilfeller bør en 60 minutters lås være egnet.';
$lang['siteprefs_sitename'] = 'Dette er et lesbart navn på ditt nettsted, i.s: bedrift, klubb, eller organisasjonsnavn';
$lang['siteprefs_frontendlang'] = 'Standardspråket nettstedet ditt viser på frontend. Dette kan endres på en per-side basis ved hjelp av ulike Smarty koder. dvs: <code>{cms_set_language}</code>';
$lang['siteprefs_frontendwysiwyg'] = 'Når WYSIWYG redaktør, er gitt på frontend former, bør hva WYSIWYG modulen brukes? Eller ingen.';
$lang['siteprefs_nogcbwysiwyg'] = 'Dette alternativet vil deaktivere WYSIWYG redaktør på alle globale innholdsblokker uavhengig av brukerinnstillinger , eller for de enkelte globale innholdsblokker';
$lang['siteprefs_globalmetadata'] = 'Dette tekst området gir mulighet til å legge inn metainformasjon som er relevant for alle innholdssider. Dette er et ideelt sted for metakoder som Generator, og forfatter, etc.';
$lang['siteprefs_logintheme'] = 'Velg Admin temaet (fra installerte Admin temaer) som skal brukes til å generere administrator login form, og som standard login tema for nye Admin brukerkontoer . Admin-brukere vil være i stand til å velge deres foretrukne Admin tema fra i brukerinnstillingene panel.';
$lang['siteprefs_backendwysiwyg'] = 'Velg WYSIWYG-editor for nyopprettede Admin brukerkontoer . Admin-brukere vil være i stand til å velge sin foretrukne WYSIWYG-editor fra i brukerens valgpanelet.';
$lang['siteprefs_dateformat'] = '<p>Angi datoformatstrengen i <a href="http://ca2.php.net/manual/en/function.strftime.php" class="external" target="_blank"><u>PHP strftime</u></a> Format som vil bli brukt <em>(som standard)</em> for å vise dato og klokkeslett på nettstedet ditt.</p><p>Admin-brukere kan justere disse innstillingene i brukerinnstillingspanelet.</p><p><strong>Merk:</strong> Noen moduler kan velge å vise tider og datoer annerledes</p>';
$lang['siteprefs_thumbwidth'] = 'Angi en bredde <em>(in pixels)</em> som skal brukes som standard når du genererer miniatyrbilder fra opplastede bildefiler. Miniatyrbilder vises vanligvis i administrasjons-panelet i FileManager-modulen eller når du velger et bilde som skal settes inn i sideinnhold. Noen moduler kan imidlertid bruke miniatyrbildene på nettsidenes frontend.<br/><br/><strong>Merk:</strong> Enkelte moduler kan ha flere innstillinger for hvordan du genererer miniatyrbilder, og ignorer denne innstillingen.';
$lang['siteprefs_thumbheight'] = 'Angi en høyde <em>(in pixels)</em>som skal brukes som standard når du genererer miniatyrbilder fra opplastede bildefiler.  Thumbnails er vanligvis vises i administrasjonspanelet i Filemanager modulen eller når du velger et bilde for å sette inn sideinnhold.  Imidlertidig kan noen moduler bruke miniatyrbildene på nettsidens frontend.<br/><br/><strong>Merk:</strong> Noen moduler kan ha flere preferanser for hvordan å generere miniatyrbilder , og ignorere denne innstillingen.';
?>