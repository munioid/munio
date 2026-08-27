import React from 'react'
import { usePage } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'

export default function ReservationDetail() {
    const { props } = usePage()
    const { reservation } = props
    const event = reservation.event

    const getCoverImage = (event) => {
        if (event.cover?.media_url) {
            return event.cover.media_url
        }
        if (event.cover?.disk_path) {
            return event.cover.disk_path
        }
        return 'https://picsum.photos/1200/600'
    }

    const formatPrice = (price) => {
        if (!price) return 'GRATIS'
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(price)
    }

    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        })
    }

    const getStatusColor = (status) => {
        const colors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-green-100 text-green-800',
            'completed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800',
        }
        return colors[status] || 'bg-gray-100 text-gray-800'
    }

    const getStatusLabel = (status) => {
        const labels = {
            'pending': 'Menunggu',
            'confirmed': 'Terkonfirmasi',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan',
        }
        return labels[status] || status
    }

    return (
        <AppLayout title="Reservation Detail">
            <div>
                <div className="min-h-screen bg-gray-50 pb-10">

                    {/* Event Cover */}
                    <div className="relative">
                        <img
                            src={getCoverImage(event)}
                            alt={event.title}
                            className="aspect-[16/9] w-full object-cover"
                            onError={(e) => {
                                e.target.src = 'https://picsum.photos/1200/600'
                            }}
                        />
                    </div>

                    <div className="-mt-6 relative rounded-t-3xl bg-white px-5 pt-6">

                        {/* Status */}
                        <div className="flex items-center justify-between">
                            <div>
                                <div className="text-xs font-semibold uppercase text-primary">
                                    Reservasi
                                </div>

                                <h1 className="mt-2 text-3xl font-bold leading-tight">
                                    {event.title}
                                </h1>
                            </div>

                            <div className={`shrink-0 rounded-full px-3 py-1 text-xs font-semibold ${getStatusColor(reservation.status)}`}>
                                {getStatusLabel(reservation.status)}
                            </div>
                        </div>

                        {/* Reservation Code */}
                        <div className="mt-3 text-sm text-gray-500">
                            Kode Reservasi
                        </div>

                        <div className="font-mono text-lg font-semibold">
                            {reservation.code}
                        </div>

                        {/* Information */}
                        <div className="mt-6 divide-y divide-gray-100 rounded-2xl border">

                            {/* Date */}
                            <div className="flex items-start gap-4 p-4">
                                <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>

                                <div>
                                    <div className="font-medium">
                                        Jadwal Event
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        {event.event_date}
                                    </div>
                                </div>
                            </div>

                            {/* Participant */}
                            <div className="flex items-start gap-4 p-4">
                                <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>

                                <div>
                                    <div className="font-medium">
                                        Reservasi Oleh
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        {reservation.name}
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        {reservation.email}
                                    </div>
                                </div>
                            </div>

                            {/* Quantity */}
                            <div className="flex items-start gap-4 p-4">
                                <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2H6v-2z" />
                                </svg>

                                <div>
                                    <div className="font-medium">
                                        Jumlah Tiket
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        {reservation.quantity} Tiket
                                    </div>
                                </div>
                            </div>

                            {/* Payment */}
                            <div className="flex items-start gap-4 p-4">
                                <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>

                                <div>
                                    <div className="font-medium">
                                        Total Pembayaran
                                    </div>

                                    <div className="text-sm font-semibold text-primary">
                                        {formatPrice(reservation.amount)}
                                    </div>
                                </div>
                            </div>

                            {/* Created */}
                            <div className="flex items-start gap-4 p-4">
                                <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                <div>
                                    <div className="font-medium">
                                        Tanggal Reservasi
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        {formatDate(reservation.created_at)}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </AppLayout>
    )
}
