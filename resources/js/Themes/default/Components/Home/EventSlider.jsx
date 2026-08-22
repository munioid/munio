import React from 'react'
import { Link } from '@inertiajs/react'

export default function EventSlider({ events, primaryColor }) {
    if (!events || events.length === 0) {
        return null
    }

    const placeholderImage = 'https://picsum.photos/500/300?1'

    const formatPrice = (price) => {
        if (!price) return 'FREE'
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(price)
    }

    return (
        <section className="mt-3 bg-white py-6">
            <div className="flex justify-between items-center px-5 mb-5">
                <h2 className="text-2xl font-semibold">Acara Terdekat</h2>
                <Link href="/events" className="font-medium" style={{ color: primaryColor }}>
                    Selengkapnya →
                </Link>
            </div>

            <div className="flex gap-4 overflow-x-auto px-5 pb-2 scroll-smooth">
                {events.map((event) => (
                    <div
                        key={event.id}
                        className="min-w-[275px] bg-white border rounded-2xl overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <img
                            src={event.cover?.path || placeholderImage}
                            className="h-45 w-full object-cover"
                            alt={event.title}
                        />
                        <div className="p-5">
                            <h3 className="mt-2 text-2xl font-semibold leading-tight line-clamp-2">
                                {event.title}
                            </h3>
                            <p className="text-gray-300 mt-4">{event.event_date}</p>
                            <div className="mt-3">
                                <span
                                    className="text-lg font-bold"
                                    style={{ color: primaryColor }}
                                >
                                    {formatPrice(event.price)}
                                </span>
                            </div>
                            <div className="mt-5 flex gap-3">
                                <Link
                                    href={`/events/${event.slug}`}
                                    className="flex-1 rounded-xl border py-3 font-medium hover:opacity-80 transition text-center"
                                    style={{
                                        borderColor: primaryColor,
                                        color: primaryColor,
                                    }}
                                >
                                    Detail
                                </Link>
                                {event.register_url && (
                                    <a
                                        href={event.register_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="flex-1 rounded-xl py-3 font-medium text-white hover:opacity-80 transition text-center"
                                        style={{ backgroundColor: primaryColor }}
                                    >
                                        Daftar
                                    </a>
                                )}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </section>
    )
}
