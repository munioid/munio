import React from 'react'
import { Link, usePage } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'

export default function EventDetail() {
    const { props } = usePage()
    const { event } = props

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

    return (
        <AppLayout title={event.title}>
            <div className="min-h-screen bg-gray-50 pb-33">
                {/* Cover */}
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
                    {/* Category */}
                    {event.category && (
                        <div className="text-xs font-semibold uppercase text-primary">
                            {event.category.name}
                        </div>
                    )}

                    {/* Title */}
                    <h1 className="mt-2 text-3xl font-bold leading-tight">
                        {event.title}
                    </h1>

                    {/* Price */}
                    <div className="mt-4">
                        <span className="text-3xl font-bold text-primary">
                            {formatPrice(event.price)}
                        </span>
                    </div>

                    {/* Information */}
                    <div className="mt-6 divide-y divide-gray-100 rounded-2xl border">
                        <div className="flex items-start gap-4 p-4">
                            <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div className="font-medium">
                                    Tanggal
                                </div>
                                <div className="text-sm text-gray-500">
                                    {event.event_date}
                                </div>
                            </div>
                        </div>

                        {event.stocks && (
                            <div className="flex items-start gap-4 p-4">
                                <svg className="mt-0.5 h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2H6v-2z" />
                                </svg>
                                <div>
                                    <div className="font-medium">
                                        Kuota
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        {event.stocks} peserta
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Content */}
                    <div className="prose mt-8 max-w-none">
                        <div dangerouslySetInnerHTML={{ __html: event.content }} />
                    </div>

                </div>
            </div>

            {/* Bottom CTA */}
            <div className="fixed inset-x-0 bottom-20 z-40">
                <div className="mx-auto max-w-md px-4">
                    <div className="rounded-2xl border bg-white p-4 shadow-xl">
                        <div className="flex items-center justify-between">
                            <div>
                                <div className="text-xs text-gray-500">
                                    Harga
                                </div>

                                <div className="text-xl font-bold text-primary">
                                    {formatPrice(event.price)}
                                </div>
                            </div>

                            {event.register_url ? (
                                <a
                                    href={event.register_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="rounded-xl bg-primary px-6 py-3 font-medium text-white hover:opacity-90 transition"
                                >
                                    Daftar
                                </a>
                            ) : (
                                <Link
                                    href={`/events/${event.slug}/reservation`}
                                    className="rounded-xl bg-primary px-6 py-3 font-medium text-white hover:opacity-90 transition"
                                >
                                    Reservasi
                                </Link>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}
