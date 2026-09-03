ONLYCARS – JULKAISUJEN KÄYTTÖOIKEUDET

ASENNUS
1. Ota varmuuskopio nykyisistä tiedostoista ja tietokannasta.
2. Avaa phpMyAdmin ja valitse nykyinen onlycars-tietokanta.
3. Avaa SQL-välilehti ja suorita sql/add_post_ownership.sql-tiedoston sisältö.
   Tämä lisää posts.user_id-sarakkeen vain, jos sitä ei vielä ole.
   Nykyisiä julkaisuja ei poisteta eikä olemassa olevia omistajia muuteta.
4. Kopioi tämän paketin PHP-tiedostot nykyiseen projektikansioon.
   Mukana on myös kaksi uutta tiedostoa: auth.php ja post_images.php.
   Säilytä nykyinen kuvat-kansiosi ja omat database.php-yhteysasetuksesi.
5. Käynnistä WAMPin Apache ja MySQL ja avaa index.php localhostin kautta.

TOIMINTA
- Kaikki voivat lukea julkaisuja.
- Luo tili signup.html-sivulla ja kirjaudu sisään login.php-sivulla.
- Vain kirjautunut, olemassa oleva käyttäjä voi luoda julkaisuja.
- Kirjoittajan nimi ja user_id tulevat kirjautuneesta käyttäjätilistä.
- Vain julkaisun omistaja näkee Muokkaa- ja Poista-linkit.
- Käyttöoikeus tarkistetaan myös PHP-palvelimella. Toisen julkaisua ei voi
  muuttaa avaamalla osoitetta suoraan tai vaihtamalla lomakkeen id-arvoa.
- Lomakkeissa on istuntokohtainen CSRF-tarkistus.
- Muokkaaminen ja poistaminen ohjaavat takaisin index.php-sivulle.

VANHAT JULKAISUT
Aiempi upload.php ei tallentanut käyttäjän tunnusta. Julkaisut, joiden
user_id on NULL tai muuten vailla kelvollista omistajaa, näkyvät edelleen,
mutta käyttäjät eivät voi muokata tai poistaa niitä.
Nimi ei todista omistajuutta, joten julkaisuja ei yhdistetä tileihin nimen mukaan.
Jos tiedät varmasti omistajan, voit asettaa oikean user-taulun id-arvon
kyseisen julkaisun posts.user_id-kenttään phpMyAdminissa.

KOKEILE KAHDELLA TILILLÄ
1. Kirjautumatta: julkaisut näkyvät, mutta luonti-, muokkaus- ja poistosivut
   ohjaavat kirjautumiseen. Suora POST upload.php-sivulle ei luo julkaisua.
2. Tilillä A: luo julkaisu. Nimi tulee tililtä ja Muokkaa/Poista näkyvät.
3. Tilillä B: A:n julkaisulla ei näy Muokkaa/Poista-linkkejä.
   Avaa edit_product.php?id=A:N_JULKAISUN_ID ja delete_post.php?id=A:N_JULKAISUN_ID:
   saat virheen eikä julkaisu muutu.
4. Tilillä B: avaa oman julkaisun lomake ja vaihda selaimen kehittäjätyökaluissa
   piilotettu id A:n julkaisun tunnukseksi. Lähetys ei saa muuttaa A:n julkaisua.
5. Tilillä A: muokkaa omaa julkaisua ja vaihda kuva. Poista oma julkaisu.
6. Lähetä lomake ilman csrf_token-kenttää: pyyntö hylätään.

TEKNISET VAATIMUKSET
Nykyiset user- ja posts-taulut sekä niiden nykyiset sarakkeet säilyvät käytössä.
Paketissa ei ollut tietokantavientiä; SQL-tiedosto päivittää olemassa olevan
posts-taulun, eikä luo uutta tietokantaa. PHP tarvitsee mysqli/mysqlnd- ja
fileinfo-laajennukset (tavallisesti mukana WAMPissa).

TARKISTUKSET
Käyttöoikeuksien tarkistusjärjestys, SQL-parametrit ja lomakkeiden kentät on
katselmoitu. Tässä työympäristössä ei ollut PHP/MySQL-ajoympäristöä, joten
PHP-linttiä tai palvelimella ajettavia toimintatestejä ei voitu suorittaa.
Suorita yllä oleva kahden tilin kokeilu omassa WAMP-ympäristössäsi.
