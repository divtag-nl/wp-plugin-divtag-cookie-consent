import * as CookieConsent from 'vanilla-cookieconsent';
import colorShade from './color-shade.js';

const CAT_NECESSARY = 'necessary';
const CAT_ANALYTICS = 'analytics';
const CAT_ADVERTISEMENT = 'advertisement';
const CAT_FUNCTIONALITY = 'functionality';
const CAT_SECURITY = 'security';

const SERVICE_AD_STORAGE = 'ad_storage';
const SERVICE_AD_USER_DATA = 'ad_user_data';
const SERVICE_AD_PERSONALIZATION = 'ad_personalization';
const SERVICE_ANALYTICS_STORAGE = 'analytics_storage';
const SERVICE_FUNCTIONALITY_STORAGE = 'functionality_storage';
const SERVICE_PERSONALIZATION_STORAGE = 'personalization_storage';
const SERVICE_SECURITY_STORAGE = 'security_storage';

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
};

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

window.dataLayer = window.dataLayer || [];

function gtag() {
  dataLayer.push(arguments);
}

// Set default consent to 'denied' (this should happen before changing any other dataLayer)
gtag('consent', 'default', {
  [SERVICE_AD_STORAGE]: 'denied',
  [SERVICE_AD_USER_DATA]: 'denied',
  [SERVICE_AD_PERSONALIZATION]: 'denied',
  [SERVICE_ANALYTICS_STORAGE]: 'denied',
  [SERVICE_FUNCTIONALITY_STORAGE]: 'denied',
  [SERVICE_PERSONALIZATION_STORAGE]: 'denied',
  [SERVICE_SECURITY_STORAGE]: 'denied',
});

function updateGtagConsent() {
  gtag('consent', 'update', {
    [SERVICE_ANALYTICS_STORAGE]: CookieConsent.acceptedService(SERVICE_ANALYTICS_STORAGE, CAT_ANALYTICS) ? 'granted' : 'denied',
    [SERVICE_AD_STORAGE]: CookieConsent.acceptedService(SERVICE_AD_STORAGE, CAT_ADVERTISEMENT) ? 'granted' : 'denied',
    [SERVICE_AD_USER_DATA]: CookieConsent.acceptedService(SERVICE_AD_USER_DATA, CAT_ADVERTISEMENT) ? 'granted' : 'denied',
    [SERVICE_AD_PERSONALIZATION]: CookieConsent.acceptedService(SERVICE_AD_PERSONALIZATION, CAT_ADVERTISEMENT) ? 'granted' : 'denied',
    [SERVICE_FUNCTIONALITY_STORAGE]: CookieConsent.acceptedService(SERVICE_FUNCTIONALITY_STORAGE, CAT_FUNCTIONALITY) ? 'granted' : 'denied',
    [SERVICE_PERSONALIZATION_STORAGE]: CookieConsent.acceptedService(SERVICE_PERSONALIZATION_STORAGE, CAT_FUNCTIONALITY) ? 'granted' : 'denied',
    [SERVICE_SECURITY_STORAGE]: CookieConsent.acceptedService(SERVICE_SECURITY_STORAGE, CAT_SECURITY) ? 'granted' : 'denied',
  });
}

CookieConsent.run({
  cookie: {
    name: 'cc_cookie_v2',
    expiresAfterDays: 365,
  },

  disablePageInteraction: options.forceConsent,

  // https://cookieconsent.orestbida.com/reference/configuration-reference.html#guioptions
  guiOptions: {
    consentModal: {
      layout: options.gui.consentModal.layout,
      position: `${options.gui.consentModal.positionY} ${options.gui.consentModal.positionX}`,
      equalWeightButtons: true,
      flipButtons: options.gui.consentModal.flipButtons,
    },
    preferencesModal: {
      layout: options.gui.preferencesModal.layout,
      equalWeightButtons: true,
      flipButtons: options.gui.consentModal.flipButtons,
    },
  },

  // Trigger consent update when user choices change
  onFirstConsent: () => {
    updateGtagConsent();
  },
  onConsent: () => {
    updateGtagConsent();
  },
  onChange: () => {
    updateGtagConsent();
  },

  categories: {
    [CAT_NECESSARY]: {
      enabled: true,  // this category is enabled by default
      readOnly: true,  // this category cannot be disabled
    },
    [CAT_ANALYTICS]: {
      autoClear: {
        cookies: [
          {
            name: /^_ga/,   // regex: match all cookies starting with '_ga'
          },
          {
            name: '_gid',   // string: exact cookie name
          },
        ],
      },
      // See: https://cookieconsent.orestbida.com/reference/configuration-reference.html#category-services
      services: {
        [SERVICE_ANALYTICS_STORAGE]: {
          label: 'Enables storage (such as cookies) related to analytics e.g. visit duration.',
        },
      },
    },
    [CAT_ADVERTISEMENT]: {
      services: {
        [SERVICE_AD_STORAGE]: {
          label: 'Enables storage (such as cookies) related to advertising.',
        },
        [SERVICE_AD_USER_DATA]: {
          label: 'Sets consent for sending user data related to advertising to Google.',
        },
        [SERVICE_AD_PERSONALIZATION]: {
          label: 'Sets consent for personalized advertising.',
        },
      },
    },
    [CAT_FUNCTIONALITY]: {
      services: {
        [SERVICE_FUNCTIONALITY_STORAGE]: {
          label: 'Enables storage that supports the functionality of the website or app e.g. language settings.',
        },
        [SERVICE_PERSONALIZATION_STORAGE]: {
          label: 'Enables storage related to personalization e.g. video recommendations.',
        },
      },
    },
    [CAT_SECURITY]: {
      services: {
        [SERVICE_SECURITY_STORAGE]: {
          label: 'Enables storage related to security such as authentication functionality, fraud prevention, and other user protection.',
        },
      },
    },
  },

  language: {
    autoDetect: 'document',
    translations: {
      nl: {
        consentModal: {
          title: options.titleNl,
          description: options.descriptionNl,
          acceptAllBtn: 'Accepteer alles',
          acceptNecessaryBtn: 'Weiger alles',
          showPreferencesBtn: 'Stel individuele voorkeuren in',
        },
        preferencesModal: {
          title: 'Stel cookie voorkeuren in',
          acceptAllBtn: 'Accepteer alles',
          acceptNecessaryBtn: 'Weiger alles',
          savePreferencesBtn: 'Sla voorkeuren op',
          closeIconLabel: 'Sluit modal',
          serviceCounterLabel: 'Service|Services',
          sections: [
            {
              title: 'Jouw privacy keuzes',
              description: 'We gebruiken cookies om de basis functionaliteiten van de website goed te laten werken. Je kan voor elke niet-strict noodzakelijke categorie kiezen om deze in of uit te schakelen.',
            },
            {
              title: 'Strikt noodzakelijke cookies',
              description: 'Deze cookies zijn nodig om de website goed te laten werken. Deze keuze kan niet worden uitgezet.',
              linkedCategory: CAT_NECESSARY,
            },
            {
              title: 'Prestaties en analytics',
              description: 'Deze cookies verzamelen informatie over hoe je de website gebruikt, welke pagina\'s je bezocht hebt en op welke links je geklikt hebt. Deze data is niet geanonimiseerd en kunnen aan jou gekoppeld worden.',
              linkedCategory: CAT_ANALYTICS,
              cookieTable: {
                headers: {
                  name: 'Naam',
                  domain: 'Dienst',
                  description: 'Beschrijving',
                  expiration: 'Verlooptijd',
                },
                body: [
                  {
                    name: '_ga',
                    domain: 'Google Analytics',
                    description: 'Cookie geplaatst door <a href="https://business.safety.google/adscookies/">Google Analytics</a>',
                    expiration: 'Verloopt na 12 dagen',
                  },
                  {
                    name: '_gid',
                    domain: 'Google Analytics',
                    description: 'Cookie geplaatst door <a href="https://business.safety.google/adscookies/">Google Analytics</a>',
                    expiration: 'Sessie',
                  },
                ],
              },
            },
            {
              title: 'Targeting en advertenties',
              description: 'Deze cookies worden gebruikt om advertentieberichten relevanter te maken voor jou en jouw interesses. Het doel is om advertenties te tonen die relevant en aantrekkelijk zijn voor de individuele gebruiker en daardoor waardevoller zijn voor uitgevers en adverteerders.',
              linkedCategory: CAT_ADVERTISEMENT,
            },
            {
              title: 'Functionaliteit',
              description: 'Cookies die worden gebruikt voor functionaliteit stellen gebruikers in staat om met een dienst of site te interageren om toegang te krijgen tot functies die fundamenteel zijn voor die dienst. Zaken die als fundamenteel voor de dienst worden beschouwd, omvatten voorkeuren zoals de taalkeuze van de gebruiker, productoptimalisaties die helpen een dienst te behouden en te verbeteren, en het bewaren van informatie met betrekking tot de sessie van een gebruiker, zoals de inhoud van een winkelwagen.',
              linkedCategory: CAT_FUNCTIONALITY,
            },
            {
              title: 'Beveiliging',
              description: 'Cookies die voor beveiliging worden gebruikt authenticeren gebruikers, voorkomen fraude en beschermen gebruikers terwijl zij met een dienst interageren.',
              linkedCategory: CAT_SECURITY,
            },
            {
              title: 'Meer informatie',
              description: 'Voor vragen omtrent onze policy over cookies en jouw keuzes, neem <a href="' + contactUrl + '">contact op</a>.',
            },
          ],
        },
      },
      en: {
        consentModal: {
          title: options.titleEn,
          description: options.titleEn,
          acceptAllBtn: 'Accept all',
          acceptNecessaryBtn: 'Reject all',
          showPreferencesBtn: 'Manage Individual preferences',
        },
        preferencesModal: {
          title: 'Manage cookie preferences',
          acceptAllBtn: 'Accept all',
          acceptNecessaryBtn: 'Reject all',
          savePreferencesBtn: 'Accept current selection',
          closeIconLabel: 'Close modal',
          serviceCounterLabel: 'Service|Services',
          sections: [
            {
              title: 'Your Privacy Choices',
              description: `In this panel you can express some preferences related to the processing of your personal information. You may review and change expressed choices at any time by resurfacing this panel via the provided link. To deny your consent to the specific processing activities described below, switch the toggles to off or use the “Reject all” button and confirm you want to save your choices.`,
            },
            {
              title: 'Strictly Necessary',
              description: 'These cookies are essential for the proper functioning of the website and cannot be disabled.',
              linkedCategory: CAT_NECESSARY,
            },
            {
              title: 'Performance and Analytics',
              description: 'These cookies collect information about how you use our website. All of the data is not anonymized and can be used to identify you.',
              linkedCategory: CAT_ANALYTICS,
              cookieTable: {
                headers: {
                  name: "Name",
                  domain: "Service",
                  description: "Description",
                  expiration: "Expiration"
                },
                body: [
                  {
                    name: "_ga",
                    domain: "Google Analytics",
                    description: "Cookie set by <a href=\"https://business.safety.google/adscookies/\">Google Analytics</a>",
                    expiration: "Expires after 12 days"
                  },
                  {
                    name: "_gid",
                    domain: "Google Analytics",
                    description: "Cookie set by <a href=\"https://business.safety.google/adscookies/\">Google Analytics</a>",
                    expiration: "Session"
                  }
                ]
              }
            },
            {
              title: 'Targeting and Advertising',
              description: 'These cookies are used to make advertising messages more relevant to you and your interests. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.',
              linkedCategory: CAT_ADVERTISEMENT,
            },
            {
              title: 'Functionality',
              description: 'Cookies used for functionality allow users to interact with a service or site to access features that are fundamental to that service. Things considered fundamental to the service include preferences like the user’s choice of language, product optimizations that help maintain and improve a service, and maintaining information relating to a user’s session, such as the content of a shopping cart.',
              linkedCategory: CAT_FUNCTIONALITY,
            },
            {
              title: 'Security',
              description: 'Cookies used for security authenticate users, prevent fraud, and protect users as they interact with a service.',
              linkedCategory: CAT_SECURITY,
            },
            {
              title: 'More information',
              description: 'For any queries in relation to my policy on cookies and your choices, please <a href="' + contactUrl + '">contact us</a>.',
            },
          ],
        },
      },
    },
  },
});
