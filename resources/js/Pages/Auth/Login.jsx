import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { locale } = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login', {
            onFinish: () => reset('password'),
        });
    };

    // Helper to translate using Laravel's __() helper, accessible via global
    const __ = (key) => {
        const translations = usePage().props.translations;
        return translations[key] || key;
    };

    // Fallback for demo if translations not loaded
    const t = (key) => {
        const strings = {
            'auth.login.title': locale === 'es' ? 'Bienvenido de nuevo' : 'Welcome back',
            'auth.login.subtitle': locale === 'es' ? 'Inicia sesión en tu cuenta' : 'Sign in to your account',
            'auth.login.email': locale === 'es' ? 'Correo electrónico' : 'Email',
            'auth.login.password': locale === 'es' ? 'Contraseña' : 'Password',
            'auth.login.remember': locale === 'es' ? 'Recuérdame' : 'Remember me',
            'auth.login.forgot': locale === 'es' ? '¿Olvidaste tu contraseña?' : 'Forgot your password?',
            'auth.login.button': locale === 'es' ? 'Iniciar sesión' : 'Sign in',
        };
        return strings[key] || key;
    };

    return (
        <GuestLayout>
            <Head title={t('auth.login.title')} />

            <div className="text-center mb-8">
                <h1 className="text-2xl font-bold text-white">{t('auth.login.title')}</h1>
                <p className="mt-2 text-sm text-white/60">{t('auth.login.subtitle')}</p>
            </div>

            {status && (
                <div className="mb-4 text-sm font-medium text-green-400 text-center">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <InputLabel htmlFor="email" value={t('auth.login.email')} className="text-white/80" />
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl focus:border-indigo-500 focus:ring-indigo-500"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div>
                    <InputLabel htmlFor="password" value={t('auth.login.password')} className="text-white/80" />
                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="flex items-center justify-between">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                        />
                        <span className="ms-2 text-sm text-white/60">{t('auth.login.remember')}</span>
                    </label>

                    {canResetPassword && (
                        <Link
                            href="/forgot-password"
                            className="text-sm text-indigo-400 hover:text-indigo-300 underline underline-offset-2"
                        >
                            {t('auth.login.forgot')}
                        </Link>
                    )}
                </div>

                <PrimaryButton
                    className="w-full justify-center py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 border-0 rounded-xl text-white font-semibold"
                    disabled={processing}
                >
                    {t('auth.login.button')}
                </PrimaryButton>
            </form>
        </GuestLayout>
    );
}