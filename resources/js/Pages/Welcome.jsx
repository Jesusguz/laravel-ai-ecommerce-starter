import { Head, Link } from '@inertiajs/react';

export default function Welcome({ canLogin, canRegister }) {
    return (
        <>
            <Head title="VendIQ" />
            <div className="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
                <h1 className="text-4xl font-bold mb-4 text-gray-800">VendIQ</h1>
                <p className="text-lg text-gray-600 mb-8">Tu asistente de ventas inteligente.</p>
                {canLogin && (
                    <div className="space-x-4">
                        <Link href="/login" className="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Iniciar sesión
                        </Link>
                        {canRegister && (
                            <Link href="/register" className="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                                Registrarse
                            </Link>
                        )}
                    </div>
                )}
            </div>
        </>
    );
}