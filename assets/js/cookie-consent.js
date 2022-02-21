// obtain plugin
const cc = initCookieConsent();

const getOptions = cookieConsentSettings.options;
const options = {
  force_consent:  getOptions.forceer_consent_0 
                    ? true 
                    : false,
  dark_mode:      getOptions.donkere_modus_1,
  title_nl:       getOptions.titel_nl_2
                    ? getOptions.titel_nl_2 
                    : 'We gebruiken cookies!',
  description_nl: getOptions.uitleg_nl_3
                    ? getOptions.uitleg_nl_3
                    : 'We gebruiken analytische cookies en sommige cookies worden geplaatst door diensten van derden die op onze pagina\'s worden weergegeven. Door op \'Laat mij kiezen welke cookies\' te klikken, kun je meer lezen over onze cookies en je voorkeuren aanpassen.',
  title_en:       getOptions.titel_en_4
                    ? getOptions.titel_nl_4 
                    : 'We use cookies!',
  description_en: getOptions.uitleg_en_5
                    ? getOptions.uitleg_nl_5
                    : 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.'
}

let contactUrl = 'mailto:' + cookieConsentSettings.adminEmail;

if (getOptions.contact_url_6) {
  contactUrl = getOptions.contact_url_6;
}

// TODO delete line below
console.log(getOptions)


// If settings dark mode is true, set dark theme
options.dark_mode ? document.body.classList.toggle('c_darkmode') : '';

// run plugin with your configuration
cc.run({
    current_lang: document.getElementsByTagName('html')[0].getAttribute('lang') === 'nl' ? 'nl' : 'en',
    autoclear_cookies: true,                   // default: false
    page_scripts: true,                        // default: false

    // mode: 'opt-in'                          // default: 'opt-in'; value: 'opt-in' or 'opt-out'
    // delay: 0,                               // default: 0
    // auto_language: null,                    // default: null; could also be 'browser' or 'document'
    // autorun: true,                          // default: true
    force_consent: options.force_consent,      // default: false
    // hide_from_bots: false,                  // default: false
    // remove_cookie_tables: false             // default: false
    // cookie_name: 'cc_cookie',               // default: 'cc_cookie'
    // cookie_expiration: 182,                 // default: 182 (days)
    // cookie_necessary_only_expiration: 182   // default: disabled
    // cookie_domain: location.hostname,       // default: current domain
    // cookie_path: '/',                       // default: root
    // cookie_same_site: 'Lax',                // default: 'Lax'
    // use_rfc_cookie: false,                  // default: false
    // revision: 0,                            // default: 0

    onFirstAction: function(user_preferences, cookie){
        // callback triggered only once
    },

    onAccept: function (cookie) {
        // ...
    },

    onChange: function (cookie, changed_preferences) {
        // ...
    },

    gui_options: {
      consent_modal: {
        layout: 'cloud',               // box/cloud/bar
        position: 'bottom right',      // bottom/middle/top + left/right/center
        transition: 'slide',           // zoom/slide
        swap_buttons: false            // enable to invert buttons
      }
    },

    languages: {
        'nl': {
            consent_modal: {
                title: options.title_nl,
                description: options.description_nl + ' <button type="button" data-cc="c-settings" class="cc-link">Laat mij kiezen welke cookies</button>',
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
                cookie_table_headers: [
                    { col1: 'Naam' },
                    { col2: 'Domein' },
                    { col3: 'Vervaltijd' },
                    { col4: 'Omschrijving' },
                ],
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
                cookie_table_headers: [
                    {col1: 'Name'},
                    {col2: 'Domain'},
                    {col3: 'Expiration'},
                    {col4: 'Description'}
                ],
                blocks: [
                    {
                        title: 'Cookie usage 📢',
                        description: 'I use cookies to ensure the basic functionalities of the website and to enhance your online experience. You can choose for each category to opt-in/out whenever you want.'
                    }, {
                        title: 'Strictly necessary cookies',
                        description: 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true          // cookie categories with readonly=true are all treated as "necessary cookies"
                        }
                    }, {
                        title: 'Performance and Analytics cookies',
                        description: 'These cookies allow the website to remember the choices you have made in the past',
                        toggle: {
                            value: 'analytics',     // your cookie category
                            enabled: false,
                            readonly: false
                        },
                        cookie_table: [             // list of all expected cookies
                            {
                                col1: '^_ga',       // match all cookies starting with "_ga"
                                col2: 'google.com',
                                col3: '2 years',
                                col4: 'description ...',
                                is_regex: true
                            },
                            {
                                col1: '_gid',
                                col2: 'google.com',
                                col3: '1 day',
                                col4: 'description ...',
                            }
                        ]
                    }, {
                        title: 'Advertisement and Targeting cookies',
                        description: 'These cookies collect information about how you use the website, which pages you visited and which links you clicked on. All of the data is anonymized and cannot be used to identify you',
                        toggle: {
                            value: 'targeting',
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