import React from 'react'
import { Link, usePage } from '@inertiajs/react'
import { route } from 'ziggy-js'
import AuthLayout from '../../Layouts/AuthLayout'
import { ArrowLeftIcon, CalendarDaysIcon, TicketIcon } from '@heroicons/react/24/outline'

export default function MyReservations() {
    const { props } = usePage()
    const { reservations, primaryColor } = props

    // Format date
    const formatDate = (dateString) => {
        const date = new Date(dateString)
        return new Intl.DateTimeFormat('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        }).format(date)
    }

    // Get status color and label
    const getStatusBadge = (status) => {
        const statusMap = {
            pending: { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Menunggu' },
            confirmed: { bg: 'bg-green-100', text: 'text-green-800', label: 'Dikonfirmasi' },
            completed: { bg: 'bg-blue-100', text: 'text-blue-800', label: 'Selesai' },
            cancelled: { bg: 'bg-red-100', text: 'text-red-800', label: 'Dibatalkan' },
        }
        return statusMap[status] || { bg: 'bg-gray-100', text: 'text-gray-800', label: 'Status' }
    }

    return (
        <AuthLayout>
            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <div className="relative pb-8 pt-8 text-center" style={{ backgroundColor: primaryColor }}>
                    <button
                        type="button"
                        onClick={() => window.history.back()}
                        className="absolute left-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur hover:bg-white/30 transition"
                    >
                        <ArrowLeftIcon className="h-5 w-5" />
                    </button>

                    <h1 className="text-xl font-semibold text-white">Reservasi Acara</h1>
                </div>

                {/* Reservations List */}
                <div className="space-y-4 px-5 pb-8 pt-4">
                    {reservations.data && reservations.data.length > 0 ? (
                        <>
                            {reservations.data.map((reservation) => {
                                const statusBadge = getStatusBadge(reservation.status)
                                return (
                                    <Link
                                        key={reservation.id}
                                        href={`/events/reservations/${reservation.code}`}
                                        className="block rounded-2xl bg-white p-5 shadow-sm transition hover:shadow-md"
                                    >
                                        <div className="flex items-start justify-between">
                                            <div>
                                                <h2 className="font-semibold text-gray-900">
                                                    {reservation.event?.title}
                                                </h2>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {formatDate(reservation.event?.event_date)}
                                                </p>
                                            </div>
                                            <span className={`shrink-0 rounded-full px-3 py-1 text-xs font-semibold ${statusBadge.bg} ${statusBadge.text}`}>
                                                {statusBadge.label}
                                            </span>
                                        </div>

                                        <div className="mt-4 flex items-center justify-between border-t pt-4">
                                            <div className="flex items-center gap-2 text-sm text-gray-500">
                                                <TicketIcon className="h-5 w-5" />
                                                {reservation.quantity} Peserta
                                            </div>

                                            <div className="flex items-center gap-1" style={{ color: primaryColor }}>
                                                <span className="text-sm font-medium">Detail</span>
                                                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </Link>
                                )
                            })}

                            {/* Pagination */}
                            {reservations.links && reservations.links.length > 0 && (
                                <div className="mt-6 flex justify-center gap-2">
                                    {reservations.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url}
                                            className={`px-3 py-2 rounded-lg text-sm font-medium transition ${
                                                link.active
                                                    ? 'text-white'
                                                    : 'text-gray-600 hover:bg-gray-100'
                                            }`}
                                            style={link.active ? { backgroundColor: primaryColor } : {}}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            )}
                        </>
                    ) : (
                        <div className="rounded-2xl bg-white px-6 py-14 text-center shadow-sm">
                            <div className="mx-auto flex h-20 w-20 items-center justify-center rounded-full" style={{ backgroundColor: `${primaryColor}20` }}>
                                <CalendarDaysIcon className="h-10 w-10" style={{ color: primaryColor }} />
                            </div>

                            <h2 className="mt-6 text-lg font-semibold">Belum Ada Reservasi</h2>

                            <p className="mt-2 text-sm text-gray-500">
                                Anda belum memiliki reservasi acara.
                            </p>

                            <Link
                                href="/events"
                                className="mt-6 inline-block px-6 py-2 font-medium text-white rounded-lg transition hover:opacity-90"
                                style={{ backgroundColor: primaryColor }}
                            >
                                Jelajahi Acara
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </AuthLayout>
    )
}
