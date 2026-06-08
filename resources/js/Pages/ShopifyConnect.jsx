import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function ShopifyConnect({ locale }) {
    const isEs = locale === 'es';
    const { data, setData, post, processing, errors } = useForm({
        store_url: '',
        access_token: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/admin/shopify/sync');
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">{isEs ? 'Conectar Shopify' : 'Connect Shopify'}</h2>}
        >
            <Head title={isEs ? 'Conectar Shopify' : 'Connect Shopify'} />
            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <form onSubmit={submit}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700">{isEs ? 'URL de la tienda' : 'Store URL'}</label>
                                <input type="text" placeholder="ejemplo.myshopify.com" value={data.store_url} onChange={e => setData('store_url', e.target.value)} className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                {errors.store_url && <div className="text-red-500 text-sm mt-1">{errors.store_url}</div>}
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700">{isEs ? 'Token de acceso' : 'Access Token'}</label>
                                <input type="password" value={data.access_token} onChange={e => setData('access_token', e.target.value)} className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                {errors.access_token && <div className="text-red-500 text-sm mt-1">{errors.access_token}</div>}
                            </div>
                            <button type="submit" disabled={processing} className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {isEs ? 'Sincronizar productos' : 'Sync Products'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}