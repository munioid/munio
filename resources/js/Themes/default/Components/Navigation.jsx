import React from 'react'
import { Link, usePage } from '@inertiajs/react'
import {
    HomeIcon,
    NewspaperIcon,
    CalendarDaysIcon,
} from '@heroicons/react/24/outline'

export default function Navigation() {
    const { url } = usePage()
    const currentPath = url

    // Helper to check if current path matches nav item
    const isActive = (path) => {
        if (path === '/') {
            return currentPath === '/'
        }
        return currentPath.startsWith(path)
    }

    const navItems = [
        {
            path: '/',
            icon: HomeIcon,
            label: 'Home',
            testId: 'nav-home',
        },
        {
            path: '/posts',
            icon: NewspaperIcon,
            label: 'Berita',
            testId: 'nav-posts',
        },
        {
            path: '/events',
            icon: CalendarDaysIcon,
            label: 'Acara',
            testId: 'nav-events',
        },
    ]

    return (
        <nav className="fixed inset-x-0 bottom-0 z-50">
            <div className="mx-auto max-w-md">
                <div className="flex rounded-full bg-white/95 backdrop-blur-lg shadow-2xl border border-gray-100 py-2">
                    {navItems.map(({ path, icon: Icon, label, testId }) => (
                        <Link
                            key={path}
                            href={path}
                            className={`flex-1 flex flex-col items-center transition-colors ${
                                isActive(path)
                                    ? 'text-[var(--primary-color)]'
                                    : 'text-gray-400 hover:text-gray-600'
                            }`}
                            data-testid={testId}
                        >
                            <Icon className="w-6 h-6" />
                            <span className="mt-1 text-[11px] font-medium">{label}</span>
                        </Link>
                    ))}
                </div>
            </div>
        </nav>
    )
}
