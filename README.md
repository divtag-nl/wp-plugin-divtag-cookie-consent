# Divtag Cookie Consent

## Externe plugins
- [orestbida/cookieconsent - v2.8.0](https://github.com/orestbida/cookieconsent/releases/tag/v2.8.0)
- [YahnisElsts/plugin-update-checker - v4.11](https://github.com/YahnisElsts/plugin-update-checker/releases/tag/v4.11)

### Admin
- [mdbassit/Coloris - v0.10.0](https://github.com/mdbassit/Coloris/releases/tag/v0.10.0)

## Development
De plugin in de map **wp-content/plugins** clonen, voer `yarn install` uit in de map van de plugin, voer vervolgens `yarn dev` of `yarn watch` uit om alle bestanden naar wens aan te passen. 

Als een nieuwe release klaar is voer `yarn prod` uit om alle gegenereerde javascript en css te minificeren en update het versienummer in **package.json**, **readme.txt** en **divtag-cookie-consent.php**. Na het committen en pushen van de update, tag de laatste commit met hetzelfde versienummer als in eerder benoemde bestanden is toegepast.

[Plugin update checker](https://github.com/YahnisElsts/plugin-update-checker) wordt gebruikt om te kijken of er updates zijn van de plugin in andere WordPress instanties.

### Optie pagina velden
Bij het toevoegen van velden voor de instellingen, is https://jeremyhixon.com/tool/wordpress-option-page-generator/ een handige generator.

## Gebruik
Download de plugin en plaats alle bestanden in een aangemaakte map genaamd **divtag-cookie-consent** in de map **wp-content/plugins**. Activeer de plugin op de plugin pagina in WordPress. Vanaf nu zou er links in het menu een nieuwe knop moeten verschijnen genaamd Cookie Consent. Hier zijn alle instellingen te vinden om de standaard instellingen te overschrijven.

### Blokkeren van externe scripts
Huidige categorieën die te blokkeren zijn:
- `marketing`

Om externe scripts te blokkeren moet bij het script dat geblokkeerd moet worden als deze instelling is gekozen het volgende gebeuren:

Om bijvoorbeeld `marketing` cookies te blokkeren, moet het `<script>` tag het type `text/plain` krijgen en voeg `data-cookiecategory="marketing"` toe:

`<script type="text/plain" data-cookiecategory="marketing" src="./assets/js/my_custom_script.js" defer></script>`

### Cookie instellingen modal tonen
Voeg een `<button>` toe waar gewenst zoals onderstaand voorbeeld om de cookie instellingen te tonen:

`<button type="button" data-cc="c-settings">Cookie voorkeuren</button>`