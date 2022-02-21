# Divtag Cookie Consent

## Externe plugins
https://github.com/orestbida/cookieconsent

## Gebruik
Wanneer de plugin is geïnstalleerd en geactiveerd is, zou er links in het menu een nieuwe knop moeten verschijnen genaamd Cookie Consent. Hier zijn alle instellingen te vinden om de standaard instellingen te vervangen.

### Blokkeren van externe scripts
Huidige categorieën die te blokkeren zijn:
- `marketing`

Om externe scripts te blokkeren moet bij het script dat geblokkeerd moet worden als deze instelling is gekozen het volgende gebeuren:

Om bijvoorbeeld `marketing` cookies te blokkeren, moet het `<script>` tag het type `text/plain` krijgen en voeg `data-cookiecategory="marketing"` toe:

`<script type="text/plain" data-cookiecategory="marketing" src="./assets/js/my_custom_script.js" defer></script>`

### Cookie instellingen modal tonen
Voeg een `<button>` toe waar gewenst zoals onderstaand voorbeeld om de cookie instellingen te tonen:

`<button type="button" data-cc="c-settings">Cookie voorkeuren</button>`