import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ locale }) {
    const isEs = locale === 'es';

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">{isEs ? 'Panel de control' : 'Dashboard'}</h2>}
        >
            <Head title={isEs ? 'Panel de control' : 'Dashboard'} />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p className="text-lg text-gray-700">
                            {isEs ? '¡Bienvenido al panel de administración de VendIQ!' : 'Welcome to the VendIQ administration panel!'}
                        </p>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}