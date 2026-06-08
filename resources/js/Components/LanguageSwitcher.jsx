import { usePage } from '@inertiajs/react';

export default function LanguageSwitcher({ className = '' }) {
    const { locale } = usePage().props;

    const switchLang = (newLocale) => {
        document.cookie = `locale=${newLocale};path=/;max-age=31536000`;
        window.location.reload();
    };

    return (
        <div className={`flex items-center gap-2 ${className}`}>
            <button
                onClick={() => switchLang('es')}
                className={`px-2 py-1 text-xs rounded-md transition-colors ${
                    locale === 'es' ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:text-white'
                }`}
            >
                ES
            </button>
            <button
                onClick={() => switchLang('en')}
                className={`px-2 py-1 text-xs rounded-md transition-colors ${
                    locale === 'en' ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:text-white'
                }`}
            >
                EN
            </button>
        </div>
    );
}