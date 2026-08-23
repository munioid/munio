import React, { useState } from 'react'
import { usePage, router } from '@inertiajs/react'
import AuthLayout from '../../Layouts/AuthLayout'

export default function Login() {
    const { props } = usePage()
    const { organization } = props
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [errors, setErrors] = useState({})
    const [isLoading, setIsLoading] = useState(false)

    const getCsrfToken = () => {
        const token = document.querySelector('meta[name="csrf-token"]')?.content
        if (!token) {
            console.warn('CSRF token not found in meta tag')
        }
        return token || ''
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        setIsLoading(true)
        setErrors({})

        try {
            const csrfToken = getCsrfToken()
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ email, password }),
                credentials: 'same-origin',
            })

            const data = await response.json()

            if (response.ok && data.success) {
                // Successful login
                window.location.href = '/'
            } else {
                // Login failed with validation errors
                if (data.errors) {
                    setErrors(data.errors)
                } else {
                    setErrors({ form: data.message || 'Login failed' })
                }
            }
        } catch (error) {
            setErrors({ form: error.message })
        } finally {
            setIsLoading(false)
        }
    }

    return (
        <AuthLayout>
            <div className="flex flex-col items-center justify-center px-4 py-8">
                <div className="w-full max-w-sm">
                    <div className="text-center mb-8">
                        <h2 className="text-2xl font-bold text-gray-900">
                            Login ke {organization?.name}
                        </h2>
                        <p className="mt-2 text-gray-600">
                            Masukkan kredensial Anda untuk lanjut
                        </p>
                    </div>

                    {errors.form && (
                        <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                            {errors.form}
                        </div>
                    )}

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input
                                type="email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent outline-none transition ${
                                    errors.email
                                        ? 'border-red-500'
                                        : 'border-gray-300'
                                }`}
                                placeholder="your@email.com"
                                required
                            />
                            {errors.email && (
                                <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input
                                type="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent outline-none transition ${
                                    errors.password
                                        ? 'border-red-500'
                                        : 'border-gray-300'
                                }`}
                                placeholder="••••••••"
                                required
                            />
                            {errors.password && (
                                <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                            )}
                        </div>

                        <button
                            type="submit"
                            disabled={isLoading}
                            className="w-full py-2 px-4 bg-[var(--primary-color)] text-white font-medium rounded-lg hover:opacity-90 disabled:opacity-50 transition"
                        >
                            {isLoading ? 'Logging in...' : 'Login'}
                        </button>
                    </form>
                </div>
            </div>
        </AuthLayout>
    )
}
