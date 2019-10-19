<?php
$lang['add_profile'] = 'Opret en ny profil';
$lang['can_delete'] = 'Tillad sletning af filer';
$lang['can_mkdir'] = 'Tillad oprettelse af mapper';
$lang['can_upload'] = 'Uploads tilladt';
$lang['changedir'] = 'Skift til mappen';
$lang['clear'] = 'Nulstil';
$lang['confirm_delete'] = 'Er du sikker på, du vil slette dette?';
$lang['create_dir'] = 'Opret en ny mappe';
$lang['dimension'] = 'Dimensioner';
$lang['delete'] = 'Slet';
$lang['delete_profile'] = 'Slet profil';
$lang['edit_profile'] = 'Redigér profil';
$lang['error_ajax_invalidfilename'] = 'Beklager, men filnavnet er ugyldigt';
$lang['error_ajax_fileexists'] = 'Beklager, men der findes allerede en fil eller en mappe med dette navn';
$lang['error_ajax_mkdir'] = 'Der opstod et problem under oprettelse af mappen %s';
$lang['error_ajax_writepermission'] = 'Beklager, men du har ikke skrivetilladelse til denne mappe';
$lang['error_failed_ajax'] = 'Der opstod et problem med en ajax-forespørgsel';
$lang['error_problem_upload'] = 'Beklager, men der opstod et problem under uploadningen';
$lang['error_upload_acceptFileTypes'] = 'Filer af denne type accepteres ikke';
$lang['error_upload_maxFileSize'] = 'Filen er for stor';
$lang['error_upload_minFileSize'] = 'Filen er for lille';
$lang['error_upload_maxNumberOfFiles'] = 'Du uploader for mange filer på én gang';
$lang['err_profile_topdir'] = 'Den angivne overmappe findes ikke';
$lang['filename'] = 'Filnavn';
$lang['filterby'] = 'Sortér efter';
$lang['filepickertitle'] = 'CMSMS filvælger';
$lang['fileview'] = 'Filvisning';
$lang['friendlyname'] = 'Filvælger';
$lang['hdr_add_profile'] = 'Tilføj profil';
$lang['hdr_edit_profile'] = 'Redigér profil';
$lang['HelpPopupTitle_ProfileName'] = 'Profilnavn';
$lang['HelpPopup_ProfileName'] = 'Hver profil bør have et simpelt, unikt navn. Navnene bør alene indeholde alfanumeriske tegn og/eller tegnet underscore.';
$lang['HelpPopupTitle_ProfileCan_Delete'] = 'Tillad sletning af filer';
$lang['HelpPopup_ProfileCan_Delete'] = 'Giv brugerne mulighed for at slette filer under udvælgelsesprocessen';
$lang['HelpPopupTitle_ProfileCan_Mkdir'] = 'Tillad sletning af filer';
$lang['HelpPopup_ProfileCan_Mkdir'] = 'Giv brugerne mulighed for at oprette nye mapper (under den angivne overmappe) under udvælgelses processen';
$lang['HelpPopupTitle_ProfileCan_Upload'] = 'Tillad upload';
$lang['HelpPopup_ProfileCan_Upload'] = 'Giv brugerne mulighed for at uploade filer under udvælgelses processen';
$lang['HelpPopupTitle_ProfileDir'] = 'Overmappe';
$lang['HelpPopup_ProfileDir'] = 'Her kan indtastes en relativ sti til en mappe (relativ i forhold til upload-stien) med henblik på at begrænse uploads til den angivne mappe';
$lang['HelpPopupTitle_ProfileShowthumbs'] = 'Vis miniaturer';
$lang['HelpPopup_ProfileShowthumbs'] = 'Ved tilvalg vises en miniature af billedet, da der dannes miniaturer af billedfiler';
$lang['name'] = 'Navn';
$lang['no_profiles'] = 'Der er endnu ikke defineret nogen profiler. Du kan oprette profiler ved at klikke på knappen ovenfor';
$lang['ok'] = 'OK';
$lang['select_an_audio_file'] = 'Vælg en lydvil';
$lang['select_a_video_file'] = 'Vælg en videofil';
$lang['select_a_media_file'] = 'Vælg en mediefil';
$lang['select_a_document'] = 'Vælg et dokument';
$lang['select_an_archive_file'] = 'Vælg en pakket arkivfil';
$lang['select_a_file'] = 'Vælg en fil';
$lang['select_an_image'] = 'Vælg et billede';
$lang['select_upload_files'] = 'Vælg filer til upload';
$lang['show_thumbs'] = 'Vis miniaturer';
$lang['size'] = 'Størrelse';
$lang['switcharchive'] = 'Vis kun pakkede arkivfiler';
$lang['switchaudio'] = 'Vis kun lydfiler';
$lang['switchfiles'] = 'Vis kun almindelige filer';
$lang['switchgrid'] = 'Vis filerne i en tabel';
$lang['switchimage'] = 'Vis kun billedfiler';
$lang['switchlist'] = 'Vis liste over filer';
$lang['switchreset'] = 'Vis alle filer';
$lang['switchvideo'] = 'Vis kun videofiler';
$lang['th_created'] = 'Oprettet';
$lang['th_default'] = 'Standardvalg';
$lang['th_id'] = 'ID';
$lang['th_last_edited'] = 'Senest redigeret';
$lang['th_name'] = 'Navn';
$lang['th_reltop'] = 'Overmappe';
$lang['title_mkdir'] = 'Opret mappe';
$lang['topdir'] = 'Overmappe';
$lang['type'] = 'Type';
$lang['upload'] = 'Upload';
$lang['youareintext'] = 'Den p.t. aktive mappe (set i forhold til installationens rodmappe)';
$lang['help'] = '<h3>Hvad er dette her?</h3>
<p>Dette modul giver en autoriseret administrator et basalt værktøj, hvormed han/hun kan vælge en fil. Det kan f.eks. være et billede, som skal bruges i et WYSIWYG-felt. Modulet kan også bruges til at knytte et billede eller en miniature til en side eller vedhæfte en PDF-fil til en nyhedsartikel. Endvidere kan modulet være en hjælp for autoriserede brugere i forbindelse med upload og sletning af filer samt oprettelse og fjernelse af undermapper.</p>
<p>Modulet giver desuden mulighed for at der kan oprettes flere profiler med hver deres funktionalitet. Profiler kan anvendes ved brug af plugin-koden <code>{cms_filepicker}</code> eller ved at bruge modulets "udvælg"-handling alt efter, hvordan det bestemmes, at filvælgeren skal opføre sig. Andre modulparametre eller brugertilladelser, kan tilsidesætte de indstillinger, som er defineret for profilen.</p>

<h3>Hvordan bruger jeg modulet?>/h3>
<p>Det er meningen, at dette modul skal bruges i kernen eller i 3. parts moduler via diverse kerne API\'er og via plugin-koden {cms_filepicker}.</p>
<p>Desuden kan dette modul kaldes direkte med koden <code>{cms_module module=FilePicker action=select name=string [profile=string] [type=string] [value=string]}</code>. Se tag\'en {cms_filepicker} for information om typen samt andre parametre.</p>

<h3>Support</h3>
<p>Copyright © 2017, JoMorg og calguy1000. Alle rettigheder forbeholdes.</p>
<p>Dette modul er blevet frigivet ifølge <a href="http://www.gnu.org/licenses/licenses.html#GPL">GNU Public License</a>. Du skal acceptere denne licens, før modulet tages i anvendelse.</p>';
?>