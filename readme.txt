=== Divtag Cookie Consent ===
Tags: cookies
Requires at least: 4.0
Tested up to: 5.9
Stable tag: 1.2.0
Requires PHP: 5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
Divtag Cookie Consent WordPress Plugin

== Description ==

= Externe plugins =
[orestbida/cookieconsent - v2.8.0](https://github.com/orestbida/cookieconsent/releases/tag/v2.8.0)

= Gebruik =
Wanneer de plugin is geïnstalleerd en geactiveerd is, zou er links in het menu een nieuwe knop moeten verschijnen genaamd Cookie Consent. Hier zijn alle instellingen te vinden om de standaard instellingen te overschijven.

= Blokkeren van externe scripts =
Huidige categorieën die te blokkeren zijn:
- `marketing`

Om externe scripts te blokkeren moet bij het script dat geblokkeerd moet worden als deze instelling is gekozen het volgende gebeuren:

Om bijvoorbeeld `marketing` cookies te blokkeren, moet het `<script>` tag het type `text/plain` krijgen en voeg `data-cookiecategory="marketing"` toe:

`<script type="text/plain" data-cookiecategory="marketing" src="./assets/js/my_custom_script.js" defer></script>`

= Cookie instellingen modal tonen =
Voeg een `<button>` toe waar gewenst zoals onderstaand voorbeeld om de cookie instellingen te tonen:

`<button type="button" data-cc="c-settings">Cookie voorkeuren</button>`

== Changelog ==

= 1.2 =
* Nieuwe instellingen om de cookie voorkeuren modal als box of bar te weergeven.

= 1.1.4 =
* Fix knoppen hover kleur wanneer deze ingesteld is vanuit de instellingen.

= 1.1 =
* Instellingen hebben nieuwe namen in de backend code, dus als er instellingen zijn ingesteld voor deze versie, let dan op dat deze gereset gaan worden.

= 1.0 =
* Eerste release.

== Upgrade Notice ==

= 1.2 =
Er zijn enkele velden in de backend aangepast waardoor bestaande instellingen verloren zijn gegaan.

= 1.1 =
Deze versie verbetert de backend code, geen nieuwe features. Let op dat de huidige instellingen gereset worden.