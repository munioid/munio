import React from 'react'
import { Link, usePage } from '@inertiajs/react'
import { useRoute } from '../Hooks/useRoute'
import { UserIcon } from '@heroicons/react/24/outline'

export default function Header() {
    const { props } = usePage()
    const { organization, auth, icon } = props
    const route = useRoute()

    return (
        <header className="bg-white shadow-sm">
            <div className="flex justify-between items-center px-5 pt-5 pb-5">
                {/* Logo and Organization Name */}
                <div className="flex items-center gap-3">
                    {icon ? (
                        <img
                            src={icon}
                            alt={organization.name}
                            className="h-12 rounded-xl object-cover"
                        />
                    ) : (
                        <div className="w-12 h-12 rounded-xl bg-[var(--primary-color)] flex items-center justify-center text-white font-bold text-xl">
                            {organization?.name?.charAt(0) || 'M'}
                        </div>
                    )}

                    {!icon && (
                        <div>
                            <h1 className="font-bold text-lg">
                                {organization?.name}
                            </h1>
                        </div>
                    )}
                </div>

                {/* User Profile / Login */}
                <div className="flex gap-3">
                    {auth?.user ? (
                        <Link
                            href={route('profile.view')}
                            className="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-[var(--primary-color)] text-lg font-semibold text-white hover:opacity-90 transition"
                            title={auth.user.name}
                        >
                            {auth.user.avatar ? (
                                <img
                                    src={auth.user.avatar}
                                    alt={auth.user.name}
                                    className="h-full w-full object-cover"
                                />
                            ) : (
                                auth.user.name?.charAt(0)?.toUpperCase()
                            )}
                        </Link>
                    ) : (
                        <Link
                            href={route('login')}
                            className="flex h-12 w-12 items-center justify-center rounded-full border border-gray-200 bg-white hover:bg-gray-50 transition"
                            title="Login"
                        >
                            <UserIcon className="h-6 w-6 text-gray-600" />
                        </Link>
                    )}
                </div>
            </div>
        </header>
    )
}
