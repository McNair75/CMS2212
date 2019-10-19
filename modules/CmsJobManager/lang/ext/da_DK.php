<?php
$lang['created'] = 'Oprettet';
$lang['errors'] = 'Fejl';
$lang['evtdesc_CmsJobManager::OnJobFailed'] = 'Sendes når en opgave bliver fjernet fra køen, fordi opgaven er mislykkedes for mange gange';
$lang['evthelp_CmsJobManager::OnJobFailed'] = '<h4>Parametre:</h4>
<ul>
  <li>"job" - reference til det objekt i en \\CMSMS\\Async\\Job opgave, som er mislykkedes </li>
</ul';
$lang['frequency'] = 'Hyppighed';
$lang['friendlyname'] = 'Håndtering af baggrundsopgaver';
$lang['info_background_jobs'] = 'Dette panel viser en liste med information om alle de p.t. kendte opgaver, som kører i baggrunden. Det er helt normalt, at opgaver ofte dukker op på og forsvinder igen fra listen. Hvis en opgave har en høj fejlrate ELLER aldrig er startet, så kan det betyde, at du er nødt til at undersøge, hvad fejlen skyldes.';
$lang['info_no_jobs'] = 'For øjeblikket er der ingen opgaver i køen';
$lang['jobs'] = 'Opgaver';
$lang['moddescription'] = 'Et modul til håndtering af asynkrone opgaveprocesser.';
$lang['module'] = 'Modul';
$lang['name'] = 'Navn';
$lang['processing_freq'] = 'Maksimal kørsels-hyppighed (i sekunder)';
$lang['recur_120m'] = 'Hver 2. time';
$lang['recur_15m'] = 'Hvert 15. minut';
$lang['recur_180m'] = 'Hver 3. time';
$lang['recur_30m'] = 'Hver halve time';
$lang['recur_daily'] = 'Dagligt';
$lang['recur_hourly'] = 'En gang i timen';
$lang['recur_monthly'] = 'En gang om måneden';
$lang['recur_weekly'] = 'En gang om ugen';
$lang['settings'] = 'Indstillinger';
$lang['start'] = 'Begynd';
$lang['until'] = 'Indtil';
$lang['help'] = '<h3>Hvad er dette?</h3>
<p>Modulet hører til CMSMS\'s kerne og det bibringer funktioner, som kører opgaver asynkront (i baggrunden) mens hjemmesiden håndterer diverse forespørgsler.</p>
<p>CMSMS samt moduler fra 3. hånd kan danne og udføre opgaver, der ikke er afhængige af direkte indgriben fra brugerens side, eller som kan tage noget tid at gennemføre. Dette modul er i stand til at varetage disse opgaver.</p>
<h3>Hvad skal jeg gøre for at bruge det?</h3>
<p>Modulet kræver ikke i sig selv nogen indgriben. Der dannes en simpel rapport med en liste over de opgaver, som modulet for øjeblikket har sat i kø. Opgaverne kan til stadighed dukke op i og forsvinde igen fra køen, så hvis du af og til genindlæser siden, får du et praj om, hvad der foregår bagom din hjemmeside.</p>
<p>Modulet behandler kun opgaver højst en gang i minuttet og mindst en gang hvert 10. minut. Som standard sker behandlingen hvert 3.
 minut. Når det ikke er oftere, er det for at sikre en tilfredsstillende drift af hjemmesider, som de er flest.</p>
<p>Du kan justere hyppigheden ved at tilføje variablen cmsjobmgr_asyncfreq i din hjemmesides konfigurationsfil - config.php. Variablen skal sættes lig med et heltal mellem 0 og 10.</p>
<pre>F.eks.: <code>$config["cmsjobmgr_asyncfreq"] = 5;</code>.</pre>
<p><strong>Bemærk:</strong> Det er ikke muligt at slå de asynkrone processer helt fra. Det skyldes, at nogle af CMSMS\'s kernefunktioner er afhængige af, at disse processer køres.</p>

<h3>Hvad så med opgaver, som giver problemer?</h3>
<p>Fra tid til anden kan det ske, at nogle applikationer opretter opgaver, som mislykkes og afslutter med en eller anden fejl. CmsJobManager vil fjerne opgaven, når den har fejlet et vist antal gange. Herefter kan den oprindelige kode gendanne opgaven. Hvis du kommer ud for en problematisk opgave, som gentagne gange mislykkes, så skyldes det en programmeringsfejl, der bør identificeres og rapporteres i detaljer til udviklerne af applikationen.</p>';
?>