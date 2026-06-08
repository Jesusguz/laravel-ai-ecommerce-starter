import { usePage } from '@inertiajs/react';
import LanguageSwitcher from '@/Components/LanguageSwitcher';

export default function GuestLayout({ children }) {
    const { locale } = usePage().props;
    const isEs = locale === 'es';

    return (
        <div className="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 p-4">
            {/* Language switcher top right */}
            <div className="absolute top-6 right-6">
                <LanguageSwitcher />
            </div>

            {/* Premium card */}
            <div className="w-full max-w-md bg-white/5 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 p-8 sm:p-10 relative overflow-hidden">
                {/* Decorative blobs */}
                <div className="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <div className="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>

                {/* Logo */}
                <div className="flex justify-center mb-6">
                    <span className="text-3xl font-extrabold text-white tracking-tight">
                        <span className="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">VendIQ</span>
                    </span>
                </div>

                {children}
            </div>

            <p className="mt-6 text-white/40 text-xs">
                {isEs ? '© 2026 VendIQ. Todos los derechos reservados.' : '© 2026 VendIQ. All rights reserved.'}
            </p>
        </div>
    );
}