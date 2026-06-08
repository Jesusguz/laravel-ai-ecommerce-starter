import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function WidgetCustomizer() {
    const { data, setData } = useForm({
        primary_color: '#4f46e5',
        welcome_message: '¡Hola! Soy VendIQ, tu asistente de ventas.',
        locale: 'es',
    });

    const [snippet, setSnippet] = useState('');

    const generateSnippet = () => {
        const script = `<script src="https://tudominio.com/widget/vendiq.js" data-api="https://tudominio.com/api/v1/chat" data-color="${data.primary_color}" data-locale="${data.locale}"></script>`;
        setSnippet(script);
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Personalizar Widget</h2>}
        >
            <Head title="Widget Customizer" />
            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium">Color primario</label>
                                <input
                                    type="color"
                                    value={data.primary_color}
                                    onChange={e => setData('primary_color', e.target.value)}
                                    className="h-10 w-20"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium">Mensaje de bienvenida</label>
                                <input
                                    type="text"
                                    value={data.welcome_message}
                                    onChange={e => setData('welcome_message', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium">Idioma</label>
                                <select
                                    value={data.locale}
                                    onChange={e => setData('locale', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                >
                                    <option value="es">Español</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                        </div>
                        <button
                            onClick={generateSnippet}
                            className="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Generar Snippet
                        </button>
                        {snippet && (
                            <div className="mt-4 p-4 bg-gray-100 rounded-md">
                                <pre className="text-sm">{snippet}</pre>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}