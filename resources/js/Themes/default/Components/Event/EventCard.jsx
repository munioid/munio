import React from 'react'
import { Link } from '@inertiajs/react'

export default function EventCard({ event }) {
    const getCoverImage = (event) => {
        if (event.cover?.media_url) {
            return event.cover.media_url
        }
        if (event.cover?.disk_path) {
            return event.cover.disk_path
        }
        return 'https://picsum.photos/800/400?1'
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
        <div className="overflow-hidden rounded-2xl bg-white shadow-sm">
            <img
                src={getCoverImage(event)}
                alt={event.title}
                className="aspect-[16/9] w-full object-cover"
                onError={(e) => {
                    e.target.src = 'https://picsum.photos/800/400?1'
                }}
            />
            <div className="p-5">
                <div className="flex items-start justify-between gap-4">
                    <div>
                        <h2 className="text-xl font-bold leading-tight line-clamp-2">
                            {event.title}
                        </h2>
                        <div className="mt-3 flex items-center gap-2 text-sm text-gray-500">
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{event.event_date}</span>
                        </div>
                        {event.category && (
                            <div className="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span>{event.category.name}</span>
                            </div>
                        )}
                    </div>
                    <div className="text-right">
                        <div className="text-xs uppercase text-gray-400">
                            Harga
                        </div>
                        <div className="mt-1 font-bold text-primary">
                            {formatPrice(event.price)}
                        </div>
                    </div>
                </div>
                <p className="mt-4 line-clamp-2 text-sm text-gray-500">
                    {event.excerpt}
                </p>
                <div className="mt-5 flex gap-3">
                    <Link
                        href={`/events/${event.slug}`}
                        className="flex-1 rounded-xl border border-primary text-primary py-3 font-medium hover:bg-primary hover:text-white transition text-center"
                    >
                        Detail
                    </Link>
                    {event.register_url && (
                        <a
                            href={event.register_url}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex-1 rounded-xl bg-primary py-3 font-medium text-white hover:opacity-90 transition text-center"
                        >
                            Daftar
                        </a>
                    )}
                </div>
            </div>
        </div>
    )
}
