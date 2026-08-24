import React, { useState } from 'react'
import { usePage } from '@inertiajs/react'
import AppLayout from '../../Layouts/AppLayout'

export default function Reservation() {
    const { props } = usePage()
    const { event } = props
    const [loading, setLoading] = useState(false)
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        quantity: 1,
    })

    const getCoverImage = (event) => {
        if (event.cover?.media_url) {
            return event.cover.media_url
        }
        if (event.cover?.disk_path) {
            return event.cover.disk_path
        }
        return 'https://picsum.photos/200/200'
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

    const totalPrice = event.price ? event.price * formData.quantity : 0

    const handleInputChange = (e) => {
        const { name, value } = e.target
        setFormData(prev => ({
            ...prev,
            [name]: value
        }))
    }

    const handleQuantityChange = (change) => {
        setFormData(prev => ({
            ...prev,
            quantity: Math.max(1, Math.min(10, prev.quantity + change))
        }))
    }

    const handleSubmit = async () => {
        setLoading(true)

        try {
            const response = await fetch('/api/reservations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    event_id: event.id,
                    name: formData.name,
                    email: formData.email,
                    quantity: formData.quantity,
                })
            })

            if (!response.ok) {
                throw new Error('Gagal membuat reservasi')
            }

            const data = await response.json()
            // Redirect to reservation detail page
            window.location.href = `/events/reservations/${data.data.code}`
        } catch (error) {
            console.error('Error creating reservation:', error)
            alert('Gagal membuat reservasi. Silakan coba lagi.')
            setLoading(false)
        }
    }

    return (
        <AppLayout>
            <div>
                {/* Form */}
                <div className="min-h-screen bg-gray-50 pb-28">

                    {/* Event */}
                    <div className="bg-white p-5 shadow-sm">

                        <div className="flex gap-4">

                            <img
                                src={getCoverImage(event)}
                                alt={event.title}
                                className="h-24 w-24 rounded-xl object-cover"
                                onError={(e) => {
                                    e.target.src = 'https://picsum.photos/200/200'
                                }}
                            />

                            <div className="flex-1">

                                <h1 className="text-xl font-bold">
                                    {event.title}
                                </h1>

                                <div className="mt-3 space-y-2 text-sm text-gray-500">

                                    <div className="flex items-center gap-2">
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {event.event_date}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {/* Reservation */}
                    <div className="mt-5 space-y-5 px-5">

                        <div className="rounded-2xl bg-white p-5">

                            <h2 className="text-lg font-semibold">
                                Data Peserta
                            </h2>

                            <div className="mt-5 space-y-4">

                                <div>
                                    <label className="mb-2 block text-sm font-medium">
                                        Nama Lengkap
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        value={formData.name}
                                        onChange={handleInputChange}
                                        required
                                        className="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none"
                                    />
                                </div>

                                <div>
                                    <label className="mb-2 block text-sm font-medium">
                                        Email
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        value={formData.email}
                                        onChange={handleInputChange}
                                        required
                                        className="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-primary focus:outline-none"
                                    />
                                </div>

                            </div>

                        </div>

                        {/* Ticket */}
                        <div className="rounded-2xl bg-white p-5">

                            <h2 className="text-lg font-semibold">
                                Tiket
                            </h2>

                            <div className="mt-4 flex items-center justify-between">

                                <div>
                                    <div className="font-medium">
                                        Jumlah Tiket
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        Maksimal 10 tiket
                                    </div>
                                </div>

                                <div className="flex items-center gap-3">
                                    <button
                                        type="button"
                                        onClick={() => handleQuantityChange(-1)}
                                        disabled={formData.quantity <= 1}
                                        className="flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 transition hover:bg-gray-100 disabled:opacity-40"
                                    >
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 12H4" />
                                        </svg>
                                    </button>

                                    <span className="w-8 text-center text-lg font-semibold">
                                        {formData.quantity}
                                    </span>

                                    <button
                                        type="button"
                                        onClick={() => handleQuantityChange(1)}
                                        disabled={formData.quantity >= 10}
                                        className="flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 transition hover:bg-gray-100 disabled:opacity-40"
                                    >
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>

                                </div>

                            </div>

                        </div>

                        {/* Summary */}
                        <div className="rounded-2xl bg-white p-5">

                            <h2 className="text-lg font-semibold">
                                Ringkasan
                            </h2>

                            <div className="mt-4 space-y-3 text-sm">

                                <div className="flex justify-between">
                                    <span>Harga Tiket</span>
                                    <span>
                                        {formatPrice(event.price)}
                                    </span>
                                </div>

                                <div className="flex justify-between">
                                    <span>Jumlah</span>
                                    <span>{formData.quantity}</span>
                                </div>

                                <div className="flex justify-between border-t pt-3 text-base font-bold">
                                    <span>Total</span>

                                    <span className="text-primary">
                                        {formatPrice(totalPrice)}
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {/* Bottom Action */}
                <div className="fixed inset-x-0 bottom-20">
                    <div className="mx-auto max-w-md px-4">
                        <button
                            onClick={handleSubmit}
                            disabled={loading}
                            className="w-full rounded-2xl bg-primary py-4 text-lg font-semibold text-white shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? 'Memproses...' : 'Daftar'}
                        </button>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}
