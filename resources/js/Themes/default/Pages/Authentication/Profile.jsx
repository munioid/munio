import React from 'react'
import { Link, usePage } from '@inertiajs/react'
import { useRoute } from '../../Hooks/useRoute'
import AuthLayout from '../../Layouts/AuthLayout'
import { ChevronRightIcon, ArrowLeftIcon, UserIcon, LockClosedIcon, CalendarDaysIcon } from '@heroicons/react/24/outline'

export default function Profile() {
    const { props } = usePage()
    const { user, primaryColor } = props
    const route = useRoute()

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
                {/* Profile Header */}
                <div className="relative pb-16 pt-8 text-center" style={{ backgroundColor: primaryColor }}>
                    <Link
                        href="/"
                        className="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur hover:bg-white/30 transition"
                    >
                        <ArrowLeftIcon className="h-5 w-5" />
                    </Link>

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

                {/* Menu */}
                <div className="mt-5 space-y-3 px-5">
                    <Link
                        href={route('profile.edit')}
                        className="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm hover:shadow-md transition"
                    >
                        <div className="flex items-center gap-3">
                            <UserIcon className="h-6 w-6" style={{ color: primaryColor }} />
                            <span className="font-medium">Edit Profil</span>
                        </div>
                        <ChevronRightIcon className="h-5 w-5 text-gray-400" />
                    </Link>

                    <Link
                        href={route('password.change')}
                        className="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm hover:shadow-md transition"
                    >
                        <div className="flex items-center gap-3">
                            <LockClosedIcon className="h-6 w-6" style={{ color: primaryColor }} />
                            <span className="font-medium">Ubah Password</span>
                        </div>
                        <ChevronRightIcon className="h-5 w-5 text-gray-400" />
                    </Link>

                    <Link
                        href={route('profile.reservations')}
                        className="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm hover:shadow-md transition"
                    >
                        <div className="flex items-center gap-3">
                            <CalendarDaysIcon className="h-6 w-6" style={{ color: primaryColor }} />
                            <span className="font-medium">Reservasi Acara</span>
                        </div>
                        <ChevronRightIcon className="h-5 w-5 text-gray-400" />
                    </Link>
                </div>

                {/* Logout */}
                <div className="mt-8 px-5 pb-8">
                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="w-full rounded-2xl border-2 border-red-500 py-4 font-semibold text-red-500 hover:bg-red-50 transition"
                    >
                        Keluar
                    </Link>
                </div>
            </div>
        </AuthLayout>
    )
}
