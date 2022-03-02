# Divtag Cookie Consent

## Externe plugins
- [orestbida/cookieconsent - v2.8.0](https://github.com/orestbida/cookieconsent/releases/tag/v2.8.0)
- [YahnisElsts/plugin-update-checker - v4.11](https://github.com/YahnisElsts/plugin-update-checker/releases/tag/v4.11)

### Admin
- [mdbassit/Coloris - v0.10.0](https://github.com/mdbassit/Coloris/releases/tag/v0.10.0)

## Development
De plugin in **wp-content/plugins** clonen, vervolgens zijn alle bestanden naar wens aan te passen. Als een nieuwe release klaar is, update het versienummer in **readme.txt** en **divtag-cookie-consent.php**. Na het committen en pushen van de update, tag de laatste commit met hetzelfde versienummer als in eerder benoemde bestanden is toegepast.

[Plugin update checker](https://github.com/YahnisElsts/plugin-update-checker) wordt gebruikt om te kijken of er updates zijn van de plugin.

### Optie pagina velden
Bij het toevoegen van velden voor de instellingen, is https://jeremyhixon.com/tool/wordpress-option-page-generator/ een handige generator.

## Gebruik
Wanneer de plugin is geïnstalleerd en geactiveerd is, zou er links in het menu een nieuwe knop moeten verschijnen genaamd Cookie Consent. Hier zijn alle instellingen te vinden om de standaard instellingen te overschrijven.

### Blokkeren van externe scripts
Huidige categorieën die te blokkeren zijn:
- `marketing`

Om externe scripts te blokkeren moet bij het script dat geblokkeerd moet worden als deze instelling is gekozen het volgende gebeuren:

Om bijvoorbeeld `marketing` cookies te blokkeren, moet het `<script>` tag het type `text/plain` krijgen en voeg `data-cookiecategory="marketing"` toe:

`<script type="text/plain" data-cookiecategory="marketing" src="./assets/js/my_custom_script.js" defer></script>`

### Cookie instellingen modal tonen
Voeg een `<button>` toe waar gewenst zoals onderstaand voorbeeld om de cookie instellingen te tonen:

`<button type="button" data-cc="c-settings">Cookie voorkeuren</button>`