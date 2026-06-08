import { Head, Link } from '@inertiajs/react';
import LanguageSwitcher from '@/Components/LanguageSwitcher';

export default function Welcome({ canLogin, canRegister, locale }) {
    const isEs = locale === 'es';

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 flex flex-col justify-center items-center p-4">
            <div className="absolute top-6 right-6">
                <LanguageSwitcher />
            </div>

            <Head title={isEs ? 'VendIQ' : 'VendIQ'} />
            <div className="text-center max-w-xl">
                <h1 className="text-5xl font-extrabold text-white mb-4">
                    <span className="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">VendIQ</span>
                </h1>
                <p className="text-lg text-white/60 mb-8">
                    {isEs ? 'Tu asistente de ventas inteligente con IA multi‑proveedor.' : 'Your intelligent sales assistant with multi‑provider AI.'}
                </p>
                {canLogin && (
                    <div className="space-x-4">
                        <Link href="/login" className="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl transition-colors">
                            {isEs ? 'Iniciar sesión' : 'Sign in'}
                        </Link>
                        {canRegister && (
                            <Link href="/register" className="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl transition-colors border border-white/20">
                                {isEs ? 'Registrarse' : 'Register'}
                            </Link>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}