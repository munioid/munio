import React from 'react'
import { useForm, usePage } from '@inertiajs/react'
import { route } from 'ziggy-js'
import AuthLayout from '../../Layouts/AuthLayout'
import { ArrowLeftIcon } from '@heroicons/react/24/outline'

export default function EditProfile() {
    const { props } = usePage()
    const { user, primaryColor, flash } = props
    const { data, setData, put, processing, errors, recentlySuccessful } = useForm({
        name: user?.name || '',
        email: user?.email || '',
    })

    const handleSubmit = (e) => {
        e.preventDefault()
        put(route('profile.update'))
    }

    // Get user initials for avatar fallback
    const getInitials = (name) => {
        return name
            .split(' ')
            .map(n => n[0])
            .join('')
            .toUpperCase()
            .slice(0, 2)
    }

    return (
        <AuthLayout>
            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <div className="relative pb-16 pt-8 text-center" style={{ backgroundColor: primaryColor }}>
                    <button
                        type="button"
                        onClick={() => window.history.back()}
                        className="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur hover:bg-white/30 transition"
                    >
                        <ArrowLeftIcon className="h-5 w-5" />
                    </button>

                    <h1 className="text-xl font-semibold text-white">Ubah Profil</h1>

                    <div className="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2">
                        {user?.avatar ? (
                            <img
                                src={user.avatar}
                                alt={user.name}
                                className="h-28 w-28 rounded-full border-4 border-white object-cover shadow-xl"
                            />
                        ) : (
                            <div className="flex h-28 w-28 items-center justify-center rounded-full border-4 border-white text-4xl font-bold text-white shadow-xl" style={{ backgroundColor: primaryColor }}>
                                {getInitials(user?.name || 'U')}
                            </div>
                        )}
                    </div>
                </div>

                {/* User Info */}
                <div className="bg-white pt-20 pb-8 text-center shadow-sm">
                    <h1 className="text-2xl font-bold">{user?.name}</h1>
                    <p className="mt-2 text-gray-500">{user?.email}</p>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="px-5 pb-8 pt-5">
                    {recentlySuccessful && (
                        <div className="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">
                            Profil berhasil diperbarui!
                        </div>
                    )}

                    {flash?.success && (
                        <div className="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">
                            {flash.success}
                        </div>
                    )}

                    <div className="overflow-hidden rounded-2xl bg-white shadow-sm">
                        <div className="divide-y divide-gray-100">
                            {/* Name */}
                            <div className="p-5">
                                <label className="mb-2 block text-sm font-medium text-gray-700">
                                    Nama <span className="text-red-600">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:border-transparent outline-none transition ${
                                        errors.name ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    style={!errors.name ? { '--focus-color': primaryColor } : {}}
                                    placeholder="Nama lengkap Anda"
                                    required
                                />
                                {errors.name && (
                                    <p className="mt-2 text-sm text-red-600">{errors.name}</p>
                                )}
                            </div>

                            {/* Email */}
                            <div className="p-5">
                                <label className="mb-2 block text-sm font-medium text-gray-700">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:border-transparent outline-none transition ${
                                        errors.email ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="your@email.com"
                                />
                                {errors.email && (
                                    <p className="mt-2 text-sm text-red-600">{errors.email}</p>
                                )}
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        disabled={processing}
                        className="mt-6 w-full rounded-2xl py-4 font-semibold text-white transition hover:opacity-90 disabled:opacity-50"
                        style={{ backgroundColor: primaryColor }}
                    >
                        {processing ? 'Menyimpan...' : 'Simpan'}
                    </button>
                </form>
            </div>
        </AuthLayout>
    )
}
