import React from 'react'
import { useForm, usePage } from '@inertiajs/react'
import { useRoute } from '../../Hooks/useRoute'
import AuthLayout from '../../Layouts/AuthLayout'
import { ArrowLeftIcon } from '@heroicons/react/24/outline'
import { Button, Input, Alert } from '../../Components/Partial'

export default function EditProfile() {
    const { props } = usePage()
    const { user, primaryColor, flash } = props
    const route = useRoute()
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
                        <Alert variant="success" dismissible className="mb-4">
                            Profil berhasil diperbarui!
                        </Alert>
                    )}

                    {flash?.success && (
                        <Alert variant="success" dismissible className="mb-4">
                            {flash.success}
                        </Alert>
                    )}

                    <div className="overflow-hidden rounded-2xl bg-white shadow-sm px-5 py-5 space-y-5">
                        <Input
                            type="text"
                            label="Nama"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            error={errors.name}
                            placeholder="Nama lengkap Anda"
                            required
                        />

                        <Input
                            type="email"
                            label="Email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            error={errors.email}
                            placeholder="your@email.com"
                        />
                    </div>

                    <Button
                        type="submit"
                        disabled={processing}
                        loading={processing}
                        className="mt-6 w-full py-4 text-lg"
                    >
                        Simpan
                    </Button>
                </form>
            </div>
        </AuthLayout>
    )
}
