ONLYCARS – YHTENÄINEN ULKOASU, MOBIILITUKI JA UUDELLEENOHJAUKSET

ASENNUS NYKYISEEN TOIMIVAAN PROJEKTIIN
1. Ota varmuuskopio nykyisestä projektista.
2. Kopioi paketin PHP-, HTML-, CSS- ja JavaScript-tiedostot projektikansioon.
   Kopioi myös uudet tiedostot: helpers.php, page.php, post_form.php ja signup.php.
   Tiedostot toimivat yhdessä, joten pelkkä CSS:n tai yhden PHP:n vaihto ei riitä.
3. Säilytä omat database.php-yhteysasetuksesi ja nykyinen kuvat-kansiosi.
   Jos käytät tämän paketin kuvaa, banneri löytyy kuvat-kansiosta samalla nimellä.
4. Avaa sivusto WAMPin localhost-osoitteella. Älä avaa PHP-tiedostoja suoraan levyltä.
5. Päivitä selain Ctrl+F5-näppäimillä, jotta uusi styles2.css latautuu.

Jos edellinen kirjautumis- ja omistajuuspäivitys toimii jo, SQL:ää EI tarvitse
ajaa uudelleen. Vain ensimmäistä asennusta varten: valitse phpMyAdminissa
onlycars-tietokanta ja suorita sql/add_post_ownership.sql. Se lisää tarvittaessa
posts.user_id-sarakkeen. Paketti ei sisällä alkuperäisen tietokannan vientiä.

YHTEINEN ULKOASU
- Etusivu, kirjautuminen, tilin luonti, julkaisun luonti, muokkaus, poisto,
  uloskirjautumisen vahvistus ja tavalliset käyttöoikeusvirheet käyttävät samaa
  banneria, navigaatiota, värejä, fontteja ja tyylejä.
- Vanha signup.html ohjaa signup.php-sivulle. Vanha signup-success.html on
  edelleen käytettävissä ja käyttää samaa tyyliä.
- Navigaatio kertoo kirjautuneen käyttäjän ja tarjoaa uloskirjautumisen.
- Lomakkeissa on nimetyt kentät, näkyvä näppäimistökohdistus ja Peruuta-linkit.
- Google Fonts -fonttien lisäksi on paikalliset varafontit.

MOBIILI
- Kiinteät 500–1000 pikselin korttileveydet ja sisältöä siirtävät transform-
  määritykset on korvattu joustavilla leveyksillä.
- Enintään 600 pikselin näytöissä navigaatio jakautuu riveille, merkki- ja
  mallikentät pinoutuvat ja toimintopainikkeet täyttävät käytettävissä olevan tilan.
- Kuvat säilyttävät kuvasuhteensa. Tekstikentät ja pitkät julkaisut mahtuvat korttiin.
- Lomakekenttien teksti on vähintään 16 pikseliä ja painikkeet vähintään 44 pikseliä.

UUDELLEENOHJAUKSET
- helpers.php:n redirect_to() tekee PHP-otsakeohjauksen ja lopettaa suorituksen.
- POST-lähetyksen jälkeen käytetään 303-ohjausta. Sivun päivitys ei lähetä samaa
  onnistunutta lomaketta uudelleen.
- Kirjautumista vaativan sivun avaaminen ohjaa login.php-sivulle. Kirjautumisen
  jälkeen palataan alkuperäiselle sivulle; lähettämistä ei suoriteta automaattisesti.
- next-parametri hyväksyy vain ennalta määritetyt tämän projektin sivut.
- Tilin luonti ohjaa kirjautumiseen ja näyttää onnistumisviestin.
- Luonti, muokkaus ja poisto ohjaavat etusivulle ja näyttävät onnistumisviestin.
- Virheellinen kirjautuminen tai lomaketieto palauttaa lomakkeeseen virheviestin.
  Nimi, sähköposti ja julkaisun tekstikentät säilyvät. Salasanaa ja kuvatiedostoa
  ei tallenneta lomakkeen palautusta varten; ne täytyy antaa uudelleen.
- Uloskirjautuminen tapahtuu POST-lomakkeella. Vanha suora logout.php-osoite
  avaa vahvistussivun. Etusivun navigaation painike kirjautuu suoraan ulos.
- Vanha signup.html on staattinen yhteensopivuussivu ja käyttää meta-ohjausta.

KÄYTTÖOIKEUDET JA MUUTTUJAT
- Kaikki voivat lukea julkaisuja. Luonti vaatii kirjautumisen.
- Vain omistaja saa muokata tai poistaa. Omistajuus tarkistetaan palvelimella sekä
  lomaketta avattaessa että lähetettäessä, myös SQL:n UPDATE/DELETE-ehdoissa.
- $mysqli ja $user määritellään sivuilla suoraan. Aputoiminnot saavat tarvittavat
  arvot parametreina, eivät global-muuttujista.
- Vanhat julkaisut ilman user_id-arvoa jäävät näkyviin mutta vain luettaviksi.
  Älä yhdistä niitä tileihin pelkän kirjoittajan nimen perusteella.
- Kaikissa tilaa muuttavissa lomakkeissa on CSRF-tarkistus.

TARKISTUKSET TÄSSÄ TYÖYMPÄRISTÖSSÄ
- JavaScriptin syntaksi ja salasanojen tarkistuksen eri tilanteet tarkistettu.
- Paikalliset linkit, sisällytetyt tiedostot, yhteisen sivupohjan käyttö,
  keskitetyt ohjaukset sekä lomakkeiden kenttätunnisteet tarkistettu lähdekoodista.
- ZIP-paketin eheys tarkistettu.
- PHP- ja MySQL-ajoympäristöä ei ollut saatavilla: PHP-linttiä, HTTP-ohjauksia
  ja tietokannan toimintaa ei voitu ajaa.
- Selaimen käyttöoikeustarkistus esti paikallisen esikatselun. Mobiili- ja
  työpöytäulkoasua ei siis ole varmennettu selaimen kuvakaappauksilla.

KOKEILE WAMPISSA
1. Avaa sivut tietokoneella ja selaimen kehittäjätyökalujen mobiilinäkymässä
   leveyksillä 320, 390 ja 768 pikseliä. Tarkista myös oikealla puhelimella,
   jos WAMP-palvelin on jo käytettävissäsi puhelimen kautta.
2. Tarkista kaikki sivut, pitkät tekstit ja kuvat. Sivulla ei pitäisi tarvita
   vaakasuuntaista vieritystä ja kaikkien painikkeiden tulee näkyä.
3. Kirjautumatta: avaa create_post.php, luo tili ja kirjaudu sisään.
   Kirjautumisen pitäisi palauttaa luontilomakkeeseen.
4. Kokeile väärää salasanaa, eri vahvistussalasanaa ja käytössä olevaa sähköpostia.
   Virheiden pitää näkyä tyylitellyn sivun sisällä.
5. Luo julkaisu, päivitä tulossivu ja varmista, ettei uutta julkaisua synny.
   Muokkaa ja poista oma julkaisu. Tarkista paluu etusivulle ja vahvistusviestit.
6. Kokeile virheellistä kuvaa ja puuttuvia pakollisia tietoja. Tekstin pitäisi
   säilyä lomakkeessa; kuvan pitää voida valita uudelleen.
7. Tilillä B: yritä avata tilin A muokkaus- ja poisto-osoitteet. Pyyntö tulee estää.
   Kokeile myös lomakkeen id-kentän vaihtamista toisen käyttäjän julkaisun id:ksi.
8. Kirjaudu ulos navigaation painikkeella ja varmista, että muokkaus vaatii
   uuden kirjautumisen. Kokeile myös vanhaa signup.html-osoitetta.

TEKNISET VAATIMUKSET
PHP 7.4 tai uudempi, MySQL/MariaDB sekä mysqli/mysqlnd- ja fileinfo-laajennukset.
Käytä tavalliseen tapaan WAMPin PHP 8 -versiota. Tietokannan nykyinen rakenne ja
asetukset säilyvät käytössä.
