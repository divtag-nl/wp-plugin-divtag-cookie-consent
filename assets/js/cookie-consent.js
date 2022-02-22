const cc = initCookieConsent();

const getOptions = cookieConsentSettings.options;
const options = {
  force_consent:  getOptions.forceer_consent
                      ? true 
                      : false,
  dark_mode:      getOptions.donkere_modus,
  title_nl:       getOptions.titel_nl
                      ? getOptions.titel_nl
                      : 'We gebruiken cookies!',
  description_nl: getOptions.uitleg_nl
                      ? getOptions.uitleg_nl
                      : 'We gebruiken analytische cookies en sommige cookies worden geplaatst door diensten van derden die op onze pagina\'s worden weergegeven. Door op \'Laat mij kiezen\' te klikken, kun je meer lezen over onze cookies en je voorkeuren aanpassen.',
  title_en:       getOptions.titel_en
                      ? getOptions.titel_nl
                      : 'We use cookies!',
  description_en: getOptions.uitleg_en
                      ? getOptions.uitleg_nl
                      : 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.',
  gui: {
      layout:               getOptions.layout || 'cloud',
      position_vertical:    getOptions.positie_verticaal || 'bottom',
      position_horizontal:  getOptions.positie_horizontaal || 'right',
      transition:           getOptions.transitie,
      swap_buttons:         getOptions.draai_knoppen_om
                                ? true
                                : false, 
  },
}

// If a contact url is set, override the admin email
let contactUrl = 'mailto:' + cookieConsentSettings.adminEmail;
if (getOptions.contact_url) {
    contactUrl = getOptions.contact_url;
}

// If a button (theme) color is set, override the existing default color
if (getOptions.knoppen_kleur) {
    document.documentElement.style.setProperty('--cc-btn-primary-bg', getOptions.knoppen_kleur);
}

// If dark mode setting is checked, set dark theme
options.dark_mode ? document.body.classList.toggle('c_darkmode') : '';

// Set language by looking at the html attribute 'lang'
const current_language = document.getElementsByTagName('html')[0].getAttribute('lang') === 'nl' ? 'nl' : 'en'


// Run cookie consent plugin with configuration
cc.run({
    current_lang: current_language,
    autoclear_cookies: true,
    page_scripts: true,
    force_consent: options.force_consent,

    gui_options: {
        consent_modal: {
            layout: options.gui.layout,
            position: `${options.gui.position_vertical} ${options.gui.position_horizontal}`,
            transition: options.gui.transition,
            swap_buttons: options.gui.swap_buttons,
        }
    },

    languages: {
        'nl': {
            consent_modal: {
                title: options.title_nl,
                description: options.description_nl + ' <button type="button" data-cc="c-settings" class="cc-link">Laat mij kiezen</button>',
                primary_btn: {
                    text: 'Accepteer alles',
                    role: 'accept_all', // 'accept_selected' or 'accept_all'
                },
                secondary_btn: {
                    text: 'Weiger alles',
                    role: 'accept_necessary', // 'settings' or 'accept_necessary'
                },
            },
            settings_modal: {
                title: 'Cookie voorkeuren',
                save_settings_btn: 'Sla voorkeuren op',
                accept_all_btn: 'Accepteer alles',
                reject_all_btn: 'Weiger alles',
                close_btn_label: 'Sluiten',
                blocks: [
                    {
                        title: 'Cookie gebruik 📢',
                        description: 'We gebruiken cookies om de basis functionaliteiten van de website goed te laten werken. Je kan voor elke categorie kiezen om deze in of uit te schakelen.',
                    }, {
                        title: 'Strikt noodzakelijke cookies',
                        description: 'Deze cookies zijn nodig om de website goed te laten werken. De website zou niet meer goed kunnen werken als je deze niet accepteert.',
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true, // cookie categories with readonly=true are all treated as "necessary cookies"
                        },
                    }, {
                        title: 'Marketing cookies',
                        description: 'Deze cookies verzamelen informatie over hoe je de website gebruikt, welke pagina\'s je bezocht hebt en op welke links je geklikt hebt. Deze data is niet geanonimiseerd en kunnen aan jou gekoppeld worden.',
                        toggle: {
                            value: 'marketing',
                            enabled: false,
                            readonly: false,
                        },
                    }, {
                        title: 'Meer informatie',
                        description: 'Voor vragen omtrent onze policy over cookies en jouw keuzes, neem <a class="cc-link" href="' + contactUrl + '" target="_blank" rel="noreferrer noopener">contact op</a>.',
                    },
                ],
            },
        },
        'en': {
            consent_modal: {
              title: options.title_en,
                description: options.description_en + ' <button type="button" data-cc="c-settings" class="cc-link">Let me choose</button>',
                primary_btn: {
                    text: 'Accept all',
                    role: 'accept_all'              // 'accept_selected' or 'accept_all'
                },
                secondary_btn: {
                    text: 'Reject all',
                    role: 'accept_necessary'        // 'settings' or 'accept_necessary'
                }
            },
            settings_modal: {
                title: 'Cookie preferences',
                save_settings_btn: 'Save settings',
                accept_all_btn: 'Accept all',
                reject_all_btn: 'Reject all',
                close_btn_label: 'Close',
                blocks: [
                    {
                        title: 'Cookie usage 📢',
                        description: 'I use cookies to ensure the basic functionalities of the website and to enhance your online experience. You can choose for each category to opt-in/out whenever you want.'
                    }, {
                        title: 'Strictly necessary cookies',
                        description: 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly.',
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true          // cookie categories with readonly=true are all treated as "necessary cookies"
                        }
                    }, {
                        title: 'Marketing cookies',
                        description: 'These cookies collect information about how you use the website, which pages you visited and which links you clicked on. All of the data is not anonymized and can be used to identify you.',
                        toggle: {
                            value: 'marketing',
                            enabled: false,
                            readonly: false
                        }
                    }, {
                        title: 'More information',
                        description: 'For any queries in relation to our policy on cookies and your choices, please <a class="cc-link" href="' + contactUrl + '">contact us</a>.',
                    }
                ]
            }
        }
    }
});