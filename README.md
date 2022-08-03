# Divtag Cookie Consent | WordPress Plugin

## Externe plugins

### Frontend
- [orestbida/cookieconsent - v2.8.5](https://github.com/orestbida/cookieconsent/releases/tag/v2.8.5)
- [YahnisElsts/plugin-update-checker - v4.12](https://github.com/YahnisElsts/plugin-update-checker/releases/tag/v4.12)

### Admin
- [mdbassit/Coloris - v0.15.0](https://github.com/mdbassit/Coloris/releases/tag/v0.15.0)

## Development
De plugin in de map **wp-content/plugins** clonen, voer `yarn install` uit in de map van de plugin, voer vervolgens `yarn watch` uit om alle bestanden in de `/src` map naar wens aan te passen.

### Optie pagina velden
Bij het toevoegen van velden voor de instellingen, is https://jeremyhixon.com/tool/wordpress-option-page-generator/ een handige generator.

### Nieuwe versie releasen
Als een nieuwe release klaar is, voer `yarn build` uit om alle gegenereerde javascript en css te minificeren. Update het versienummer in **package.json**, **readme.txt** en **divtag-cookie-consent.php**. Na het committen en pushen van de update, tag de laatste commit met hetzelfde versienummer zoals in eerder benoemde bestanden is toegepast.

[Plugin update checker](https://github.com/YahnisElsts/plugin-update-checker) wordt gebruikt om te kijken of er updates zijn van de plugin in andere WordPress installaties.

## Gebruik
Download de plugin en plaats alle bestanden in een aangemaakte map genaamd `divtag-cookie-consent` in de map `wp-content/plugins`. Activeer de plugin op de plugin pagina in WordPress. Vanaf nu zou er links in het menu een nieuwe knop moeten verschijnen genaamd Cookie Consent. Hier zijn alle instellingen te vinden om de standaard instellingen te overschrijven.

### Blokkeren van externe scripts
Huidige categorieën die te blokkeren zijn:
- `marketing`

Om externe scripts te blokkeren moet bij het script dat geblokkeerd moet worden als deze instelling is gekozen het volgende gebeuren:

Om bijvoorbeeld `marketing` cookies te blokkeren, moet het `<script>` tag `type="text/plain"` krijgen en voeg `data-cookiecategory="marketing"` toe:

`<script type="text/plain" data-cookiecategory="marketing" src="./assets/js/my_custom_script.js" defer></script>`

### Cookie instellingen modal tonen
Voeg een `<button>` toe waar gewenst zoals onderstaand voorbeeld om de cookie instellingen te tonen:

`<button type="button" data-cc="c-settings">Cookie voorkeuren</button>`


## Changelog

Zie [readme.txt](https://github.com/divtag-nl/wp-plugin-divtag-cookie-consent/blob/master/readme.txt) voor de changelog.