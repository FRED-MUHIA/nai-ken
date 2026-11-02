import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.richTextEditor = function () {
    return {
        input: null,
        editor: null,
        savedRange: null,

        init(input, editor) {
            this.input = input;
            this.editor = editor;

            if (this.input.value.trim().match(/<[a-z][\s\S]*>/i)) {
                this.editor.innerHTML = this.input.value;
            } else {
                this.editor.textContent = this.input.value;
            }

            this.sync();
            document.addEventListener('selectionchange', () => this.saveSelection());
            this.editor.addEventListener('keyup', () => this.saveSelection());
            this.editor.addEventListener('mouseup', () => this.saveSelection());
            this.editor.closest('form')?.addEventListener('submit', () => this.sync());
        },

        format(command) {
            this.restoreSelection();
            this.editor.focus();
            document.execCommand(command, false, null);
            this.sync();
            this.saveSelection();
        },

        block(tagName) {
            this.restoreSelection();
            this.editor.focus();
            document.execCommand('formatBlock', false, `<${tagName}>`);
            this.sync();
            this.saveSelection();
        },

        link() {
            this.restoreSelection();
            this.editor.focus();
            const url = window.prompt('Paste link URL');

            if (! url) {
                return;
            }

            this.restoreSelection();
            document.execCommand('createLink', false, url);
            this.sync();
            this.saveSelection();
        },

        saveSelection() {
            if (! this.editor) {
                return;
            }

            const selection = window.getSelection();

            if (! selection || selection.rangeCount === 0) {
                return;
            }

            const range = selection.getRangeAt(0);

            if (this.editor.contains(range.commonAncestorContainer)) {
                this.savedRange = range.cloneRange();
            }
        },

        restoreSelection() {
            if (! this.savedRange) {
                return;
            }

            const selection = window.getSelection();

            if (! selection) {
                return;
            }

            selection.removeAllRanges();
            selection.addRange(this.savedRange);
        },

        sync() {
            if (! this.input || ! this.editor) {
                return;
            }

            this.input.value = this.editor.innerHTML.trim();
        },
    };
};

window.eastAfricaLanguageSelector = function (locales = []) {
    return {
        locales,
        open: false,
        responseText: 'Choose a country to translate the website.',
        storageKey: 'np_country_code_v2',
        selected: { code: 'EN', country: 'English', language: 'English', locale: 'en' },
        ready: false,

        init() {
            window.localStorage.removeItem('np_country_code');
            const savedCode = window.localStorage.getItem(this.storageKey);
            const saved = this.locales.find((locale) => locale.code === savedCode);

            if (saved) {
                this.selected = saved;
            }

            this.responseText = this.messageFor(this.selected);
            window.addEventListener('np-google-translate-ready', () => {
                this.ready = true;
                this.applyWebsiteTranslation(false);
            });

            if (window.npGoogleTranslateReady) {
                this.ready = true;
                this.applyWebsiteTranslation(false);
            }
        },

        choose(locale) {
            this.selected = locale;
            this.open = false;
            window.localStorage.setItem(this.storageKey, locale.code);
            this.responseText = this.messageFor(locale);
            this.applyWebsiteTranslation(true);
        },

        messageFor(locale) {
            if (! locale) {
                return 'Choose a country to translate the website.';
            }

            if (locale.locale === 'en') {
                return 'Website language set to English.';
            }

            return `Website will translate to ${locale.language}.`;
        },

        applyWebsiteTranslation(shouldReload = false) {
            const language = this.selected.locale || 'en';

            document.documentElement.lang = language;

            if (language === 'en') {
                this.clearTranslateCookie();
                if (shouldReload) {
                    window.location.reload();
                }

                return;
            } else {
                this.setTranslateCookie(language);
            }

            const combo = document.querySelector('.goog-te-combo');
            if (combo) {
                combo.value = language === 'en' ? '' : language;
                combo.dispatchEvent(new Event('change'));
                return;
            }

            if (shouldReload) {
                window.location.reload();
            }
        },

        setTranslateCookie(language) {
            if (window.npSetGoogleTranslate) {
                window.npSetGoogleTranslate(language);
                return;
            }

            document.cookie = `googtrans=/en/${language}; path=/; SameSite=Lax`;
        },

        clearTranslateCookie() {
            if (window.npClearGoogleTranslate) {
                window.npClearGoogleTranslate();
                return;
            }

            document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
        },
    };
};

window.productSearchSuggestions = function (endpoint) {
    return {
        endpoint,
        query: '',
        suggestions: [],
        loading: false,
        open: false,
        abortController: null,

        init() {
            this.query = new URLSearchParams(window.location.search).get('q') || '';
        },

        get allResultsUrl() {
            return `/products?q=${encodeURIComponent(this.query.trim())}`;
        },

        show() {
            if (this.query.trim().length >= 2) {
                this.open = true;
                this.search(this.query);
            }
        },

        async search(value) {
            const term = value.trim();

            if (term.length < 2) {
                this.abortController?.abort();
                this.abortController = null;
                this.open = false;
                this.suggestions = [];
                this.loading = false;
                return;
            }

            this.abortController?.abort();
            const controller = new AbortController();
            this.abortController = controller;
            this.loading = true;
            this.open = true;

            try {
                const response = await fetch(`${this.endpoint}?q=${encodeURIComponent(term)}`, {
                    headers: { Accept: 'application/json' },
                    signal: controller.signal,
                });

                if (! response.ok) {
                    throw new Error('Product suggestions request failed.');
                }

                const suggestions = await response.json();
                if (this.abortController === controller) {
                    this.suggestions = suggestions;
                }
            } catch (error) {
                if (error.name !== 'AbortError' && this.abortController === controller) {
                    this.suggestions = [];
                }
            } finally {
                if (this.abortController === controller) {
                    this.loading = false;
                }
            }
        },
    };
};

Alpine.start();
