import React from 'react'
import { useForm, usePage } from '@inertiajs/react'
import { useRoute } from '../../Hooks/useRoute'
import AuthLayout from '../../Layouts/AuthLayout'
import { ArrowLeftIcon } from '@heroicons/react/24/outline'
import { Button, Input, Alert } from '../../Components/Partial'

export default function ChangePassword() {
    const { props } = usePage()
    const { user, primaryColor } = props
    const route = useRoute()
    const { data, setData, put, processing, errors, recentlySuccessful, reset } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    })

    const handleSubmit = (e) => {
        e.preventDefault()
        put(route('password.update'), {
            onSuccess: () => {
                reset()
            }
        })
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

                    <h1 className="text-xl font-semibold text-white">Ubah Password</h1>

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
                            Password berhasil diperbarui!
                        </Alert>
                    )}

                    <div className="overflow-hidden rounded-2xl bg-white shadow-sm px-5 py-5 space-y-5">
                        <Input
                            type="password"
                            label="Password Lama"
                            value={data.current_password}
                            onChange={(e) => setData('current_password', e.target.value)}
                            error={errors.current_password}
                            placeholder="••••••••"
                            autoComplete="current-password"
                            required
                        />

                        <Input
                            type="password"
                            label="Password Baru"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            error={errors.password}
                            placeholder="••••••••"
                            autoComplete="new-password"
                            required
                        />

                        <Input
                            type="password"
                            label="Konfirmasi Password"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            error={errors.password_confirmation}
                            placeholder="••••••••"
                            autoComplete="new-password"
                            required
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
