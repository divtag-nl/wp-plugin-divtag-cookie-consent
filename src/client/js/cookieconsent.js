import * as CookieConsent from "vanilla-cookieconsent";
import colorShade from './color-shade.js';

const get_options = cookie_consent_settings.options;

const options = {
  forceConsent: get_options.force_consent ?
    true :
    false,
  titleNl: get_options.title_nl ?
    get_options.title_nl :
    'We gebruiken cookies!',
  descriptionNl: get_options.description_nl ?
    get_options.description_nl :
    'We gebruiken analytische cookies en sommige cookies worden geplaatst door diensten van derden die op onze pagina\'s worden weergegeven. Door op \'Stel individuele voorkeuren in\' te klikken, kun je meer lezen over onze cookies en je voorkeuren aanpassen.',
  titleEn: get_options.title_en ?
    get_options.title_en :
    'We use cookies!',
  descriptionEn: get_options.description_en ?
    get_options.description_en :
    'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.',
  gui: {
		consentModal: {
			layout: get_options.layout || 'cloud inline',
			positionY: get_options.position_vertical || 'bottom',
			positionX: get_options.position_horizontal || 'right',
			flipButtons: get_options.swap_buttons ?
				true :
				false,
		},
		preferencesModal: {
			layout: get_options.layout_settings || 'box',
			positionX: get_options.position_horizontal_settings || 'right',
		},
  },
}

// If a contact url is set, override the admin email
let contactUrl = 'mailto:' + cookie_consent_settings.admin_email;

if (get_options.contact_url) {
  contactUrl = get_options.contact_url;
}

// If a button (theme) color is set, override the existing default color
if (get_options.button_color) {
  document.documentElement.style.setProperty('--cc-btn-primary-bg', get_options.button_color);
  document.documentElement.style.setProperty('--cc-btn-primary-border-color', get_options.button_color);
  document.documentElement.style.setProperty('--cc-btn-primary-hover-bg', colorShade(get_options.button_color, -20));
  document.documentElement.style.setProperty('--cc-btn-primary-hover-border-color', colorShade(get_options.button_color, -20));
}

CookieConsent.run({
  cookie: {
    name: "cc_cookie_v2",
    expiresAfterDays: 365
  },

  disablePageInteraction: options.forceConsent,

  // https://cookieconsent.orestbida.com/reference/configuration-reference.html#guioptions
  guiOptions: {
    consentModal: {
      layout: options.gui.consentModal.layout,
      position: `${options.gui.consentModal.positionY} ${options.gui.consentModal.positionX}`,
      equalWeightButtons: true,
      flipButtons: options.gui.consentModal.flipButtons
    },
    preferencesModal: {
      layout: options.gui.preferencesModal.layout,
      equalWeightButtons: true,
      flipButtons: options.gui.consentModal.flipButtons,
    }
  },

  categories: {
    necessary: {
      enabled: true,
      readOnly: true
    },
    analytics: {
      services: {
        'Google Analytics': {
          label: "Google Analytics",
        }
      }
    },
    ads: {
      services: {
        'Google Ads': {
          label: "Google Ads",
        }
      }
    }
  },

  language: {
    autoDetect: 'document',
    translations: {
      nl: {
        consentModal: {
          title: options.titleNl,
          description: options.descriptionNl,
          acceptAllBtn: "Accepteer alles",
          acceptNecessaryBtn: "Weiger alles",
          showPreferencesBtn: "Stel individuele voorkeuren in",
        },
        preferencesModal: {
          title: "Stel cookie voorkeuren in",
          acceptAllBtn: "Accepteer alles",
          acceptNecessaryBtn: "Weiger alles",
          savePreferencesBtn: "Sla voorkeuren op",
          closeIconLabel: "Sluit modal",
          serviceCounterLabel: "Service|Services",
          sections: [
            {
              title: "Jouw privacy keuzes",
              description: "We gebruiken cookies om de basis functionaliteiten van de website goed te laten werken. Je kan voor elke niet-strict noodzakelijke categorie kiezen om deze in of uit te schakelen."
            }, {
              title: "Strikt noodzakelijke cookies",
              description: "Deze cookies zijn nodig om de website goed te laten werken. Deze keuze kan niet worden uitgezet.",
              linkedCategory: "necessary"
            }, {
              title: "Prestaties en analytics",
              description: "Deze cookies verzamelen informatie over hoe je de website gebruikt, welke pagina\'s je bezocht hebt en op welke links je geklikt hebt. Deze data is niet geanonimiseerd en kunnen aan jou gekoppeld worden.",
              linkedCategory: "analytics"
            }, {
              title: "Targeting en advertenties",
              description: "Deze cookies worden gebruikt om advertentieberichten relevanter te maken voor jou en jouw interesses. Het doel is om advertenties te tonen die relevant en aantrekkelijk zijn voor de individuele gebruiker en daardoor waardevoller zijn voor uitgevers en adverteerders.",
              linkedCategory: "ads"
            }, {
              title: "Meer informatie",
              description: 'Voor vragen omtrent onze policy over cookies en jouw keuzes, neem <a href="' + contactUrl + '">contact op</a>.'
            }
          ]
        }
      },
      en: {
        consentModal: {
          title: options.titleEn,
          description: options.titleEn,
          acceptAllBtn: "Accept all",
          acceptNecessaryBtn: "Reject all",
          showPreferencesBtn: "Manage Individual preferences",
        },
        preferencesModal: {
          title: "Manage cookie preferences",
          acceptAllBtn: "Accept all",
          acceptNecessaryBtn: "Reject all",
          savePreferencesBtn: "Accept current selection",
          closeIconLabel: "Close modal",
          serviceCounterLabel: "Service|Services",
          sections: [
            {
              title: "Your Privacy Choices",
              description: `In this panel you can express some preferences related to the processing of your personal information. You may review and change expressed choices at any time by resurfacing this panel via the provided link. To deny your consent to the specific processing activities described below, switch the toggles to off or use the “Reject all” button and confirm you want to save your choices.`
            }, {
              title: "Strictly Necessary",
              description: "These cookies are essential for the proper functioning of the website and cannot be disabled.",
              linkedCategory: "necessary"
            }, {
              title: "Performance and Analytics",
              description: "These cookies collect information about how you use our website. All of the data is not anonymized and can be used to identify you.",
              linkedCategory: "analytics"
            }, {
              title: "Targeting and Advertising",
              description: "These cookies are used to make advertising messages more relevant to you and your interests. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.",
              linkedCategory: "ads"
            }, {
              title: "More information",
              description: 'For any queries in relation to my policy on cookies and your choices, please <a href="' + contactUrl + '">contact us</a>.'
            }
          ]
        }
      }
    }
  }
});