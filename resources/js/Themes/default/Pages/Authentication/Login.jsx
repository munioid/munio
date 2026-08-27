import React from 'react'
import { useForm, usePage } from '@inertiajs/react'
import { route } from 'ziggy-js'
import AuthLayout from '../../Layouts/AuthLayout'

export default function Login() {
    const { props } = usePage()
    const { organization } = props

    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    })

    const handleSubmit = (e) => {
        e.preventDefault()
        post('/login')
    }

    // Banner image URL - fallback to a default if not provided by organization
    const bannerUrl = organization?.login_banner || 'https://picsum.photos/500/300?1'

    // Google OAuth URL - placeholder, should point to actual Google OAuth endpoint
    // const googleOAuthUrl = route('oauth.google') || '#'

    return (
        <AuthLayout>
            <div className="w-full">
                {/* Banner */}
                <div className="w-full aspect-video overflow-hidden shadow-lg">
                    <img
                        src={bannerUrl}
                        alt="Login Banner"
                        className="w-full h-full object-cover"
                    />
                </div>

                {/* Card Container - overlapping banner */}
                <div className="rounded-t-3xl bg-white -mt-6 relative z-10 px-5 pt-6 pb-8">
                    {/* Header */}
                    <div className="mb-6 text-center">
                        <h2 className="text-2xl font-bold text-gray-900">
                            Selamat Datang
                        </h2>
                        <p className="mt-2 text-gray-500">
                            Masuk untuk melanjutkan ke akun Anda.
                        </p>
                    </div>

                    {/* General error message */}
                    {errors.email && (
                        <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                            {errors.email}
                        </div>
                    )}

                    {/* Form */}
                    <form onSubmit={handleSubmit} className="space-y-5">
                        {/* Email Field */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className={`w-full px-4 py-3 border rounded-xl outline-none transition ${
                                    errors.email
                                        ? 'border-red-500 focus:border-red-500'
                                        : 'border-gray-300 focus:border-[var(--primary-color)]'
                                }`}
                                placeholder="your@email.com"
                                required
                            />
                            {errors.email && (
                                <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                            )}
                        </div>

                        {/* Password Field */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className={`w-full px-4 py-3 border rounded-xl outline-none transition ${
                                    errors.password
                                        ? 'border-red-500 focus:border-red-500'
                                        : 'border-gray-300 focus:border-[var(--primary-color)]'
                                }`}
                                placeholder="••••••••"
                                required
                            />
                            {errors.password && (
                                <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                            )}
                        </div>

                        {/* Forgot Password Link */}
                        {/* <div className="text-right">
                            <a
                                href={route('password.request')}
                                className="text-sm"
                                style={{ color: 'var(--primary-color)' }}
                            >
                                Lupa Password?
                            </a>
                        </div> */}

                        {/* Submit Button */}
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full py-3 px-4 text-white font-semibold rounded-xl disabled:opacity-50 transition"
                            style={{
                                backgroundColor: processing ? 'var(--primary-color)' : 'var(--primary-color)',
                                opacity: processing ? 0.7 : 1,
                            }}
                        >
                            {processing ? 'Masuk...' : 'Masuk'}
                        </button>
                    </form>

                    {/* Divider */}
                    <div className="my-8 flex items-center">
                        <div className="flex-1 h-px bg-gray-200"></div>
                        <span className="px-3 text-gray-400 text-sm">atau</span>
                        <div className="flex-1 h-px bg-gray-200"></div>
                    </div>

                    {/* Google Login Button */}
                    <a
                        href="#"
                        className="flex items-center justify-center gap-3 w-full py-3 px-4 border border-gray-300 bg-white font-medium rounded-xl hover:bg-gray-50 transition"
                    >
                        <svg
                            className="w-5 h-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4"
                            />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853"
                            />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05"
                            />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335"
                            />
                        </svg>
                        <span>Masuk dengan Google</span>
                    </a>
                </div>
            </div>
        </AuthLayout>
    )
}
