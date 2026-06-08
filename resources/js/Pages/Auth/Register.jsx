import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Register() {
    const { locale } = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register', {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    const t = (key) => {
        const strings = {
            'auth.register.title': locale === 'es' ? 'Crea tu cuenta' : 'Create your account',
            'auth.register.subtitle': locale === 'es' ? 'Comienza tu prueba gratuita de 14 días' : 'Start your free 14-day trial',
            'auth.register.name': locale === 'es' ? 'Nombre' : 'Name',
            'auth.register.email': locale === 'es' ? 'Correo electrónico' : 'Email',
            'auth.register.password': locale === 'es' ? 'Contraseña' : 'Password',
            'auth.register.password_confirmation': locale === 'es' ? 'Confirmar contraseña' : 'Confirm password',
            'auth.register.button': locale === 'es' ? 'Crear cuenta' : 'Create account',
            'auth.register.already': locale === 'es' ? '¿Ya tienes cuenta? Inicia sesión' : 'Already have an account? Sign in',
        };
        return strings[key] || key;
    };

    return (
        <GuestLayout>
            <Head title={t('auth.register.title')} />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-white">{t('auth.register.title')}</h1>
                <p className="mt-2 text-sm text-white/60">{t('auth.register.subtitle')}</p>
            </div>

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <InputLabel htmlFor="name" value={t('auth.register.name')} className="text-white/80" />
                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl"
                        autoComplete="name"
                        isFocused={true}
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div>
                    <InputLabel htmlFor="email" value={t('auth.register.email')} className="text-white/80" />
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div>
                    <InputLabel htmlFor="password" value={t('auth.register.password')} className="text-white/80" />
                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl"
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div>
                    <InputLabel htmlFor="password_confirmation" value={t('auth.register.password_confirmation')} className="text-white/80" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl"
                        autoComplete="new-password"
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        required
                    />
                    <InputError message={errors.password_confirmation} className="mt-2" />
                </div>

                <div className="pt-2">
                    <PrimaryButton
                        className="w-full justify-center py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 border-0 rounded-xl text-white font-semibold"
                        disabled={processing}
                    >
                        {t('auth.register.button')}
                    </PrimaryButton>
                </div>

                <div className="text-center text-sm text-white/60">
                    <Link href="/login" className="text-indigo-400 hover:text-indigo-300 underline underline-offset-2">
                        {t('auth.register.already')}
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}