import { Link, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';

export default function GuestLayout({ children }) {
    const { locale } = usePage().props;
    const [lang, setLang] = useState(locale || 'en');

    useEffect(() => {
        // Persist language choice via cookie (or you can update session via Inertia)
        document.cookie = `locale=${lang};path=/;max-age=31536000`;
        // Optionally reload or let the backend handle it on next request.
        // For immediate effect, you can pass ?lang= to the URL.
        if (lang !== locale) {
            window.location.search = `?lang=${lang}`;
        }
    }, [lang]);

    const toggleLanguage = () => {
        setLang(prev => (prev === 'en' ? 'es' : 'en'));
    };

    return (
        <div className="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 p-4">
            {/* Language Toggle */}
            <div className="absolute top-6 right-6 flex items-center gap-3">
                <button
                    onClick={toggleLanguage}
                    className="text-sm text-white/70 hover:text-white transition-colors flex items-center gap-1.5 bg-white/5 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10"
                >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    {lang === 'en' ? 'English' : 'Español'}
                </button>
            </div>

            {/* Premium Card */}
            <div className="w-full max-w-md bg-white/5 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 p-8 sm:p-10 relative overflow-hidden">
                {/* Decorative gradient blobs */}
                <div className="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <div className="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>

                {/* Logo / App name */}
                <Link href="/" className="flex justify-center mb-6">
                    <span className="text-3xl font-extrabold text-white tracking-tight">
                        <span className="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">VendIQ</span>
                    </span>
                </Link>

                {children}
            </div>

            <p className="mt-6 text-white/40 text-xs">
                © 2026 VendIQ. All rights reserved.
            </p>
        </div>
    );
}