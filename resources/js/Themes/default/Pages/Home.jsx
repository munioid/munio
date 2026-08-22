import React from 'react'
import { usePage } from '@inertiajs/react'
import AppLayout from '../Layouts/AppLayout'

export default function Home() {
    const { props } = usePage()
    const { organization, theme, primaryColor, auth } = props

    return (
        <AppLayout>
            <div className="py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-3xl mx-auto">
                    <div className="bg-gray-50 rounded-lg p-8">
                        <h1 className="text-3xl font-bold text-gray-900 mb-4">
                            Welcome to {organization.name}
                        </h1>

                        <div className="space-y-4 text-gray-700">
                            <p>
                                <span className="font-semibold">Current Theme:</span> {theme}
                            </p>
                            <p>
                                <span className="font-semibold">Primary Color:</span>{' '}
                                <span
                                    className="inline-block w-6 h-6 rounded border"
                                    style={{ backgroundColor: primaryColor }}
                                    title={primaryColor}
                                />
                                {' '}
                                {primaryColor}
                            </p>
                            {auth.user ? (
                                <p>
                                    <span className="font-semibold">Logged in as:</span> {auth.user.email}
                                </p>
                            ) : (
                                <p className="text-gray-500">Not authenticated</p>
                            )}
                        </div>

                        <p className="mt-8 text-sm text-gray-500">
                            This is a test page from the default theme. Layout & Shell are working!
                        </p>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}
